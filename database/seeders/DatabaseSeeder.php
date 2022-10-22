<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wallet;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User 1
        $user = new User;
        $user->name = 'Jhon Doe';
        $user->email = 'doe@gmail.com';
        $user->password = Hash::make('123456');
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->currency = 'USD';
        $wallet->amount = 1000;
        $wallet->save();

        // User 2
        $user = new User;
        $user->name = 'Mike Henry';
        $user->email = 'henry@gmail.com';
        $user->password = Hash::make('123456');
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->currency = 'EUR';
        $wallet->amount = 1000;
        $wallet->save();

    }
}
