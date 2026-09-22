@extends('admin.hotel.search')

@section('search_results')
    <div class="search-result">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (!empty($hotelList) && $hotelList->count() > 0)
            <div class="search-result-header">
                <h3 class="search-result-title">{{ (isset($hotel_name) && $hotel_name !== '') || !empty($prefecture_id) ? '検索結果' : 'ホテル一覧' }}</h3>
                <span class="result-count">{{ $hotelList->total() }} 件見つかりました</span>
            </div>
            <div class="table-responsive">
                <table class="shopsearchlist_table">
                    <thead>
                        <tr class="table-header">
                            <td nowrap id="hotel_name">ホテル名</td>
                            <td nowrap id="pref">都道府県</td>
                            <td nowrap id="created_at">登録日</td>
                            <td nowrap id="updated_at">更新日</td>
                            <td nowrap class="btn_center" id="edit" style="width: 100px; text-align: center;">編集</td>
                            <td nowrap class="btn_center" id="delete" style="width: 100px; text-align: center;">削除</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hotelList as $hotel)
                            <tr class="table-row">
                                <td>
                                    <a class="hotel-name-link" href="{{ route('hotelDetail', ['hotel_id' => $hotel['hotel_id']]) }}" target="_blank">
                                        {{ $hotel['hotel_name'] }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-pref">{{ $hotel['prefecture']['prefecture_name'] ?? '' }}</span>
                                </td>
                                <td>
                                    <span class="date-text">{{ (string) $hotel['created_at'] }}</span>
                                </td>
                                <td>
                                    <span class="date-text">{{ (string) $hotel['updated_at'] }}</span>
                                </td>
                                <td style="text-align: center; white-space: nowrap; width: 100px;">
                                    <form action="{{ route('adminHotelEditPage') }}" method="get" style="display: inline-block; margin: 0;">
                                        <input type="hidden" name="hotel_id" value="{{ $hotel['hotel_id'] }}">
                                        @if(isset($hotel_name) && $hotel_name !== '')
                                            <input type="hidden" name="search_hotel_name" value="{{ $hotel_name }}">
                                        @endif
                                        @if(!empty($prefecture_id))
                                            <input type="hidden" name="search_prefecture_id" value="{{ $prefecture_id }}">
                                        @endif
                                        @if(request('page'))
                                            <input type="hidden" name="search_page" value="{{ request('page') }}">
                                        @endif
                                        <button type="submit" class="btn-table-edit">編集</button>
                                    </form>
                                </td>
                                <td style="text-align: center; white-space: nowrap; width: 100px;">
                                    <form action="{{ route('adminHotelDeleteProcess') }}" method="post" onsubmit="return confirm('本当に削除しますか？');" style="display: inline-block; margin: 0;">
                                        @csrf
                                        <input type="hidden" name="hotel_id" value="{{ $hotel['hotel_id'] }}">
                                        @if(isset($hotel_name) && $hotel_name !== '')
                                            <input type="hidden" name="search_hotel_name" value="{{ $hotel_name }}">
                                        @endif
                                        @if(!empty($prefecture_id))
                                            <input type="hidden" name="search_prefecture_id" value="{{ $prefecture_id }}">
                                        @endif
                                        @if(request('page'))
                                            <input type="hidden" name="search_page" value="{{ request('page') }}">
                                        @endif
                                        <button type="button" class="btn-table-delete" onclick="openDeleteModal(this, '{{ addslashes($hotel['hotel_name']) }}')">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    全 {{ $hotelList->total() }} 件中 {{ $hotelList->firstItem() }} 〜 {{ $hotelList->lastItem() }} 件を表示
                </div>
                {{ $hotelList->links('common.admin.pagination') }}
            </div>
        @else
            <div class="no-result-box">
                <p style="margin: 0;">検索結果がありません</p>
            </div>
        @endif

        <!-- Custom Delete Confirmation Modal -->
        <div id="deleteModal" class="custom-modal-overlay" style="display: none;">
            <div class="custom-modal-dialog">
                <div class="modal-icon-wrapper">
                    <span>🗑️</span>
                </div>
                <h3 class="modal-title">削除の確認</h3>
                <p class="modal-message">
                    ホテル「<strong id="modalHotelName" class="hotel-target-name"></strong>」を<br>
                    本当に削除しますか？
                </p>
                <p class="modal-subtext">※この操作は取り消せません。</p>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">キャンセル</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">削除する</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script>
        let currentDeleteForm = null;

        function openDeleteModal(button, hotelName) {
            currentDeleteForm = button.closest('form');
            document.getElementById('modalHotelName').textContent = hotelName;
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.style.display = 'none';
            }
            document.body.style.overflow = '';
            currentDeleteForm = null;
        }

        document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
            if (currentDeleteForm) {
                currentDeleteForm.submit();
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('deleteModal');
                if (modal && modal.style.display === 'flex') {
                    closeDeleteModal();
                }
            }
        });
    </script>
@endsection