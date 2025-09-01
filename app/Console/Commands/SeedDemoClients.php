<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Project;

class SeedDemoClients extends Command
{
    protected $signature = 'imhotion:seed-demo-clients';
    protected $description = 'Create demo client users and assign projects without an owner to them at random';

    public function handle(): int
    {
        $clients = [
            ['email' => 'client1@imhotion.com', 'name' => 'Client One'],
            ['email' => 'client2@imhotion.com', 'name' => 'Client Two'],
        ];

        $ids = [];
        foreach ($clients as $c) {
            $user = User::query()->firstOrCreate(
                ['email' => $c['email']],
                [
                    'name'     => $c['name'],
                    'password' => Hash::make('ChangeMe123!'),
                    'role'     => 'client',
                ],
            );
            $this->info("Client: {$user->email} (id={$user->id})");
            $ids[] = $user->id;
        }

        if (empty($ids)) {
            $this->error('No demo clients available.');
            return self::FAILURE;
        }

        $assigned = 0;
        Project::whereNull('user_id')->orderBy('id')->chunkById(100, function ($chunk) use ($ids, &$assigned) {
            foreach ($chunk as $p) {
                $p->user_id = Arr::random($ids);
                $p->save();
                $assigned++;
            }
        });

        $this->info("Assigned owners to {$assigned} projects without owner.");
        return self::SUCCESS;
    }
}

