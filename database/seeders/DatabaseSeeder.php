<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('users')->insert([
            'name' => 'Admin guy',
            'email' => 'admin@silc.com',
            'password' => Hash::make('password'),
            'investor_type' => 'Individual Trustee',
            'contact_no' => '00000000000',
            'address' => '',
        ]);

        DB::table('users')->insert([
            'name' => 'Asset manager guy',
            'email' => 'am@silc.com',
            'password' => Hash::make('password'),
            'investor_type' => 'Individual Trustee',
            'contact_no' => '00000000000',
            'address' => '',
        ]);

        DB::table('users')->insert([
            'name' => 'Investor guy',
            'email' => 'investor@silc.com',
            'password' => Hash::make('password'),
            'investor_type' => 'Individual Trustee',
            'contact_no' => '00000000000',
            'address' => '',
        ]);

        DB::table('users')->insert([
            'name' => 'Basic user',
            'email' => 'basic@silc.com',
            'password' => Hash::make('password'),
            'investor_type' => 'Individual Trustee',
            'contact_no' => '00000000000',
            'address' => '',
        ]);

        DB::table('roles')->insert([
            'user_id' => 1,
            'role' => 'admin',
        ]);

        DB::table('roles')->insert([
            'user_id' => 2,
            'role' => 'AM',
        ]);

        DB::table('roles')->insert([
            'user_id' => 3,
            'role' => 'investor',
        ]);

        DB::table('roles')->insert([
            'user_id' => 4,
            'role' => 'basic',
        ]);
    }
}