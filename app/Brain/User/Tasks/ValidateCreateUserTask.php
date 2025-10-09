<?php

declare(strict_types = 1);

namespace App\Brain\User\Tasks;

use Brain\Task;
use Illuminate\Support\Facades\Validator;

/**
 * Task ValidateCreateUserTask
 *
 * @property-read string $email
 * @property-read string $username
 * @property-read string $name
 */
class ValidateCreateUserTask extends Task
{
    public function handle(): self
    {
        Validator::make(
            $this->toArray(),
            [
                'name'     => ['required', 'min:3', 'max:100'],
                'username' => ['required', 'min:3', 'max:100', 'unique:users,username', 'alpha_dash'],
                'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            ]
        )->validate();

        return $this;
    }
}
