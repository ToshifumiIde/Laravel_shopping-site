<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class Image extends Model {
    use HasFactory;
    /**
     * $fillableを使用するとホワイトリストとして登録可能、create(),update(),fill()が可能となる
     * 今回の場合、owner_idとfilenameのみが対象となる
     *
     */
    protected $fillable = [
        "owner_id",
        "filename",
    ];

    // Modelの依存関係を記載
    // 1対多(逆)の所属を記載する場合、$this->belongsTo()メソッドを使用し、引数にModel名::classで指定
    public function owner() {
        return $this->belongsTo(Owner::class);
    }
}
