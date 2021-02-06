<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'Meysam Mahmoudi',
            'email' => 'meysam0011212@gmail.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password' => bcrypt('go2tr123456'),
            'api_token' => Str::random(80),
        ]);
    }
}
