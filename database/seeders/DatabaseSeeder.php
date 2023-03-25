<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Address;
use App\Models\Company;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create test user for login
        User::factory()
            ->has(Address::factory())
            ->has(Company::factory())
            ->create([
                'name' => 'Md Nayeem Hossain',
                'username' => 'nayeemdev',
                'email' => 'nayeemdev@yahoo.com',
                'password' => bcrypt('Secret123@'),
                'phone' => '121312312',
                'website' => 'https://nayeemdev.com',
            ]);

        // create users
        User::factory()
            ->count(100)
            ->has(Address::factory())
            ->has(Company::factory())
            ->create();

        // create followers
        $users = User::limit(50)->get();
        $users->each(function ($user) use ($users) {
            $users->each(function ($user2) use ($user) {
                if ($user->id !== $user2->id) {
                    Follower::create([
                        'user_id' => $user->id,
                        'follower_id' => $user2->id,
                    ]);
                }
            });
        });
    }
}
