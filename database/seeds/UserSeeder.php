<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Davide Vinciguerra';
        $user->email = 'davidevinci@gmail.com';
        $user->password = bcrypt('password');
        $user->save();
    }
}
