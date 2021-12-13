<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner; //Eloquent エロクアント
use Illuminate\Support\Facades\DB; //QueryBuilder クエリビルダ
use Carbon\Carbon; //日付関連のライブラリはCarbonで対応可能

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
        $owners = Owner::select("name", "email", "created_at")->get();

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
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
