<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = "Jean-Luc DOH";
        $user->email = "30506c0ef4-9ef119+1@inbox.mailtrap.io";
        $user->password = Hash::make("edyrodal1238");
        $user->save();
    }
}
