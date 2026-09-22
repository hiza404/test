<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Hotel;
use App\Models\Prefecture;
use App\Models\Booking;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HotelAdminTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test hotel search page renders successfully
     */
    public function test_hotel_search_page_renders(): void
    {
        $response = $this->get(route('adminHotelSearchPage'));

        $response->assertStatus(200);
        $response->assertSee('検索画面');
        $response->assertSee('都道府県を選択');
    }

    /**
     * Test empty search displays the required Japanese error message directly below the form
     */
    public function test_empty_search_displays_error_message(): void
    {
        $response = $this->post(route('adminHotelSearchResult'), [
            'hotel_name' => '',
            'prefecture_id' => '',
        ]);

        $response->assertStatus(200);
        $response->assertSee('何も入力されていません');
        $response->assertSee('ホテル一覧');
    }

    /**
     * Test partial match search on hotel name
     */
    public function test_partial_match_hotel_name_search(): void
    {
        $response = $this->post(route('adminHotelSearchResult'), [
            'hotel_name' => 'シティ',
        ]);

        $response->assertStatus(200);
        $response->assertSee('シティホテル');
    }

    /**
     * Test search by prefecture
     */
    public function test_search_by_prefecture(): void
    {
        $response = $this->post(route('adminHotelSearchResult'), [
            'prefecture_id' => 11, // Tokyo
        ]);

        $response->assertStatus(200);
        $response->assertSee('東京');
    }

    /**
     * Test hotel creation page renders
     */
    public function test_hotel_create_page_renders(): void
    {
        $response = $this->get(route('adminHotelCreatePage'));

        $response->assertStatus(200);
        $response->assertSee('ホテル追加');
    }

    /**
     * Test hotel creation with valid data
     */
    public function test_hotel_can_be_created(): void
    {
        $prefecture = Prefecture::first();
        $hotelName = 'THK Test Hotel ' . uniqid();

        $response = $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => $hotelName,
            'prefecture_id' => $prefecture->prefecture_id,
        ]);

        $response->assertRedirect(route('adminHotelSearchPage'));
        $response->assertSessionHas('success', 'ホテルを追加しました。');

        $this->assertDatabaseHas('hotels', [
            'hotel_name' => $hotelName,
            'prefecture_id' => $prefecture->prefecture_id,
        ]);
    }

    /**
     * Test hotel edit 3-step workflow
     */
    public function test_hotel_edit_3_step_workflow(): void
    {
        $hotel = Hotel::first();
        $prefecture = Prefecture::first();
        $updatedName = 'Updated Hotel ' . uniqid();

        // Step 1: View edit page
        $step1 = $this->get(route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id]));
        $step1->assertStatus(200);
        $step1->assertSee('ホテル情報編集');
        $step1->assertSee($hotel->hotel_name);

        // Step 2: Submit to confirmation screen
        $step2 = $this->post(route('adminHotelEditConfirm'), [
            'hotel_id' => $hotel->hotel_id,
            'hotel_name' => $updatedName,
            'prefecture_id' => $prefecture->prefecture_id,
        ]);
        $step2->assertStatus(200);
        $step2->assertSee('ホテル情報編集 - 確認画面');
        $step2->assertSee($updatedName);

        // Step 3: Commit update and view completion screen
        $step3 = $this->post(route('adminHotelEditProcess'), [
            'hotel_id' => $hotel->hotel_id,
            'hotel_name' => $updatedName,
            'prefecture_id' => $prefecture->prefecture_id,
            'action' => 'update',
        ]);
        $step3->assertRedirect(route('adminHotelEditCompletePage'));

        $completePage = $this->get(route('adminHotelEditCompletePage'));
        $completePage->assertStatus(200);
        $completePage->assertSee('ホテル情報の更新が完了しました。');

        $this->assertDatabaseHas('hotels', [
            'hotel_id' => $hotel->hotel_id,
            'hotel_name' => $updatedName,
        ]);
    }

    /**
     * Test hotel deletion
     */
    public function test_hotel_can_be_deleted(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'Hotel To Delete',
            'prefecture_id' => 1,
            'file_path' => 'hoteltype/business.png',
        ]);

        $response = $this->post(route('adminHotelDeleteProcess'), [
            'hotel_id' => $hotel->hotel_id,
        ]);

        $response->assertRedirect(route('adminHotelSearchPage'));
        $response->assertSessionHas('success', 'ホテルを削除しました。');

        $this->assertDatabaseMissing('hotels', [
            'hotel_id' => $hotel->hotel_id,
        ]);
    }

    /**
     * Test booking search feature
     */
    public function test_booking_search_feature(): void
    {
        // View booking search page
        $response = $this->get(route('adminBookingSearchPage'));
        $response->assertStatus(200);
        $response->assertSee('予約情報検索');

        // Search by customer name
        $searchResponse = $this->post(route('adminBookingSearchResult'), [
            'customer_name' => '山田',
        ]);
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('山田 太郎');
    }

    /**
     * Test hotel list pagination renders and handles page navigation
     */
    public function test_hotel_pagination(): void
    {
        // View page 1
        $page1 = $this->get(route('adminHotelSearchPage'));
        $page1->assertStatus(200);
        $page1->assertSee('pagination-nav');
        $page1->assertSee('件中');

        // View page 2
        $page2 = $this->get(route('adminHotelSearchPage', ['page' => 2]));
        $page2->assertStatus(200);
        $page2->assertSee('pagination-nav');
        $page2->assertSee('11 〜 20 件を表示');
    }

    /**
     * Test booking list pagination renders and handles page navigation
     */
    public function test_booking_pagination(): void
    {
        // Seed multiple bookings in the test environment if needed
        $hotel = Hotel::first();
        for ($i = 1; $i <= 15; $i++) {
            Booking::create([
                'hotel_id' => $hotel->hotel_id,
                'customer_name' => 'Test User ' . $i,
                'customer_contact' => 'test' . $i . '@example.com',
                'chekin_time' => now()->addDays($i),
                'checkout_time' => now()->addDays($i + 1),
            ]);
        }

        // View page 1
        $page1 = $this->get(route('adminBookingSearchPage'));
        $page1->assertStatus(200);
        $page1->assertSee('pagination-nav');

        // View page 2
        $page2 = $this->get(route('adminBookingSearchPage', ['page' => 2]));
        $page2->assertStatus(200);
        $page2->assertSee('pagination-nav');
        $page2->assertSee('11 〜');
    }

    /**
     * Test hotel deletion preserves search filters upon redirect
     */
    public function test_delete_preserves_search_filters(): void
    {
        $hotel = Hotel::create([
            'hotel_name' => 'City Hotel Filter Test',
            'prefecture_id' => 11,
            'file_path' => 'hoteltype/business.png',
        ]);

        $response = $this->post(route('adminHotelDeleteProcess'), [
            'hotel_id' => $hotel->hotel_id,
            'search_hotel_name' => 'City Hotel',
            'search_prefecture_id' => 11,
        ]);

        // Should redirect back to search results with active filter parameters
        $response->assertRedirect(route('adminHotelSearchResult', [
            'hotel_name' => 'City Hotel',
            'prefecture_id' => 11,
        ]));
        $response->assertSessionHas('success', 'ホテルを削除しました。');

        $this->assertDatabaseMissing('hotels', [
            'hotel_id' => $hotel->hotel_id,
        ]);
    }

    /**
     * Test hotel edit workflow preserves search filters across steps
     */
    public function test_edit_preserves_search_filters(): void
    {
        $hotel = Hotel::first();
        $prefecture = Prefecture::first();
        $updatedName = 'Updated Name Filter ' . uniqid();

        // Step 1: View edit page with search parameters
        $step1 = $this->get(route('adminHotelEditPage', [
            'hotel_id' => $hotel->hotel_id,
            'search_hotel_name' => 'Keyword',
            'search_prefecture_id' => $prefecture->prefecture_id,
            'search_page' => 2,
        ]));
        $step1->assertStatus(200);
        $step1->assertSee('value="Keyword"', false);

        // Step 2: Confirm edit with search parameters
        $step2 = $this->post(route('adminHotelEditConfirm'), [
            'hotel_id' => $hotel->hotel_id,
            'hotel_name' => $updatedName,
            'prefecture_id' => $prefecture->prefecture_id,
            'search_hotel_name' => 'Keyword',
            'search_prefecture_id' => $prefecture->prefecture_id,
            'search_page' => 2,
        ]);
        $step2->assertStatus(200);
        $step2->assertSee('value="Keyword"', false);

        // Step 3: Commit edit
        $step3 = $this->post(route('adminHotelEditProcess'), [
            'hotel_id' => $hotel->hotel_id,
            'hotel_name' => $updatedName,
            'prefecture_id' => $prefecture->prefecture_id,
            'action' => 'update',
            'search_hotel_name' => 'Keyword',
            'search_prefecture_id' => $prefecture->prefecture_id,
            'search_page' => 2,
        ]);
        $step3->assertRedirect(route('adminHotelEditCompletePage', [
            'search_hotel_name' => 'Keyword',
            'search_prefecture_id' => $prefecture->prefecture_id,
            'search_page' => 2,
        ]));

        // Completion page has link returning to filtered results
        $completePage = $this->get(route('adminHotelEditCompletePage', [
            'search_hotel_name' => 'Keyword',
            'search_prefecture_id' => $prefecture->prefecture_id,
            'search_page' => 2,
        ]));
        $completePage->assertStatus(200);
        $completePage->assertSee(route('adminHotelSearchResult', [
            'hotel_name' => 'Keyword',
            'prefecture_id' => $prefecture->prefecture_id,
            'page' => 2,
        ]));
    }

    /**
     * Test reload/reset button exists on hotel and booking search pages
     */
    public function test_reset_reload_button_exists(): void
    {
        $hotelSearch = $this->get(route('adminHotelSearchPage'));
        $hotelSearch->assertStatus(200);
        $hotelSearch->assertSee('リセット');

        $bookingSearch = $this->get(route('adminBookingSearchPage'));
        $bookingSearch->assertStatus(200);
        $bookingSearch->assertSee('リセット');
    }

    /**
     * Test hotel deletion removes the uploaded image file from public directory
     */
    public function test_delete_hotel_removes_uploaded_image_file(): void
    {
        $imageDir = public_path('assets/img/hotel');
        if (!file_exists($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        $testFileName = 'hotel_test_delete_' . time() . '.png';
        $testFilePath = $imageDir . '/' . $testFileName;
        file_put_contents($testFilePath, 'dummy image content');

        $this->assertFileExists($testFilePath);

        $hotel = Hotel::create([
            'hotel_name' => 'Hotel With Custom Image ' . uniqid(),
            'prefecture_id' => 1,
            'file_path' => 'hotel/' . $testFileName,
        ]);

        $response = $this->post(route('adminHotelDeleteProcess'), [
            'hotel_id' => $hotel->hotel_id,
        ]);

        $response->assertRedirect(route('adminHotelSearchPage'));
        $this->assertDatabaseMissing('hotels', ['hotel_id' => $hotel->hotel_id]);
        $this->assertFileDoesNotExist($testFilePath);
    }

    /**
     * Test creating duplicate hotel name in the same prefecture is rejected
     */
    public function test_duplicate_hotel_name_in_same_prefecture_is_rejected(): void
    {
        $hotelName = 'Unique Test Hotel ' . uniqid();
        Hotel::create([
            'hotel_name' => $hotelName,
            'prefecture_id' => 1,
            'file_path' => 'hoteltype/business.png',
        ]);

        $response = $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => $hotelName,
            'prefecture_id' => 1,
        ]);

        $response->assertSessionHasErrors(['hotel_name']);
    }

    /**
     * Test creating same hotel name in a different prefecture is allowed
     */
    public function test_same_hotel_name_in_different_prefecture_is_allowed(): void
    {
        $hotelName = 'Multi Pref Hotel ' . uniqid();
        Hotel::create([
            'hotel_name' => $hotelName,
            'prefecture_id' => 1,
            'file_path' => 'hoteltype/business.png',
        ]);

        $response = $this->post(route('adminHotelCreateProcess'), [
            'hotel_name' => $hotelName,
            'prefecture_id' => 2,
        ]);

        $response->assertRedirect(route('adminHotelSearchPage'));
        $response->assertSessionHas('success', 'ホテルを追加しました。');
    }

    /**
     * Test hotel creation validation requires hotel_name and prefecture_id
     */
    public function test_hotel_creation_validation_requires_fields(): void
    {
        $response = $this->post(route('adminHotelCreateProcess'), []);

        $response->assertSessionHasErrors([
            'hotel_name' => 'ホテル名を入力してください。',
            'prefecture_id' => '都道府県を選択してください。',
        ]);
    }

    /**
     * Test hotel edit confirmation validates unique name within prefecture
     */
    public function test_hotel_edit_confirm_validates_unique_name(): void
    {
        $hotel1 = Hotel::create([
            'hotel_name' => 'Existing Hotel ' . uniqid(),
            'prefecture_id' => 1,
            'file_path' => 'hoteltype/business.png',
        ]);

        $hotel2 = Hotel::create([
            'hotel_name' => 'Second Hotel ' . uniqid(),
            'prefecture_id' => 1,
            'file_path' => 'hoteltype/business.png',
        ]);

        // Attempt to update hotel2 with hotel1's name in the same prefecture
        $response = $this->post(route('adminHotelEditConfirm'), [
            'hotel_id' => $hotel2->hotel_id,
            'hotel_name' => $hotel1->hotel_name,
            'prefecture_id' => 1,
        ]);

        $response->assertSessionHasErrors([
            'hotel_name' => 'この都道府県には既に同じ名前のホテルが存在します。',
        ]);
    }
}
