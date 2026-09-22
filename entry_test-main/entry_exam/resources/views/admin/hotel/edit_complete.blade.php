<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper">
        <h2 class="title">ホテル情報編集 - 完了</h2>

        <div class="admin-card" style="max-width: 600px; text-align: center; padding: 48px 24px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--success-light); color: var(--success); display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 20px;">
                ✓
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                ホテル情報の更新が完了しました。
            </h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 28px;">
                更新内容はシステムに正常に反映されました。
            </p>

            @php
                $backUrl = (!empty($search_hotel_name) || !empty($search_prefecture_id))
                    ? route('adminHotelSearchResult', array_filter(['hotel_name' => $search_hotel_name, 'prefecture_id' => $search_prefecture_id, 'page' => $search_page]))
                    : route('adminHotelSearchPage', array_filter(['page' => $search_page]));
            @endphp
            <div>
                <a href="{{ $backUrl }}" class="btn btn-primary">ホテル検索画面へ戻る</a>
            </div>
        </div>
    </div>
@endsection
