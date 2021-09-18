<x-tests.app>
    <x-slot name="header">
        ヘッダー2
    </x-slot>
    コンポーネントテスト2
    <x-tests.card
        title="タイトル2"
        content="本文2"
        :message="$message"
    />
    <div class="mb-4"></div>
    <x-test-class-base classBaseMessage="クラスベースのメッセージです" />
    <div class="mb-4"></div>
    <x-test-class-base classBaseMessage="クラスベースのメッセージです" defaultMessage="初期値から変更しています"/>
</x-tests.app>
