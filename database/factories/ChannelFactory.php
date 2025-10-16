<?php

declare(strict_types = 1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'short_id'     => $this->faker->unique()->regexify('[a-zA-Z0-9]{8}'),
            'icon'         => $this->faker->randomElement(['#', '📢', '💬', '📣', '🔔', '📌', '🎯', '🚀', '💡', '🔥', '⭐', '🎨', '🎵', '🎮', '📚', '🔧', '🌟', '💻', '📱', '🎉']),
            'name'         => $this->faker->unique()->bothify('channel-#######'),
            'description'  => $this->faker->optional()->sentence(6),
            'workspace_id' => \App\Models\Workspace::factory(),
            'is_private'   => $this->faker->boolean(20),
            'is_dm'        => false,
        ];
    }

    public function private(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => true,
        ]);
    }

    public function dm(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_dm'      => true,
            'is_private' => true,
        ]);
    }
}
