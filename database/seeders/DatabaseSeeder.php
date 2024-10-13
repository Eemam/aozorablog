<?php

namespace Database\Seeders;

use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Eemam',
            'username' => 'eemams',
            'email' => 'eemam.phoenix@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('minato97'),
            'remember_token' => Str::random(10),
        ]);

        Post::factory(100)->recycle(User::factory(4)->create())->create();
        Comment::factory(100)->create();
    }
}
