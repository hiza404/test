<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper">
        <h2 class="title">ホテル追加</h2>

        <div class="admin-card" style="max-width: 600px;">
            <form action="{{ route('adminHotelCreateProcess') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label for="hotel_name" style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 6px;">
                        ホテル名 <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" id="hotel_name" name="hotel_name" value="{{ old('hotel_name') }}" placeholder="ホテル名を入力してください" style="width: 100%;">
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
                            <option value="{{ $prefecture->prefecture_id }}" {{ (string)old('prefecture_id') === (string)$prefecture->prefecture_id ? 'selected' : '' }}>
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
                    <input type="file" id="hotel_image" name="hotel_image" accept="image/*" style="width: 100%; padding: 6px;">
                    @error('hotel_image')
                        <p style="color: var(--danger); font-size: 13px; margin: 6px 0 0 0;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; gap: 16px;">
                    <button type="submit" class="btn btn-primary">登録する</button>
                    <a href="{{ route('adminHotelSearchPage') }}" class="btn btn-secondary">戻る</a>
                </div>
            </form>
        </div>
    </div>
@endsection
