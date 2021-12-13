<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route; //Routeファサードの読み込み

class Authenticate extends Middleware {
    //RouteServiceProviderで設定したas('admin')などに当たる
    protected $user_route = "user.login";
    protected $owner_route = "owner.login";
    protected $admin_route = "admin.login";

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * もし認証されていなかった場合、リダイレクトをかける
     * 今回はここをuser、owner、adminの3パターンで分岐する
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            if (Route::is("owner.*")) {
                return route($this->owner_route);
            } else if (Route::is("admin.*")) {
                return route($this->admin_route);
            } else {
                return route($this->user_route);
            }
        }
    }
}
