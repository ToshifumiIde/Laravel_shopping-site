<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     * 認証されているユーザーが使えるかどうか、基本的にtrueに設定
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * rulesにはバリデーションルールを設定する必要がある
     * 今回は画像のアップロードに関するvalidationのため、その設定を行う
     * 画像、拡張子（jpg,jpeg,png）、最大2MB
     * バリデーションにエラーが発生した場合のメッセージは
     * function messages(){}を作成すればよい（後述）
     * @return array
     */
    public function rules() {
        return [
            //
            "image" => "image|mimes:jpg,jpeg,png|max:2048",
        ];
    }

    /**
     * バリデーションエラー時のメッセージはmessage(){}で生成可能
     * @return array
     */
    public function messages() {
        return [
            "image" => "画像ファイルを指定してください。",
            "mimes" => "指定された拡張子(jpg/jpeg/png)ではありません。",
            "max"   => "ファイルサイズは2MB以下にしてください。",
        ];
    }
}
