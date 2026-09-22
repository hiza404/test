<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\HotelRequest;
use App\Models\Hotel;
use App\Models\Prefecture;

class HotelController extends Controller
{
    /**
     * Display the hotel search page (auto-displaying hotel list with pagination)
     *
     * @param Request $request
     * @return View
     */
    public function showSearch(Request $request): View
    {
        $prefectures = Prefecture::all();
        $hotelList = Hotel::with('prefecture')->orderBy('hotel_id', 'desc')->paginate(10)->appends($request->except('_token', 'page'));

        return view('admin.hotel.result', compact('prefectures', 'hotelList'));
    }

    /**
     * Display the search results page
     *
     * @return View
     */
    public function showResult(): View
    {
        return view('admin.hotel.result');
    }

    /**
     * Display the hotel creation page
     *
     * @return View
     */
    public function showCreate(): View
    {
        $prefectures = Prefecture::all();

        return view('admin.hotel.create', compact('prefectures'));
    }

    /**
     * Display the hotel edit input screen (Step 1)
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function showEdit(Request $request): View|RedirectResponse
    {
        $hotelId = $request->query('hotel_id', $request->input('hotel_id'));

        if (empty($hotelId)) {
            return redirect()->route('adminHotelSearchPage');
        }

        $hotel = Hotel::with('prefecture')->findOrFail($hotelId);
        $prefectures = Prefecture::all();

        $searchHotelName = $request->input('search_hotel_name');
        $searchPrefectureId = $request->input('search_prefecture_id');
        $searchPage = $request->input('search_page');

        return view('admin.hotel.edit', compact('hotel', 'prefectures', 'searchHotelName', 'searchPrefectureId', 'searchPage'));
    }

    /**
     * Display the hotel edit confirmation screen (Step 2)
     *
     * @param Request $request
     * @return View
     */
    public function showEditConfirm(HotelRequest $request): View
    {
        $hotel = Hotel::findOrFail($request->input('hotel_id'));
        $prefecture = Prefecture::findOrFail($request->input('prefecture_id'));

        $newImagePath = null;
        if ($request->hasFile('hotel_image')) {
            $imageDirectory = public_path('assets/img/hotel');
            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0755, true);
            }

