<?php

namespace App\Helpers\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

use App\Helpers\Order\OrderHelper;

/**
 * Helper class for working with goods
 */
class ProductHelper
{
    /**
     * Checks the requested quantity of goods.
     * If there are enough products, it will return a empty array.
     * Otherwise, it will return an array of products for which there are not enough leftovers.
     *
     * @param Collection $products
     * @param array $data
     * @return array
     */
    public static function checkStock(Collection $products, array $data): array
    {
        $output = [];
        foreach ($data as $datum) {
            $product = $products->firstWhere('id', $datum['id']);
            if (
                $datum['count'] > $product->stock
                || $product->stock === 0
                || $datum['count'] < 1
            ) { // If you need more than there is in the database or requested 0 products
                $output[] = [
                    'id' => $datum['id'],
                    'max_stock' => $product->stock,
                ];
            }
        }
        return $output;
    }

    /**
     * Checks the requested quantity of items when editing an order.
     * Returns an array $errors with information about the products,
     * the quantity of which is not enough to update order.
     * The $success array contains information about what to do with the number of products in the order,
     * and information about new products, if any.
     *
     * @param array $data
     * @param Collection $products
     * @return array[]
     */
    public static function infoStockAtUpdate(Collection $products, array $data): array
    {
        $errors = [];
        $success = [];
        $newProductId = [];
        $temp = [];
        foreach ($data as $datum) {
            $product = $products->firstWhere('id', $datum['id']);
            if ($product !== null) {
                if ($product->stock === 0) { // If in stock 0
                    // If there are more items requested than in the order
                    if ($datum['count'] > $product->pivot->count) {
                        $errors[] = [
                            'id' => $datum['id'],
                            'max_stock' => $product->pivot->count,
                        ];
                        // If the number of products is less than or equal to the number of products in the order
                    } elseif ($datum['count'] > 0 && $datum['count'] < $product->pivot->count) {
                        $success[] = [
                            'id' => $datum['id'],
                            'subtract_count' => $product->pivot->count - $datum['count'],
                            'discount' => $datum['discount'],
                        ];
                    } elseif ($datum['count'] < 0) {
                        $errors[] = [
                            'id' => $datum['id'],
                            'max_stock' => $product->pivot->count,
                        ];
                    } else {
                        $success[] = [
                            'id' => $datum['id'],
                            'default_count' => $datum['count'],
                            'discount' => $datum['discount'],
                        ];
                    }
                } else {
                    if ($datum['count'] > $product->pivot->count) {
                        $fromStock = $datum['count'] - $product->pivot->count;
                        if ($fromStock <= $product->stock) {
                            $success[] = [
                                'id' => $datum['id'],
                                'increase_count' => $fromStock,
                                'discount' => $datum['discount'],
                            ];
                        } else {
                            $errors[] = [
                                'id' => $datum['id'],
                                'max_stock' => $product->stock + $product->pivot->count,
                            ];
                        }
                    } elseif ($datum['count'] > 0 && $datum['count'] < $product->pivot->count) {
                        $success[] = [
                            'id' => $datum['id'],
                            'subtract_count' => $product->pivot->count - $datum['count'],
                            'discount' => $datum['discount'],
                        ];
                    } elseif ($datum['count'] < 0) {
                        $errors[] = [
                            'id' => $datum['id'],
                            'max_stock' => $product->stock + $product->pivot->count,
                        ];
                    } else {
                        $success[] = [
                            'id' => $datum['id'],
                            'default_count' => $datum['count'],
                            'discount' => $datum['discount'],
                        ];
                    }
                }
            } else {
                $newProductId[] = $datum['id'];
                $temp[] = [
                    'id' => $datum['id'],
                    'count' => $datum['count'],
                    'discount' => $datum['discount'],
                    'new_product' => true,
                ];
            }
        }
        $newProducts = Product::find($newProductId);
        foreach ($temp as $item) {
            $tempItem = $item;
            $product = $newProducts->firstWhere('id', $item['id']);
            $tempItem['object'] = $product;
            $success[] = $tempItem;
        }
        $newErrors = self::checkStock($newProducts, $temp);
        foreach ($newErrors as $error) {
            $errors[] = $error;
        }
        $deleted = OrderHelper::getRemoveIds($products, $data);
        return [
            'errors' => $errors,
            'success' => $success,
            'deleted' => $deleted,
        ];
    }

    /**
     * Checks the correctness of the value of the goods after subtracting the percentage.
     * Returns an array of products with a negative price.
     * Otherwise, an empty array.
     *
     * @param array $data
     * @return array
     */
    public static function checkCost(array $data): array
    {
        $output = [];
        foreach ($data as $id => $datum) {
            if ($datum['cost'] < 0) {
                $output[] = [
                    'id' => $id,
                    'cost' => $datum['cost'],
                ];
            }
        }
        return $output;
    }

    /**
     * Returns an array of product IDs.
     *
     * @param array $data
     * @return array
     */
    public static function getIds(array $data): array
    {
        $output = [];
        foreach ($data as $datum) {
            $output[] = $datum['id'];
        }
        return $output;
    }

    /**
     * Calculates the price of an item taking into account the discount
     *
     * @param Collection $products
     * @param array $data
     * @return array
     */
    public static function calculateCostProduct(Collection $products, array $data): array
    {
        $output = [];
        foreach ($data as $datum) {
            $product = $products->firstWhere('id', $datum['id']);
            $cost = self::getPrice($product->price, $datum['discount']);
            $output[$datum['id']] = [
                'count' => $datum['count'],
                'discount' => $datum['discount'],
                'cost' => $cost,
            ];
        }
        return $output;
    }

    /**
     * Calculates the cost of products, taking into account the discount and the number of the products
     *
     * @param Collection $products
     * @param array $data
     * @return array
     */
    public static function calculateCostCountAtUpdate(Collection $products, array $data): array
    {
        $output = [];
        foreach ($data as $datum) {
            if (isset($datum['new_product'])) {
                $product = $datum['object'];
                $price = self::getPrice($product->price, $datum['discount']);
                $output[$datum['id']] = [
                    'count' => $datum['count'],
                    'discount' => $datum['discount'],
                    'cost' => $price,
                ];
            } else {
                $product = $products->firstWhere('id', $datum['id']);
                $count = self::changeCount($datum, $product);
                $price = self::getPrice($product->price, $datum['discount']);
                $output[$datum['id']] = [
                    'count' => $count,
                    'discount' => $datum['discount'],
                    'cost' => $price,
                ];
            }
        }
        return $output;
    }

    /**
     * Get product price
     *
     * @param float $price
     * @param float $percent
     * @return float
     */
    public static function getPrice(float $price, float $percent): float
    {
        $temp = ($price / 100) * $percent;
        return $price - $temp;
    }

    /**
     * Calculates the quantity of product
     * @param array $item
     * @param Product $product
     * @return int
     */
    public static function changeCount(array $item, Product $product): int
    {
        $result = 0;
        $count = $product->pivot->count;
        if (isset($item['increase_count'])) {
            $result = $count + $item['increase_count'];
        } elseif (isset($item['subtract_count'])) {
            $result = $count - $item['subtract_count'];
        } elseif (isset($item['default_count'])) {
            $result = $item['default_count'];
        }
        return $result;
    }
}
