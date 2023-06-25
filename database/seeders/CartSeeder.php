<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        for ($i = 0; $i < 5; $i++) {
            $newCart = new Cart();

            $newCart->save();

            $productsNumber = $faker->numberBetween(1, 10);
            for ($j = 0; $j < $productsNumber; $j++) {
                $randomProduct = $faker->randomElement(Product::all());

                // controllo se è già stato aggiunto un prodotto di tipo $randomProduct
                if ($newCart->products()->wherePivot('product_id', $randomProduct->id)->exists()) {
                    // in caso affermativo incremento solo la quantità
                    $newCart->products()->wherePivot('product_id', $randomProduct->id)->increment('quantity');
                } else {
                    // altrimenti aggiungo il record
                    $newCart->products()->attach($randomProduct);
                }
            }
        }
    }
}
