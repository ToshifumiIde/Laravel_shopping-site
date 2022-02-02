<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        // \App\Models\User::factory(10)->create();
        // 個別に作成したSeederは$this->call()メソッドで呼び出す必要がある
        $this->call([
            AdminSeeder::class,
            OwnerSeeder::class,
            ShopSeeder::class,
            ImageSeeder::class,
            CategorySeeder::class,
        ]);
        // 上記作成したら、php artisan migrate:fresh --seedを実行
        // 呼び出し完了したら、php artisan db:seedコマンドで実装する必要あり
        //
    }
}
