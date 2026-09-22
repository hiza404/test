@extends('admin.booking.search')

@section('search_results')
    <div class="search-result">
        @if (!empty($bookingList) && $bookingList->count() > 0)
            <div class="search-result-header">
                <h3 class="search-result-title">{{ !empty($customer_name) || !empty($customer_contact) || !empty($checkin_time) || !empty($checkout_time) ? '予約検索結果' : '予約一覧' }}</h3>
                <span class="result-count">{{ $bookingList->total() }} 件見つかりました</span>
            </div>
            <div class="table-responsive">
                <table class="shopsearchlist_table">
                    <thead>
                        <tr class="table-header">
                            <td nowrap>顧客名</td>
                            <td nowrap>顧客連絡先</td>
                            <td nowrap>ホテル名</td>
                            <td nowrap>チェックイン日時</td>
                            <td nowrap>チェックアウト日時</td>
                            <td nowrap>予約日時</td>
                            <td nowrap>情報更新日時</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookingList as $booking)
                            <tr class="table-row">
                                <td style="font-weight: 600;">{{ $booking['customer_name'] }}</td>
                                <td>{{ $booking['customer_contact'] }}</td>
                                <td>
                                    @if (!empty($booking['hotel']))
                                        <a class="hotel-name-link" href="{{ route('hotelDetail', ['hotel_id' => $booking['hotel']['hotel_id']]) }}" target="_blank">
                                            {{ $booking['hotel']['hotel_name'] }}
                                        </a>
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td><span class="date-text">{{ (string) ($booking['chekin_time'] ?? $booking['checkin_time'] ?? '') }}</span></td>
                                <td><span class="date-text">{{ (string) $booking['checkout_time'] }}</span></td>
                                <td><span class="date-text">{{ (string) $booking['created_at'] }}</span></td>
                                <td><span class="date-text">{{ (string) $booking['updated_at'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    全 {{ $bookingList->total() }} 件中 {{ $bookingList->firstItem() }} 〜 {{ $bookingList->lastItem() }} 件を表示
                </div>
                {{ $bookingList->links('common.admin.pagination') }}
            </div>
        @else
            <div class="no-result-box">
                <p style="margin: 0;">該当する予約情報が見つかりませんでした。</p>
            </div>
        @endif
    </div>
@endsection
