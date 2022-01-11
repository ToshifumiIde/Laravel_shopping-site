<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;//ソフトデリート（論理削除）
use App\Models\Shop;
use App\Models\Image;

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

    // 以下、Modelのリレーション(hasOne()：1対1、hasMany()：)

    /**
     * Ownerに関連しているshopの取得(1対1の関係)
     * 逆の関係もShopModelに記載が必要、Shop.phpではbelongsTo()メソッドを使用
     */
    public function shop(){
        return $this->hasOne(Shop::class);
    }
    /**
     * Ownerに関連しているimageの取得(1対多)
     * 逆の関係もImage.phpに記載が必要、Image.phpでは
     */
    public function image(){
        return $this->hasMany(Image::class);
    }
}
