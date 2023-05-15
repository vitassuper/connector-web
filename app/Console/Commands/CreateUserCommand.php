<?php

namespace App\Console\Commands;

use Hash;
use App\Models\User;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    protected $description = 'Create a new user';

    protected $signature = 'create:user';

    public function handle(): void
    {
        $username = $this->ask('Enter the username');
        $password = $this->secret('Enter the user password');
        $confirmPassword = $this->secret('Confirm the user password');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match');
            return;
        }

        $user = new User();
        $user->username = $username;
        $user->password = Hash::make($password);
        $user->save();

        $this->info('User created successfully');
    }
}
