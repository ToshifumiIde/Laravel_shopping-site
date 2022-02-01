<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller {
    // コントローラー側での認証処理
    // __construct(){}アクションはControllerの処理実行前に必ず実行されるアクション
    // ここに認証処理を加えることで、どのアクションを実行するにあたっても必ず認証処理が実行される

    public function __construct() {
        $this->middleware("auth:owners");
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter("image"); // imageのidを取得
            if (!is_null($id)) {
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $imageId       = (int)$imagesOwnerId;
                $ownerId       = Auth::id();
                // imageIdとAuth::id()が異なっている場合、404ページに飛ばす
                if ($imageId !== $ownerId) {
                    abort(404); //abort()で404ページを返却
                }
            }
            return $next($request);
        });
    }


    public function index() {
        // Imageの情報をModelから取得
        // where文で条件を指定、"owner_id"がAuth::id()に合致するものを取得
        // 順番はupdated_atが降順、paginate(20)で20件ごとにpaginationを実施
        $images = Image::where("owner_id", Auth::id())
            ->orderBy("updated_at", "desc")
            ->paginate(20);
        return view("owner.images.index", compact("images"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view("owner.images.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request) {
        //
        $imageFiles = $request->file("files");
        if (!is_null($imageFiles)) {
            foreach ($imageFiles as $imageFile) {
                $fileNameToStore = ImageService::upload($imageFile, "products");
                Image::create([
                    "owner_id" => Auth::id(),
                    "filename" => $fileNameToStore,
                ]);
            }
        }
        return redirect()
            ->route("owner.images.index")
            ->with(["message" => "画像登録を実施しました", "status" => "info",]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        // 何も認証系での処理を実行していない状況で、どのIDが取得されるか確認
        // dd(Image::findOrFail($id));
        // 現状だと、URLにidをベタ打ちすると他のownerのimage情報が取得できてしまう
        // ミドルウェアを使用して__construct()内にmiddleware(function($request , $next){})でshopの認証を実行
        $image = Image::findOrFail($id);
        return view("owner.images.edit", compact("image"));
    }

    public function update(Request $request, $id) {
        // バリデーション処理
        $request->validate([
            "title" => "string|max:50",
        ]);
        // 対象のidの画像を取得
        $image = Image::findOrFail($id);
        // ユーザーから入力されたtitleをimageのtitleに格納
        $image->title = $request->title;
        // 格納後、DBに登録
        $image->save();
        // 登録完了後、owner/images/index.blade.phpにリダイレクト
        return redirect()
            ->route("owner.images.index")
            ->with(["message" => "画像情報を更新しました。", "status" => "info"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        // データを削除する前に、
        // 指定したidのインスタンスを取得
        $image = Image::findOrFail($id);
        //
        $filePath = "public/products/"  . $image->filename;

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        // 出力確認
        // dd("削除処理");
        // テーブルに対し、論理削除を実施する
        // findOrFail()メソッドを使用、
        // 存在する場合主キー(値)の値が渡されたOwnerModelのインスタンスが返却される
        // Eloquentの->delete()をチェーンメソッドで繋いで、imageの削除実施
        Image::findOrFail($id)->delete();
        return redirect()
            ->route("owner.images.index")
            ->with([
                "message" => "画像を削除しました。",
                "status" => "alert",
            ]);
        // with()メソッドでsessionを渡す
        // 今回はmessageの背景色も変更したいので、連想配列で指定する
        // view側での->with()で指定したsessionの取得方法は、session(”キー名”)で取得可能

    }
}
