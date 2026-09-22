<!-- base view -->
@extends('common.admin.base')

<!-- CSS per page -->
@section('custom_css')
    @vite('resources/scss/admin/search.scss')
    @vite('resources/scss/admin/result.scss')
    <style>
        .booking-search-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }
        .booking-search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .booking-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }
        .booking-field input {
            width: 100%;
        }
    </style>
@endsection

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper search-page-wrapper">
        <h2 class="title">予約情報検索</h2>

        <div class="booking-search-card">
            <form action="{{ route('adminBookingSearchResult') }}" method="post">
                @csrf
                <div class="booking-search-grid">
                    <div class="booking-field">
                        <label for="customer_name">顧客名</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $customer_name ?? '') }}" placeholder="例: 山田 太郎">
                    </div>
                    <div class="booking-field">
                        <label for="customer_contact">顧客連絡先</label>
                        <input type="text" id="customer_contact" name="customer_contact" value="{{ old('customer_contact', $customer_contact ?? '') }}" placeholder="例: 090-xxxx-xxxx / email">
                    </div>
                    <div class="booking-field">
                        <label for="checkin_time">チェックイン日時</label>
                        <input type="datetime-local" id="checkin_time" name="checkin_time" value="{{ old('checkin_time', $checkin_time ?? '') }}">
                    </div>
                    <div class="booking-field">
                        <label for="checkout_time">チェックアウト日時</label>
                        <input type="datetime-local" id="checkout_time" name="checkout_time" value="{{ old('checkout_time', $checkout_time ?? '') }}">
                    </div>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <button type="submit" class="btn btn-primary">検索</button>
                    <a href="{{ route('adminBookingSearchPage') }}" class="btn btn-secondary" title="検索条件をクリアして再読み込み">
                        <span>🔄</span> リセット
                    </a>
                </div>
            </form>
        </div>

        @yield('search_results')
    </div>
@endsection
