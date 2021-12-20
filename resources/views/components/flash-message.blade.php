@props(["status" => "info"]){{-- propsで初期値をinfoに設定 --}}
@php
    // controller内のwith()メソッドで指定した"status"=>"alert"はview側でsession('status')で取得可能
    if(session("status") ==="info"){$bgColor = "bg-blue-300";}
    if(session("status") ==="alert"){$bgColor = "bg-red-500";}
@endphp
@if(session("message"))
    <div class="{{$bgColor}} w-1/2 mx-auto p-2 text-white">
        {{session("message")}}
    </div>
@endif
