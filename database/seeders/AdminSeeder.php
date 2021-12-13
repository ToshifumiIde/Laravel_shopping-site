<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// DBファサードとHashファサードの読み込み
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seederを用いてダミーデータを作成する
        // 作成したSeederはDatabaseSeeder.phpで呼び出す必要がある
        DB::table("admins")->insert([
            "name" => "test",
            "email" => "test@test.com",
            "password" => Hash::make("password1123"),
            "created_at" => "2021/01/01 09:00:00",
        ]);

    }
}
