<div class="">
    @if (empty($filename))
    <img src="{{asset('images/no_image.jpg')}}" alt="画像はありません">
    @else
    <img src="{{asset('storage/shops/' . $filename)}}" alt="{{$filename}}の画像">
    @endif
</div>
