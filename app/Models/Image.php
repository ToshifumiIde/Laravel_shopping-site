<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
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
}
