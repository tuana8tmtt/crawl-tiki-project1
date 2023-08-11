<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'name'     => 'Tran Tuan',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'is_admin' => 1,
        ]);
    }
}
