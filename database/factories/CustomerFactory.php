<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'cust_name' => fake()->name(),
            'cust_address' => fake()->address(),
            'cust_director_name' => fake()->name(),
            'cust_contact_number' => fake()->phoneNumber(),
            'cust_email' => fake()->unique()->safeEmail(),
            'cust_type' => 'EU'
        ];
    }
}
