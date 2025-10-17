<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Tasks;

use App\Models\User;
use Brain\Task;

/**
 * Task AttachChannelToTheUserTask
 *
 * @property-read int $channel_id
 * @property-read User $user
 */
class AttachChannelToTheUserTask extends Task
{
    public function handle(): self
    {
        $this->user->channels()->attach($this->channel_id);

        return $this;
    }
}
