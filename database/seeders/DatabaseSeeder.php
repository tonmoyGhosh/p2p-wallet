<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
        $user= new User;
        $user->name = 'Jhon Doe';
        $user->email = 'doe@gmail.com';
        $user->password = Hash::make('123456');
        $user->currency = 'USD';
        $user->save();

        $user= new User;
        $user->name = 'Mike Henry';
        $user->email = 'henry@gmail.com';
        $user->password = Hash::make('123456');
        $user->currency = 'EUR';
        $user->save();

    }
}
