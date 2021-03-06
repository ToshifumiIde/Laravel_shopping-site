<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-500 leading-tight">
            期限切れオーナー一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="text-gray-600 body-font">
                        <div class="container px-5 mx-auto">
                            {{-- controllerから->withで渡されたsession("status")をstatusに格納 --}}
                            <x-flash-message status="{{session('status')}}" />
                            <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                                <thead>
                                <tr>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">お名前</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メールアドレス</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">期限が切れた日</th>
                                    <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiredOwners as $owner)
                              <tr>
                                <td class="px-4 py-3">{{$owner->name}}</td>
                                <td class="px-4 py-3">{{$owner->email}}</td>
                                <td class="px-4 py-3">{{$owner->deleted_at->diffForHumans()}}</td>
                                <form
                                    action="{{route('admin.expired-owners.destroy',['owner'=>$owner->id])}}"
                                    method="post"
                                    id="delete_{{$owner->id}}"
                                    >
                                    @csrf
                                    <td>
                                        <a
                                        herf="#"
                                        data-id="{{$owner->id}}"
                                        class="text-white bg-red-400 border-0 py-2 px-4 focus:outline-none hover:bg-red-500 rounded cursor-pointer"
                                        onclick="deletePost(this)"
                                        >
                                        完全に削除
                                    </a>
                                </td>
                                </form>
                            </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                    </section>

                    {{-- エロクアント
                    @foreach($e_all as $e_owner)
                        {{$e_owner->name}}
                        {{$e_owner->created_at->diffForHumans()}}
                        エロクアントの場合created_atのタイムスタンプがカーボンインスタンスなので、diffForHumans()メソッドがそのまま繋げられる --}}
                    {{-- @endforeach --}}
                    {{-- <br> --}}
                    {{--クエリビルダ
                    @foreach ($q_get as $q_owner)
                        {{$q_owner->name}}
                        クエリビルダでCarbonを使用したい場合、Carbonのparseを通す必要がある
                        {{Carbon\Carbon::parse($q_owner->created_at)->diffForHumans()}}
                    @endforeach --}}
                </div>
            </div>
        </div>
    </div>
    <script>
        function deletePost(e){
            "use strict";
            if(confirm("本当に削除してもよろしいですか")){
                document.getElementById("delete_" + e.dataset.id).submit();
            }
        }
    </script>
</x-app-layout>
