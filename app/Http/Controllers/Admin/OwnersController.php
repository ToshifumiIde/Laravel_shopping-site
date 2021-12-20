<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner; //Eloquent エロクアント
use App\Models\Shop; //Eloquent エロクアント
use Illuminate\Support\Facades\DB; //QueryBuilder クエリビルダ
use Carbon\Carbon; //日付関連のライブラリはCarbonで対応可能
use Illuminate\Support\Facades\Hash; //パスワードハッシュ化に必要

use Throwable; //例外処理を投げる場合のPHP7の機能
use Illuminate\Support\Facades\Log; //例外処理のログ



class OwnersController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // コントローラー側でも認証確認する
    // コントローラーの__construct(){}メソッド内でミドルウェアを指定可能
    // 中で$this->middleware("auth:admin")で指定可能
    public function __construct() {
        $this->middleware("auth:admin");
    }

    public function index() {
        // Carbonのライブラリを用いて日付を取得する
        // $date_now = Carbon::now();
        // $date_parse = Carbon::parse(now());
        // echo $date_now->year;
        // echo $date_parse;
        // $e_all = Owner::all(); //Ownerモデルから全データを取得（エロクアント）
        // $q_get = DB::table("owners")->select("name", "created_at")->get();
        // $q_first = DB::table("owners")->select("name")->first();
        // $c_test = collect(["name" => "テスト"]);
        // var_dump($q_first);
        // dd($e_all, $q_get, $q_first, $c_test);

        // エロクアントクラスのスコープ定義演算子からselect()->get()を使用して、name,email,created_atを取得
        $owners = Owner::select("id", "name", "email", "created_at")
            ->paginate(3); //get()メソッドからpaginate()メソッドに切り替え

        return view("admin.owners.index", compact("owners"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view("admin.owners.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * Request $request = リクエストクラスの$requestはメソッドインジェクションと呼ばれる
     * メソッドインジェクションを使わない場合
     * $request = new Request();インスタンスを生成し
     * $request->all();インスタンスを生成してからallメソッドを呼ぶ手順が必要
     * formで渡ってきた値を使用する場合、actionの仮引数にメソッドインジェクションである
     * Request $request を記述するケースがほとんど
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:owners',
            'password' => 'required|string|confirmed|min:8',
            // conformed：2つのformがあってるかどうかまとめて確認
        ]);

        // 例外処理の記入
        try {
            // トランザクション（複数の処理をまとめた）処理
            // クロージャー（無名関数、コールバック関数）に引数を渡す場合はuse()内に引数を渡す必要がある
            DB::transaction(function () use ($request) {
                // バリデーションがOKだったら、Ownerテーブルにデータを登録していく
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    //パスワードはHash::make()で暗号化
                ]);
                Shop::create([
                    "owner_id" => $owner->id,
                    "name" => "店名を入力してください",
                    "information" => "",
                    "filename" => "",
                    "is_selling" => true,
                ]);
            }, 2);
        } catch (Throwable $e) {
            Log::error($e);
            throw $e;
        }



        // 登録が無事成功した場合のsessionメッセージを書いていく（今回は->with()メソッドを使用）
        return redirect()
            ->route("admin.owners.index")
            ->with([
                "message" => "オーナー登録を実施しました。",
                "status" => "info",
            ]);
        // with()メソッド一緒に渡す値は連想配列で格納可能
        // messageと合わせて、表示部分のstatusも渡したいので、連想配列で指定
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
        //findOrFail()メソッドにより、渡ってきた$idが存在する場合は変数に格納
        // 存在しない場合は404 not foundエラーを返却させる
        $owner = Owner::findOrFail($id);
        // dd($owner); Owner::findOrFail($id)の確認でdd($owner);実施
        return view("admin.owners.edit", compact("owner"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //$ownerをEloquentで生成
        // findOrFailメソッドを用いて、存在しない場合404 not foundを返却
        // 該当する主キー(id)の値が渡されたModelのインスタンスが返却される
        $owner = Owner::findOrFail($id); //Ownerモデルで$idを指定した情報を$ownerに格納可能
        // ユーザーから取得した情報で$ownerの情報を更新
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        // save処理を実施（Eloquentのsave()メソッド使用）
        $owner->save();
        // 更新処理が完了したらリダイレクト
        return redirect()
            ->route("admin.owners.index")
            ->with([
                "message" => "オーナー情報を更新しました",
                "status"  => "info",
            ]);
        // ->with()メソッドを使うと、sessionメッセージを渡すことが可能
        // messageと合わせて、表示箇所の背景色を変更するためのstatusを同時に渡す
        // リソースコントローラ（php artisan make:controller OwnersController --resources）で作成した
        // updateはmethod="post"に対応していない、PUT/PATCHのいずれか
        // したがって今回は、
        // edit.blade.phpのformタグの下に擬似フォームメソッドの@method("PUT")を追加する必要がある
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        // 出力確認
        // dd("削除処理");
        // テーブルに対し、論理削除を実施する
        // findOrFail()メソッドを使用、
        // 存在する場合主キー(値)の値が渡されたOwnerModelのインスタンスが返却される
        // Eloquentの->delete()をチェーンメソッドで繋いで削除実施
        Owner::findOrFail($id)->delete();
        return redirect()
            ->route("admin.owners.index")
            ->with([
                "message" => "指定したidのオーナー情報を削除しました。",
                "status" => "alert",
            ]);
        // with()メソッドでsessionを渡す
        // 今回はmessageの背景色も変更したいので、連想配列で指定する
        // view側での->with()で指定したsessionの取得方法は、session(”キー名”)で取得可能
    }

    // 以下、サブスク解除や年会費未払いなどでのソフトデリート（論理削除）を実施する
    public function expiredOwnerIndex() {
        $expiredOwners = Owner::onlyTrashed()->get(); //onlyTrashed()で期限切れのユーザーのみ取得可能
        return view("admin.expired-owners", compact("expiredOwners"));
    }
    public function expiredOwnerDestroy($id) {
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route("admin.expired-owners.index");
    }
}
