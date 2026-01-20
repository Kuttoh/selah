<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Tests\TestCase;

class UserCreateCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_user_with_a_hashed_password(): void
    {
        $this->artisan('user:create')
            ->expectsQuestion('Name', 'Ada Lovelace')
            ->expectsQuestion('Email', 'ada@example.com')
            ->expectsQuestion('Password', 'secret123')
            ->expectsOutput('User created: ada@example.com')
            ->assertExitCode(SymfonyCommand::SUCCESS);

        $user = User::query()->where('email', 'ada@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('Ada Lovelace', $user->name);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_it_rejects_duplicate_emails_and_prompts_again(): void
    {
        User::factory()->create(['email' => 'ada@example.com']);

        $this->artisan('user:create')
            ->expectsQuestion('Name', 'Ada Second')
            ->expectsQuestion('Email', 'ada@example.com')
            ->expectsOutput('The email has already been taken.')
            ->expectsQuestion('Email', 'ada.two@example.com')
            ->expectsQuestion('Password', 'topsecret')
            ->expectsOutput('User created: ada.two@example.com')
            ->assertExitCode(SymfonyCommand::SUCCESS);

        $this->assertSame(2, User::query()->count());

        $newUser = User::query()->where('email', 'ada.two@example.com')->first();

        $this->assertNotNull($newUser);
        $this->assertTrue(Hash::check('topsecret', $newUser->password));
    }
}
