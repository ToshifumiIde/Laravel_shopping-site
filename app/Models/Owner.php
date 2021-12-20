<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;//ソフトデリート（論理削除）
use App\Models\Shop;

class Owner extends Authenticatable
{
    use HasFactory , SoftDeletes;//(SoftDeletes(論理削除追加))

        /**
     * The attributes that are mass assignable.
     * protected $fillableはホワイトリストとして登録可能
     * 指定したカラムに対してのみcreate()やupdate()、fillが可能になる
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function shop(){
        return $this->hasOne(Shop::class);
    }
}
