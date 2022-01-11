<?php

namespace App\Http\Controllers\Owner;

use App\Models\Shop;
use App\Http\Controllers\Controller;
// php artisan make:request UploadRequestで生成したUploadRequest.phpの読み込み
use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\SUpport\Facades\Storage;
// 画像のリサイズ機能の追加（composer.jsonの"require"にInterventionが追加されていることを確認し、config/app.phpに2つ設定追加）
use InterventionImage;


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

        // PHPの情報を確認する関数：phpinfo();
        // phpinfo();

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

    // app/Http/Requests/UploadImageRequest.phpで生成したRequestの取得を行う
    // この場合、Request型をUploadImageRequestに変更する
    public function update(UploadImageRequest $request, $id) {
        // 1.Laravel側でリサイズしないパターン（ユーザーにリサイズしてもらう場合）
        // $imageFile = $request->image;
        // 取得したimageFileのnull判定と、念の為アップロードできているか確認を実行
        // if (!is_null($imageFile) && $imageFile->isValid()) {
        //    // アップロードできている場合、imageFileをstorage/app/public/shopsに格納
            // Storage::putFile("public/shops", $imageFile);
        // }
        // return redirect()->route("owner.shops.index");

        // 2.Laravel側でInterventionImageを使用してリサイズする場合
        $imageFile = $request->image;

        if(!is_null($imageFile) && $imageFile->isValid()){
            // ファイル名をランダムIDに変更し、ファイル拡張子を取得して結合
            $fileName        = uniqid(rand() . "_");         //ランダムIDを生成
            $extension       = $imageFile->extension();      //拡張子を取得
            $fileNameToStore = $fileName . "." . $extension; //名前の結合
            // ファイルリサイズ処理
            $resizedImage    = InterventionImage::make($imageFile)->resize(1920 , 1080)->encode();

            // 出力確認：dd()
            // dd($imageFile , $resizedImage);

            // Storageに保存(shops/と最後の/を忘れないこと)
            Storage::put("public/shops/" . $fileNameToStore , $resizedImage);
        }
        return redirect()->route("owner.shops.index");
    }
}
