<?php

declare(strict_types = 1);

namespace App\Brain\User\Tasks;

use App\Models\User;
use Brain\Task;

/**
 * Task SendWelcomeEmailTask
 *
 * @property-read User $user
 */
class SendWelcomeEmailTask extends Task
{
    public function handle(): self
    {
        $this->user->notify(new \App\Notifications\WelcomeEmailNotification());

        return $this;
    }
}
