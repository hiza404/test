<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
    @vite('resources/scss/admin/result.scss')
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">検索画面</h2>

        <div class="search-hotel-name">
            <form action="{{ route('adminHotelSearchResult') }}" method="post">
                @csrf
                <input type="text" name="hotel_name" value="{{ old('hotel_name', $hotel_name ?? '') }}" placeholder="ホテル名を入力...">
                <select name="prefecture_id">
                    <option value="">都道府県を選択</option>
                    @if (!empty($prefectures))
                        @foreach ($prefectures as $prefecture)
                            <option value="{{ $prefecture->prefecture_id }}" {{ (string)old('prefecture_id', $prefecture_id ?? '') === (string)$prefecture->prefecture_id ? 'selected' : '' }}>
                                {{ $prefecture->prefecture_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <button type="submit">検索</button>
                <a href="{{ route('adminHotelSearchPage') }}" class="btn-reset" title="検索条件をクリアして再読み込み">
                    <span>🔄</span> リセット
                </a>
            </form>

            @if (!empty($errorMessage))
                <p class="search-error">{{ $errorMessage }}</p>
            @elseif ($errors->has('search_error'))
                <p class="search-error">{{ $errors->first('search_error') }}</p>
            @endif
        </div>

        @yield('search_results')
    </div>
@endsection