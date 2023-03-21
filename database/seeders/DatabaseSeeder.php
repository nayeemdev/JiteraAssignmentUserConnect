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
        // this will create 70 users because of follower factory
        User::factory()
            ->count(10)
            ->has(Address::factory())
            ->has(Company::factory())
            ->has(Follower::factory()->count(3), 'followers')
            ->create();
    }
}
