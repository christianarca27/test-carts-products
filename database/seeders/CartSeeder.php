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
                $newCart->products()->attach($randomProduct->id);
            }
        }
    }
}
