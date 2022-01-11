<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner; //外部キー使用に伴う紐付けで使用

class Shop extends Model {
    use HasFactory;
    /**
     * ホワイトリストとして指定可能、$fillableに指定した配列の値のみcreate(),update(),fill()が可能
     */
    protected $fillable = [
        "owner_id",
        "name",
        "information",
        "filename",
        "is_selling",
    ];

    public function owner() {
        return $this->belongsTo(Owner::class);
    }
}
