<?php

namespace Database\Seeders;

use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use \Faker\Factory as Faker;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $price = $faker->randomFloat(2, 10000, 30000);
        $timestamp = Carbon::now();

        for ($i = 1; $i <= 100; $i++) {
            $price = $price + $faker->randomFloat(2, -500, 500);
            $product = new Price([
                'price' => $price,
                'timestamp' => $timestamp->addMinute()->format('Y-m-d H:i:s')
            ]);
            $product->save();
        }
    }
}
