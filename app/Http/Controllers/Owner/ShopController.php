<?php

namespace App\Http\Controllers\Owner;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\SUpport\Facades\Storage;

class ShopController extends Controller {
    // コントローラー側でも認証確認
    // クラスからオブジェクトがnewによって生成される時に、自動的に呼び出されるメソッドが__construct(){}メソッド
    // コントローラーの__construct(){}メソッド内でミドルウェアを指定可能
    // 中で$this->middleware("auth:owners")で指定可能
    public function __construct() {
        // ユーザー認証が
        $this->middleware("auth:owners");
        $this->middleware(function ($request, $next) {
            // まずは取得情報を確認
            // dd($request->route()->parameter("shop")); //文字列でid取得
            // dd(Auth::id()); //数字でid取得
            $id = $request->route()->parameter("shop"); //shopのid取得
            if (!is_null($id)) {
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId       = (int)$shopsOwnerId; //キャスト 文字列→数字に変換
                $ownerId      = Auth::id();
                if ($shopId !== $ownerId) { //もしshopIdとownerIdが異なっている場合
                    abort(404); //404画面表示
                }
            }
            return $next($request);
        });
    }
    public function index() {
        //ログインしているownerのIDを取得
        // $ownerId = Auth::id();
        // Shopモデルを使用して、取得したownerIdに該当するshopの情報を取得
        // $shops   = Shop::where("owner_id", $ownerId)->get();
        // 以上の内容は下記にまとめることも可能
        $shops = Shop::where("owner_id", Auth::id())->get();

        return view("owner.shops.index", compact("shops"));
    }
    public function edit($id) {
        // 何も認証系での処理を実行していない状況で、どのIDが取得されるか確認
        // dd(Shop::findOrFail($id));
        // 現状だと、URLにidを下手打ちすると他のownerのshop情報が取得できてしまう
        // ミドルウェアを使用して__construct()内にmiddleware(function($request , $next){})でshopの認証を実行
        $shop = Shop::findOrFail($id);
        return view("owner.shops.edit", compact("shop"));
    }
    public function update(Request $request, $id) {
        // Laravel側でリサイズしないパターン（ユーザーにリサイズしてもらう場合）
        $imageFile = $request->image;
        // 取得したimageFileのnull判定と、念の為アップロードできているか確認を実行
        if (!is_null($imageFile) && $imageFile->isValid()) {
            //アップロードできている場合、imageFileをstorage/app/public/shopsに格納
            Storage::putFile("public/shops", $imageFile);
        }
        return redirect()->route("owner.shops.index");
    }
}
