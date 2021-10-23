<?php

namespace App\Helpers\Order;

use Illuminate\Database\Eloquent\Collection;

/**
 * Helper class for working with orders
 */
class OrderHelper
{
    /**
     * Get an array of product IDs that have been deleted from order
     *
     * @param Collection $products
     * @param array $data
     * @return array
     */
    public static function getRemoveIds(Collection $products, array $data): array
    {
        $output = [];
        foreach ($products as $product) {
            $flag = false;
            foreach ($data as $datum) {
                if ($product->id == $datum['id']) {
                    $flag = true;
                }
            }
            if (!$flag) {
                $output[] = [
                    'id' => $product->id,
                    'count' => $product->pivot->count,
                ];
            }
        }
        return $output;
    }

    /**
     * The method returns the total amount of the order
     *
     * @param Collection $orders
     * @return int
     */
    public static function getTotalPriceProducts(Collection $orders): int
    {
        $total = 0;
        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $total += $product->pivot->cost * $product->pivot->count;
            }
        }
        return round($total);
    }
}
