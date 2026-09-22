<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper">
        <h2 class="title">ホテル情報編集 - 確認画面</h2>
        <p style="color: var(--text-muted); margin-bottom: 20px; font-size: 14px;">以下の内容で更新します。よろしければ「更新」ボタンを押してください。</p>

        <div class="admin-card" style="max-width: 600px;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                <tbody>
                    <tr>
                        <th style="padding: 12px 16px; width: 30%; text-align: left; background: var(--border-light); font-weight: 600; font-size: 14px; border-bottom: 1px solid var(--border-color); border-radius: var(--radius-sm) 0 0 0;">ホテル名</th>
                        <td style="padding: 12px 16px; font-size: 14px; border-bottom: 1px solid var(--border-color);">{{ $hotel_name }}</td>
                    </tr>
                    <tr>
                        <th style="padding: 12px 16px; text-align: left; background: var(--border-light); font-weight: 600; font-size: 14px; border-bottom: 1px solid var(--border-color);">都道府県</th>
                        <td style="padding: 12px 16px; font-size: 14px; border-bottom: 1px solid var(--border-color);">{{ $prefecture->prefecture_name }}</td>
                    </tr>
                    <tr>
                        <th style="padding: 12px 16px; text-align: left; background: var(--border-light); font-weight: 600; font-size: 14px; border-bottom: 1px solid var(--border-color); border-radius: 0 0 0 var(--radius-sm);">ホテル画像</th>
                        <td style="padding: 12px 16px; border-bottom: 1px solid var(--border-color);">
                            @if (!empty($new_file_path))
                                <img src="/assets/img/{{ $new_file_path }}" alt="New Image" style="max-width: 200px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                                <p style="font-size: 12px; color: var(--success); font-weight: 600; margin: 6px 0 0 0;">（新しくアップロードされた画像）</p>
                            @elseif (!empty($current_file_path))
                                <img src="/assets/img/{{ $current_file_path }}" alt="Current Image" style="max-width: 200px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                                <p style="font-size: 12px; color: var(--text-muted); margin: 6px 0 0 0;">（既存の画像）</p>
                            @else
                                <span style="color: var(--text-muted); font-size: 14px;">画像なし</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <form action="{{ route('adminHotelEditProcess') }}" method="post" style="display: flex; align-items: center; gap: 16px;">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                <input type="hidden" name="hotel_name" value="{{ $hotel_name }}">
                <input type="hidden" name="prefecture_id" value="{{ $prefecture_id }}">
                @if (!empty($new_file_path))
                    <input type="hidden" name="file_path" value="{{ $new_file_path }}">
                @endif
                <input type="hidden" name="search_hotel_name" value="{{ $search_hotel_name ?? '' }}">
                <input type="hidden" name="search_prefecture_id" value="{{ $search_prefecture_id ?? '' }}">
                <input type="hidden" name="search_page" value="{{ $search_page ?? '' }}">

                <button type="submit" name="action" value="update" class="btn btn-primary">更新</button>
                <button type="submit" name="action" value="back" class="btn btn-secondary">戻る</button>
            </form>
        </div>
    </div>
@endsection
