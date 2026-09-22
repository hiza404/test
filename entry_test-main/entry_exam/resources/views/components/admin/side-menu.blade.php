<div class="admin-menu">
    <div class="menu-block">
        <div class="menu-header">
            <a href="{{ route('adminTop') }}" class="brand-link">
                <span class="badge-icon">🏨</span>
                <h3 class="title">サイト管理</h3>
            </a>
        </div>
        <ul class="menu-list">
            <li>
                <a class="link {{ request()->routeIs('adminHotelSearch*') ? 'active' : '' }}" href="{{ route('adminHotelSearchPage') }}">
                    <span>🔍</span> ホテル検索
                </a>
            </li>
            <li>
                <a class="link {{ request()->routeIs('adminHotelCreate*') ? 'active' : '' }}" href="{{ route('adminHotelCreatePage') }}">
                    <span>➕</span> ホテル追加
                </a>
            </li>
            <li>
                <a class="link {{ request()->routeIs('adminBooking*') ? 'active' : '' }}" href="{{ route('adminBookingSearchPage') }}">
                    <span>📅</span> 予約情報検索
                </a>
            </li>
        </ul>
    </div>
</div>