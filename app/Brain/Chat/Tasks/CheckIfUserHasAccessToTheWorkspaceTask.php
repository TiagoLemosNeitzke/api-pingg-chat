<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Tasks;

use Brain\Task;
use Illuminate\Validation\ValidationException;

/**
 * Task CheckIfUserHasAccessToTheWorkspaceTask
 *
 * @property-read User $user
 * @property-read int $workspace_id
 */
class CheckIfUserHasAccessToTheWorkspaceTask extends Task
{
    public function handle(): self
    {
        if (
            $this->user->workspaces()->where('workspace_id', $this->workspace_id)->doesntExist()
        ) {
            throw ValidationException::withMessages([
                'user_id' => __('The user does not have access to the workspace.'),
            ]);
        }

        return $this;
    }
}
