<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    // 試験的にサービスプロバイダを作成（EncryptionServiceProviderを参考に）
    public function showServiceProviderTest(){
        //app()->make()でサービスの利用が可能（インスタンスの生成）
        $encrypt = app()->make('encrypter');
        //インスタンスを生成した$encryptに->encrypt()で文字列を入れ、暗号化する
        $password = $encrypt->encrypt('password');

        // 自作のプロバイダの読み込み
        $sample = app()->make('serviceProviderTest');

        // decrypt()は暗号化されたものを元に戻すメソッド
        // dd()でブラウザに表示
        dd($sample ,$password , $encrypt->decrypt($password));
    }

    // 試験的にライフサイクルを作成
    public function showServiceContainerTest(){
        app()->bind("lifeCycleTest" , function(){
            return "ライフサイクルテスト";
        }); //bind()メソッドでコンテナに格納
        $test = app()->make("lifeCycleTest");//コンテナから引き出す

        // サービスコンテナなし（それぞれのクラスをインスタンス化すればOK）
        // $message = new Message();
        // $sample = new Sample($message);
        // $sample->run();

        // サービスコンテナありのパターン（自動的に依存関係を解決する）
        app()->bind("sample" , Sample::class); //Sampleクラスの紐付け
        $sample = app()->make("sample"); //sampleの呼び出しを変数に格納
        $sample->run();

        dd($test , app());
    }
}

class Sample
{
    public $message;
    public function __construct(Message $message){
        $this->message = $message;
    }
    public function run(){
        $this->message->send();
    }
}

class Message
{
    public function send(){
        echo("メッセージ表示");
    }
}

