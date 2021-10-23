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
}
