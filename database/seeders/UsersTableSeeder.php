<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'telp'           => '081',
                'tanggal_lahir'  => '1998-06-27',
                'jenis_kelamin'  => 'Laki-laki',
                'alamat'         => 'pppp',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
            [
                'id'             => 2,
                'name'           => 'Admin2',
                'telp'           => '081',
                'tanggal_lahir'  => '1977-06-27',
                'jenis_kelamin'  => 'Laki-laki',
                'alamat'         => 'pppp',
                'email'          => 'admin2@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
            [
                'id'             => 3,
                'name'           => 'Admin3',
                'telp'           => '081',
                'tanggal_lahir'  => '2010-06-27',
                'jenis_kelamin'  => 'Laki-laki',
                'alamat'         => 'pppp',
                'email'          => 'admin3@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
            [
                'id'             => 4,
                'name'           => 'Wijaya',
                'telp'           => '081',
                'tanggal_lahir'  => '2019-06-27',
                'jenis_kelamin'  => 'Laki-laki',
                'alamat'         => 'pppp',
                'email'          => 'admin4@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'created_at'     => '2019-09-19 12:08:28',
                'updated_at'     => '2019-09-19 12:08:28',
            ],
        ];

        User::insert($users);
    }
}
