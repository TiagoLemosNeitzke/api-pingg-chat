<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        User::all()
            ->each(fn (User $user) => Workspace::factory()->create(['owner_id' => $user->id]));
    }
}
