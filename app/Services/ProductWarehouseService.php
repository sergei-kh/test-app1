<?php

namespace App\Services;

use App\Contracts\ProductWarehouse;

use Illuminate\Database\Eloquent\Collection;

class ProductWarehouseService implements ProductWarehouse
{
    public function subtractProducts(Collection $products, array $data): void
    {
        foreach ($data as $id => $datum) {
            $product = $products->firstWhere('id', $id);
            $newStock = $product->stock - $datum['count'];
            if ($newStock >= 0) {
                $product->stock = $newStock;
                $product->save();
            }
        }
    }

    public function updateStockProducts(Collection $products, array $data): void
    {
        foreach ($data['success'] as $datum) {
            if (!isset($datum['new_product'])) {
                $product = $products->firstWhere('id', $datum['id']);
                if (isset($datum['increase_count'])) {
                    $product->stock = $product->stock - $datum['increase_count'];
                    $product->save();
                } elseif(isset($datum['subtract_count'])) {
                    $product->stock = $product->stock + $datum['subtract_count'];
                    $product->save();
                }
            } else {
                $product = $datum['object'];
                $product->stock = $product->stock - $datum['count'];
                $product->save();
            }
        }

        foreach ($data['deleted'] as $datum) {
            $product = $products->firstWhere('id', $datum['id']);
            $product->stock = $product->stock + $datum['count'];
            $product->save();
        }
    }

    public function allProductsToWarehouse(Collection $products): void
    {
        foreach ($products as $product) {
            $product->stock = $product->stock + $product->pivot->count;
            $product->pivot->count = 0;
            $product->save();
            $product->pivot->save();
        }
    }
}
