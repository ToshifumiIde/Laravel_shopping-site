<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId("owner_id")
                ->constrained()
                ->onUpdate("cascade")
                ->onDelete("cascade");
            // 外部キー設定する場合->foreignId("モデル名_キー")->constrained()が必要
            // 外部キー制約がある状態で合わせて削除する場合
            // ->onUpdate("cascade")->onDelete("cascade");が必要
            $table->string("name");
            $table->text("information");
            $table->string("filename");
            $table->boolean("is_selling");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shops');
    }
}
