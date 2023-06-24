<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        for ($i = 0; $i < 10; $i++) {
            $newProduct = new Product();

            $newProduct->SKU = $faker->regexify('[A-Z0-9]{12}');
            $newProduct->name = $faker->word();
            $newProduct->price = $faker->randomFloat(2, 0, 100);

            $newProduct->save();
        }
    }
}
