<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dump = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt(123456),
            ],
            [
                'name' => 'Member',
                'email' => 'member@example.com',
                'password' => bcrypt(123456),
            ],
        ];
        User::insert($dump);
    }
}
