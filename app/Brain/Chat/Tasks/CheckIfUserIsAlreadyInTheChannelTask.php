<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Tasks;

use App\Models\Channel;
use App\Models\User;
use Brain\Task;
use Illuminate\Validation\ValidationException;

/**
 * Task CheckIfUserIsAlreadyInTheChannelTask
 *
 * @property-read int $channel_id
 * @property-read int $user_id
 *
 * @property User $user
 * @property int $workspace_id
 */
class CheckIfUserIsAlreadyInTheChannelTask extends Task
{
    public function rules(): array
    {
        return [
            'channel_id' => ['required', 'integer', 'exists:channels,id'],
            'user_id'    => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function handle(): self
    {
        $this->user = User::findOrFail($this->user_id);

        if ($this->user->channels()->where('channel_id', $this->channel_id)->exists()) {
            throw ValidationException::withMessages([
                'user_id' => __('The user is already subscribed to this channel.'),
            ]);
        }

        $this->workspace_id = Channel::findOrFail($this->channel_id)->workspace_id;

        return $this;
    }
}
