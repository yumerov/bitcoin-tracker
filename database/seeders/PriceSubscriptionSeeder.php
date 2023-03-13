<?php

namespace Database\Seeders;

use App\Models\PriceSubscription;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PriceSubscriptionSeeder extends Seeder
{

    public function run(): void
    {
        $faker = Faker::create();
        $price = $faker->randomFloat(2, 10000, 30000);

        for ($i = 1; $i <= 25; $i++) {
            $price = $price + $faker->randomFloat(2, -500, 500);
            (new PriceSubscription([
                'email' => $faker->email(),
                'price' => $price
            ]))->save();
        }
    }
}
