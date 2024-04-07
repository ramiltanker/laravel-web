<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
            [
                'name'      =>  'Admin',
                'email'     =>  'admin@gmail.com',
                'password'  =>  Hash::make('12345678'),
                'role'   =>  'moderator'
            ]
        );
        User::create(
            [
                'name'      =>  'CommonUser',
                'email'     =>  'example@mail.ru',
                'password'  =>  Hash::make('12345678'),
                'role'   =>  'reader'
            ]
        );
    }
}