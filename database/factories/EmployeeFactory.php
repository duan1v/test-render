<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'age'        => random_int(10, 30),
            'type'       => random_int(0, 5),
            'name'       => Str::random(10),
            'country'    => Str::random(5),
            'job'        => Str::random(7),
            'brief'      => Str::random(100),
            'email'      => Str::random(10) . '@gmail.com',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
