<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('primary_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("sort_order");
            $table->timestamps();
        });
        // upメソッドの中で複数のスキームの作成が可能
        Schema::create('secondary_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId("primary_category_id")->constrained();
            $table->integer("sort_order");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 削除する時のfunction
     * @return void
     */
    public function down()
    {
        // 外部キー制約をかけているため、先にsecondary_categoriesを削除しないと外部キーエラーが発生する
        Schema::dropIfExists('secondary_categories');
        Schema::dropIfExists('primary_categories');
    }
}
