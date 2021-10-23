<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProductWarehouse
{
    /**
     * Subtracts items from the warehouse.
     *
     * @param Collection $products
     * @param array $data
     * @return void
     */
    public function subtractProducts(Collection $products, array $data): void;

    /**
     * Updates the number of products in stock.
     *
     * @param Collection $products
     * @param array $data
     * @return void
     */
    public function updateStockProducts(Collection $products, array $data): void;

    /**
     * Return all goods to the warehouse.
     *
     * @param Collection $products
     * @return void
     */
    public function allProductsToWarehouse(Collection $products): void;
}
