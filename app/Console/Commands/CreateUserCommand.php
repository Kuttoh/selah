<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user account';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->askForName();
        $email = $this->askForEmail();
        $password = $this->askForPassword();

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("User created: {$user->email}");

        return self::SUCCESS;
    }

    private function askForName(): string
    {
        do {
            $name = trim((string) $this->ask('Name'));

            if ($name === '') {
                $this->error('Name is required.');
            }
        } while ($name === '');

        return $name;
    }

    private function askForEmail(): string
    {
        do {
            $email = (string) $this->ask('Email');

            $validator = Validator::make([
                'email' => $email,
            ], [
                'email' => ['required', 'email', 'unique:users,email'],
            ]);

            if ($validator->fails()) {
                $this->error($validator->errors()->first('email'));
            }
        } while ($validator->fails());

        return $email;
    }

    private function askForPassword(): string
    {
        do {
            $password = (string) $this->secret('Password');

            if ($password === '') {
                $this->error('Password is required.');
            }
        } while ($password === '');

        return $password;
    }
}
