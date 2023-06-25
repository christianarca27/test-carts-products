<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use FakerRestaurant\Provider\it_IT\Restaurant as FakerRestaurant;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $faker->addProvider(new FakerRestaurant($faker));
        for ($i = 0; $i < 10; $i++) {
            $newProduct = new Product();

            $newProduct->SKU = $faker->regexify('[A-Z0-9]{12}');

            do {
                $newProduct->name = $faker->foodName();
            } while (Product::where('name', $newProduct->name)->first());

            $newProduct->price = $faker->randomFloat(2, 5, 20);

            $newProduct->save();
        }
    }
}
