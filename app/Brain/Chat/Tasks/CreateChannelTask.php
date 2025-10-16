<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Tasks;

use App\Models\Channel;
use Brain\Task;

/**
 * Task CreateChannelTask
 *
 * @property-read string $short_id
 * @property-read string $icon
 * @property-read string $name
 * @property-read string $description
 * @property-read int $workspace_id
 * @property-read bool $is_private
 * @property-read bool $is_dm
 *
 * @property Channel $channel
 */
class CreateChannelTask extends Task
{
    public function rules(): array
    {
        return [
            'short_id'     => ['required', 'string', 'unique:channels,short_id'],
            'icon'         => ['nullable', 'string', 'max:1'],
            'name'         => ['required', 'min:3', 'max:15', 'unique:channels,name', 'alpha_dash'],
            'description'  => ['nullable', 'max:255'],
            'workspace_id' => ['required', 'integer', 'exists:workspaces,id'],
            'is_private'   => ['boolean'],
            'is_dm'        => ['boolean'],
        ];
    }

    public function handle(): self
    {
        $this->channel = Channel::query()
            ->create([
                'short_id'     => $this->short_id,
                'icon'         => $this->icon ?? '',
                'name'         => $this->name,
                'description'  => $this->description,
                'workspace_id' => $this->workspace_id,
                'is_private'   => $this->is_private ?? false,
                'is_dm'        => $this->is_dm ?? false,
            ]);

        return $this;
    }
}
