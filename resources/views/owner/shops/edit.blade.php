<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-500 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <form method="post" action="{{route('owner.shops.update' , ['shop'=> $shop->id])}}" enctype="multipart/form-data">
                        @csrf
                                <div class="-m-2">
                                    <div class="p-2 w-1/2 mx-auto">
                                        <div class="relative">
                                            <label for="name" class="leading-7 text-sm text-gray-600">店名　※必須</label>
                                            <input
                                                type="text"
                                                id="name"
                                                name="name"
                                                placeholder="Name"
                                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                                required
                                                placeholder="例）山田 太郎"
                                                value="{{$shop->name}}"
                                            >
                                        </div>
                                    </div>
                                    <div class="p-2 w-1/2 mx-auto">
                                        <div class="relative">
                                            <label for="information" class="leading-7 text-sm text-gray-600">店舗情報　※必須</label>
                                            <textarea
                                                id="information"
                                                name="information"
                                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                                required
                                                rows="10"
                                                placeholder="例）関東支店"
                                            >{{$shop->information}}</textarea>
                                        </div>
                                    </div>
                                    <div class="p-2 w-1/2 mx-auto">
                                        <div class="relative">
                                            <div class="w-32">
                                                <x-shop-thumbnail :filename="$shop->filename"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2 w-1/2 mx-auto">
                                        <div class="relative">
                                            <label for="image" class="leading-7 text-sm text-gray-600">画像</label>
                                            <input
                                                type="file"
                                                accept="image/png,image/jpg,image/jpeg"
                                                id="image"
                                                name="image"
                                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                                            >
                                        </div>
                                    </div>
                                    <div class="p-2 w-1/2 mx-auto">
                                        <div class="relative flex justify-around">
                                            <div><input type="radio" name="is_selling" value="1" class="mr-2" @if($shop->is_selling === 1)checked @endif>販売中</div>
                                            <div><input type="radio" name="is_selling" value="0" class="mr-2" @if($shop->is_selling === 0)checked @endif>停止中</div>
                                        </div>
                                    </div>
                                    <div class="p-2 w-full flex justify-around mt-4">
                                        <button type="button" onClick="location.href='{{route("owner.shops.index")}}'" class="text-white bg-gray-300 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">
                                            戻る
                                        </button>
                                        <button type="submit"class="text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">
                                            更新する
                                        </button>
                                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
