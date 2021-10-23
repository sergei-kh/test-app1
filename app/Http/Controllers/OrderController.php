<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\OrderRequest;
use App\Helpers\Product\ProductHelper;
use App\Contracts\ProductWarehouse;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Service for working with leftovers
     * @var ProductWarehouse
     */
    protected $productWarehouse;

    public function __construct(ProductWarehouse $productWarehouse)
    {
        $this->productWarehouse = $productWarehouse;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $data = json_decode($request->products, true);
        $arrayId = ProductHelper::getIds($data);
        $products = Product::find($arrayId);
        $checkStock = ProductHelper::checkStock($products, $data);
        $dataProducts = ProductHelper::calculateCostProduct($products, $data);
        $checkCost = ProductHelper::checkCost($dataProducts);
        if (!empty($checkStock) || !empty($checkCost)) {
            return response()->json([
                'status' => false,
                'stock' => $checkStock,
                'cost' => $checkCost,
            ], 422);
        } else {
            $order = new Order();
            if ($request->status === 'canceled') {
                $order->completed_at = Carbon::now();
            }
            $order->store($request);
            $order->products()->attach($dataProducts);
            if ($request->status !== 'canceled') {
                $this->productWarehouse->subtractProducts($products, $dataProducts);
            }
            return response()->json([
                'status' => true
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $order = Order::where('id', $id)->with('products')->first();
        if ($order !== null) {
            return response()->json([
                'status' => true,
                'order' => new OrderResource($order)
            ]);
        } else {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(OrderRequest $request, $id): JsonResponse
    {
        $data = json_decode($request->products, true);
        $order = Order::findOrFail($id);
        Log::debug($request->all());
        if ($request->status === 'completed') {
            $request->query->set('completed_at', Carbon::now());
        } elseif ($request->status !== 'completed') {
            $request->query->set('completed_at', null);
        }
        $products = $order->products()->get();
        if ($request->status !== 'canceled') {
            $infoStock = ProductHelper::infoStockAtUpdate($products, $data);
            Log::debug($infoStock);
            $dataProducts = ProductHelper::calculateCostCountAtUpdate($products, $infoStock['success']);
            Log::debug($dataProducts);
            $checkCost = ProductHelper::checkCost($dataProducts);
            Log::debug($checkCost);
            if (!empty($infoStock['errors']) || !empty($checkCost)) {
                return response()->json([
                    'status' => false,
                    'stock' => $infoStock['errors'],
                    'cost' => $checkCost,
                ], 422);
            }
            $order->update($request->all());
            $order->products()->sync($dataProducts);
            $this->productWarehouse->updateStockProducts($products, $infoStock);
        } elseif ($order->status !== 'canceled') {
            $this->productWarehouse->allProductsToWarehouse($products);
            $order->update($request->all());
        } else {
            $order->update($request->all());
        }
        return response()->json([
            'status' => true,
            'id' => $order->id,
        ]);
    }

    /**
     * Displays information about goods in the order
     *
     * @param $id
     * @return JsonResponse
     */
    public function info($id): JsonResponse
    {
        $order = Order::findOrFail($id);
        return response()->json([
            'status' => true,
            'products' => Product::all(),
            'products_order' => ProductResource::collection($order->products()->get()),
        ]);
    }
}
