<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 11; $i++) {
            $product = new Product();
            $product->name = "Товары №$i";
            $product->price = rand(100, 500);
            $product->stock = rand(1, 25);
            $product->save();
        }
    }
}
