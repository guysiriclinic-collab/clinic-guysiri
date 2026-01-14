<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $roles = ['Admin', 'PT', 'Staff', 'Manager', 'Doctor', 'Nurse', 'Receptionist'];
        return [
            'name' => fake()->randomElement($roles) . '_' . fake()->unique()->randomNumber(5),
            'description' => fake()->sentence(),
            'is_system' => false,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin',
            'is_system' => true,
        ]);
    }

    public function pt(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'PT',
            'is_system' => true,
        ]);
    }
}
