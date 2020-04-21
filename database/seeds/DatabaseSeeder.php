<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('user')->insert([
            'firstname' => "SuperAdmin",
            'lastname' => "Constructo",
            'email' => "admin@gmail.com",
            'phone' => "09068002030",
            'address' => "Davao City",
            'password' => Hash::make('password'),
            'userType' => 1,
            'isVerified' => 1,
            'isblocked' => 0,
            // 'created_at' => Carbon::now(),
        ]);
    }
}
