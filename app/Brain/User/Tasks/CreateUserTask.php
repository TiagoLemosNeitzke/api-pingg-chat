<?php

declare(strict_types = 1);

namespace App\Brain\User\Tasks;

use App\Models\User;
use Brain\Task;

/**
 * Task CreateUserTask
 *
 * @property-read string $email
 * @property-read string $name
 * @property-read string $username
 *
 * @property User $user
 */
class CreateUserTask extends Task
{
    public function handle(): self
    {
        $this->user = User::query()
            ->create([
                'name'     => $this->name,
                'username' => $this->username,
                'email'    => $this->email,
            ]);

        return $this;
    }
}
