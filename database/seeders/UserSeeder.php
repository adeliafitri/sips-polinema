<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $auth = User::where('username', 'admin@gmail.com')->first()->id;

        DB::table('admin')->insert([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'telp' => '081456780923',
            'id_auth' => 1,
        ]);
    }
}
