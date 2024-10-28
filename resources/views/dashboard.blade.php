<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table>
                        <tr>
                            <th>displayName</th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th>DID</th>
                            <td>{{ auth()->user()->did }}</td>
                        </tr>
                        <tr>
                            <th>handle</th>
                            <td>{{ auth()->user()->handle }}</td>
                        </tr>
                        <tr>
                            <th>avatar</th>
                            <td>{{ auth()->user()->avatar ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>issuer</th>
                            <td>{{ auth()->user()->issuer }}</td>
                        </tr>
                        <tr>
                            <th>refresh_token</th>
                            <td>{{ auth()->user()->refresh_token }}</td>
                        </tr>
                        <tr>
                            <th>session</th>
                            <td>@dump(session('bluesky_session'))</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('bsky.first') }}" method="POST">
                        @csrf
                        <x-secondary-button type="submit">最新の投稿を表示</x-secondary-button>
                    </form>

                    @isset($post)
                        <table>
                            <tr>
                                <th>text</th>
                                <td>{{ data_get($post, 'post.record.text', '') }}</td>
                            </tr>
                            <tr>
                                <th>createdAt</th>
                                <td>{{ data_get($post, 'post.record.createdAt', '') }}</td>
                            </tr>
                            <tr>
                                <th>indexedAt</th>
                                <td>{{ data_get($post, 'post.indexedAt', '') }}</td>
                            </tr>
                            <tr>
                                <th>author</th>
                                <td>{{ json_encode(data_get($post, 'post.author', [])) }}</td>
                            </tr>
                            <tr>
                                <th>embed</th>
                                <td>{{ json_encode(data_get($post, 'post.record.embed', [])) }}</td>
                            </tr>
                            <tr>
                                <th>facets</th>
                                <td>{{ json_encode(data_get($post, 'post.record.facets', [])) }}</td>
                            </tr>
                            <tr>
                                <th>post</th>
                                <td>@dump($post)</td>
                            </tr>
                        </table>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
