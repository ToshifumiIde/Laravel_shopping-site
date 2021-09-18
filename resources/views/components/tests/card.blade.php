@props([
    "title" => "タイトル初期値です",
    "message" => "メッセージ初期値です",
    "content" => "本文初期値です",
])

<div class="border-2 shadow-mb w-1/4 p-2">
    <div class="">{{$title}}</div>
    <div class="">画像</div>
    <div class="">{{$content}}</div>
    <div class="">{{$message}}</div>
</div>
