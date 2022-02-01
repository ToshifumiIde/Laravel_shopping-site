<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
