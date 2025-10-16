<?php

declare(strict_types = 1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkspaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id'    => \App\Models\User::factory(),
            'icon'        => $this->faker->randomElement(['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']),
            'name'        => $this->faker->unique()->bothify('workspace-#######'),
            'description' => $this->faker->optional()->sentence(6),
        ];
    }
}