            $extension = $request->file('hotel_image')->getClientOriginalExtension();
            $fileName = 'hotel_' . time() . '_' . uniqid() . '.' . $extension;
            $request->file('hotel_image')->move($imageDirectory, $fileName);
            $newImagePath = 'hotel/' . $fileName;
        }

        return view('admin.hotel.edit_confirm', [
            'hotel' => $hotel,
            'prefecture' => $prefecture,
            'hotel_name' => $request->input('hotel_name'),
            'prefecture_id' => $request->input('prefecture_id'),
            'current_file_path' => $hotel->file_path,
            'new_file_path' => $newImagePath,
            'search_hotel_name' => $request->input('search_hotel_name'),
            'search_prefecture_id' => $request->input('search_prefecture_id'),
            'search_page' => $request->input('search_page'),
        ]);
    }

    /**
     * Display the hotel edit completion screen (Step 3)
     *
     * @param Request $request
     * @return View
     */
    public function showEditComplete(Request $request): View
    {
        return view('admin.hotel.edit_complete', [
            'search_hotel_name' => $request->query('search_hotel_name'),
            'search_prefecture_id' => $request->query('search_prefecture_id'),
            'search_page' => $request->query('search_page'),
        ]);
    }

    /**
     * Search hotels by name (partial match) and/or prefecture
     *
     * @param Request $request
     * @return View
     */
    public function searchResult(Request $request): View
    {
        $hotelNameToSearch = $request->input('hotel_name');
        $prefectureIdToSearch = $request->input('prefecture_id');
        $prefectures = Prefecture::all();

        // Bug fix: If nothing is entered, display error message directly below the form while still displaying all hotels
        if ((is_null($hotelNameToSearch) || trim($hotelNameToSearch) === '') && empty($prefectureIdToSearch)) {
            $hotelList = Hotel::with('prefecture')->orderBy('hotel_id', 'desc')->paginate(10);

            return view('admin.hotel.result', [
                'hotelList' => $hotelList,
                'prefectures' => $prefectures,
                'errorMessage' => '何も入力されていません',
            ]);
        }

        // Perform partial match search on hotel name and/or filter by prefecture with pagination
        $hotelList = Hotel::searchHotelsQuery(
            trim($hotelNameToSearch) !== '' ? trim($hotelNameToSearch) : null,
            !empty($prefectureIdToSearch) ? (int)$prefectureIdToSearch : null
        )->paginate(10)->appends($request->except('_token', 'page'));

        return view('admin.hotel.result', [
            'hotelList' => $hotelList,
            'prefectures' => $prefectures,
            'hotel_name' => $hotelNameToSearch,
            'prefecture_id' => $prefectureIdToSearch,
        ]);
    }

    /**
     * Create a new hotel
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(HotelRequest $request): RedirectResponse
    {
        $filePath = 'hoteltype/business.png'; // Default placeholder image

        // Handle image upload under public/assets/img/hotel
        if ($request->hasFile('hotel_image')) {
            $imageDirectory = public_path('assets/img/hotel');
            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0755, true);
            }

            $extension = $request->file('hotel_image')->getClientOriginalExtension();
            $fileName = 'hotel_' . time() . '_' . uniqid() . '.' . $extension;
            $request->file('hotel_image')->move($imageDirectory, $fileName);
            $filePath = 'hotel/' . $fileName;
        }

        Hotel::create([
            'hotel_name' => $request->input('hotel_name'),
            'prefecture_id' => $request->input('prefecture_id'),
            'file_path' => $filePath,
        ]);

        return redirect()->route('adminHotelSearchPage')->with('success', 'ホテルを追加しました。');
    }

    /**
     * Commit hotel edit updates to database
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit(HotelRequest $request): RedirectResponse
    {
        // If user clicked 'Back' from confirmation screen, return to edit page
        if ($request->input('action') === 'back') {
            return redirect()->route('adminHotelEditPage', array_filter([
                'hotel_id' => $request->input('hotel_id'),
                'search_hotel_name' => $request->input('search_hotel_name'),
                'search_prefecture_id' => $request->input('search_prefecture_id'),
                'search_page' => $request->input('search_page'),
            ]))->withInput();
        }

        $hotel = Hotel::findOrFail($request->input('hotel_id'));

        $hotel->hotel_name = $request->input('hotel_name');
        $hotel->prefecture_id = $request->input('prefecture_id');

        if ($request->filled('file_path')) {
            // If the image was updated, delete the old custom uploaded image from public/assets/img/hotel/
            if (!empty($hotel->file_path) && str_starts_with($hotel->file_path, 'hotel/') && $hotel->file_path !== $request->input('file_path')) {
                $oldImagePath = public_path('assets/img/' . $hotel->file_path);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $hotel->file_path = $request->input('file_path');
        }

        $hotel->save();

        return redirect()->route('adminHotelEditCompletePage', array_filter([
            'search_hotel_name' => $request->input('search_hotel_name'),
            'search_prefecture_id' => $request->input('search_prefecture_id'),
            'search_page' => $request->input('search_page'),
        ]));
    }

    /**
     * Delete a hotel by ID and return to current search filter/page
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $hotelId = $request->input('hotel_id');
        $searchHotelName = $request->input('search_hotel_name');
        $searchPrefectureId = $request->input('search_prefecture_id');
        $searchPage = $request->input('search_page');

        if (!empty($hotelId)) {
            $hotel = Hotel::find($hotelId);
            if ($hotel) {
                // Delete the hotel's custom uploaded image file from public/assets/img/hotel/
                if (!empty($hotel->file_path) && str_starts_with($hotel->file_path, 'hotel/')) {
                    $imagePath = public_path('assets/img/' . $hotel->file_path);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }

                $hotel->delete();

                $redirectParams = array_filter([
                    'hotel_name' => $searchHotelName,
                    'prefecture_id' => $searchPrefectureId,
                    'page' => $searchPage,
                ]);

                if (!empty($searchHotelName) || !empty($searchPrefectureId)) {
                    return redirect()->route('adminHotelSearchResult', $redirectParams)->with('success', 'ホテルを削除しました。');
                }

                return redirect()->route('adminHotelSearchPage', array_filter(['page' => $searchPage]))->with('success', 'ホテルを削除しました。');
            }
        }

        return redirect()->route('adminHotelSearchPage');
    }
}
