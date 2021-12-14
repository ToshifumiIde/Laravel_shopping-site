<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner; //Eloquent エロクアント
use Illuminate\Support\Facades\DB; //QueryBuilder クエリビルダ
use Carbon\Carbon; //日付関連のライブラリはCarbonで対応可能
use Illuminate\Support\Facades\Hash;

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
        $owners = Owner::select("id", "name", "email", "created_at")->get();

        return view("admin.owner.index", compact("owners"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view("admin.owner.create");
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
            'password' => 'required|string|confirmed|min:8', // conformed：2つのformがあってるかどうかまとめて確認
        ]);
        // バリデーションがOKだったら、Ownerテーブルにデータを登録していく
        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            //パスワードはHash::make()で暗号化
        ]);

        // 登録が無事成功した場合のsessionメッセージを書いていく（今回は->with()メソッドを使用）
        return redirect()
            ->route("admin.owners.index")
            ->with("message", "オーナー登録を実施しました。");
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
        return view("admin.owner.edit", compact("owner"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //$ownerをEloquentで生成、findOrFailメソッドを用いて、存在しない場合404 not foundを返却
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
            ->with("message", "オーナー情報を更新しました");
        // リソースコントローラ（php artisan make:controller OwnersController --resources）で作成した
        // updateはmethod="post"に対応していない、PUT/PATCHのいずれか
        // したがって、edit.blade.phpのformタグの下に@method("PUT")を今回は追加する必要がある
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
