<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper">
        <h2 class="title">ホテル情報編集</h2>

        <div class="admin-card" style="max-width: 600px;">
            <form action="{{ route('adminHotelEditConfirm') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                <input type="hidden" name="search_hotel_name" value="{{ old('search_hotel_name', $searchHotelName ?? '') }}">
                <input type="hidden" name="search_prefecture_id" value="{{ old('search_prefecture_id', $searchPrefectureId ?? '') }}">
                <input type="hidden" name="search_page" value="{{ old('search_page', $searchPage ?? '') }}">

                <div style="margin-bottom: 20px;">
                    <label for="hotel_name" style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px;">
                        ホテル名 <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" id="hotel_name" name="hotel_name" value="{{ old('hotel_name', $hotel->hotel_name) }}" placeholder="ホテル名" style="width: 100%;">
                    @error('hotel_name')
                        <p style="color: var(--danger); font-size: 13px; margin: 6px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="prefecture_id" style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px;">
                        都道府県 <span style="color: var(--danger);">*</span>
                    </label>
                    <select id="prefecture_id" name="prefecture_id" style="width: 100%;">
                        <option value="">都道府県を選択してください</option>
                        @foreach ($prefectures as $prefecture)
                            <option value="{{ $prefecture->prefecture_id }}" {{ (string)old('prefecture_id', $hotel->prefecture_id) === (string)$prefecture->prefecture_id ? 'selected' : '' }}>
                                {{ $prefecture->prefecture_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('prefecture_id')
                        <p style="color: var(--danger); font-size: 13px; margin: 6px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 28px;">
                    <label for="hotel_image" style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px;">
                        ホテル画像
                    </label>
                    @if (!empty($hotel->file_path))
                        <div style="margin-bottom: 12px;">
                            <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 6px 0;">現在の画像:</p>
                            <img src="/assets/img/{{ $hotel->file_path }}" alt="{{ $hotel->hotel_name }}" style="max-width: 180px; height: auto; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                        </div>
                    @endif
                    <p style="font-size: 12px; color: var(--text-muted); margin: 0 0 6px 0;">変更する場合のみ新しい画像を選択してください:</p>
                    <input type="file" id="hotel_image" name="hotel_image" accept="image/*" style="width: 100%; padding: 6px;">
                    @error('hotel_image')
                        <p style="color: var(--danger); font-size: 13px; margin: 6px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; gap: 16px;">
                    <button type="submit" class="btn btn-primary">確認画面へ</button>
                    @php
                        $backUrl = (!empty($searchHotelName) || !empty($searchPrefectureId))
                            ? route('adminHotelSearchResult', array_filter(['hotel_name' => $searchHotelName, 'prefecture_id' => $searchPrefectureId, 'page' => $searchPage]))
                            : route('adminHotelSearchPage', array_filter(['page' => $searchPage]));
                    @endphp
                    <a href="{{ $backUrl }}" class="btn btn-secondary">戻る</a>
                </div>
            </form>
        </div>
    </div>
@endsection
