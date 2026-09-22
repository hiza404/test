<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'hotel_id';

    /**
     * @var array
     */
    protected $guarded = ['hotel_id'];

    /**
     * @return BelongsTo
     */
    public function prefecture(): BelongsTo
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id', 'prefecture_id');
    }

    /**
     * Build the query for searching hotels by name and/or prefecture
     *
     * @param string|null $hotelName
     * @param int|null $prefectureId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function searchHotelsQuery(?string $hotelName = null, ?int $prefectureId = null)
    {
        $query = self::with('prefecture');

        if (!empty($hotelName)) {
            $query->where('hotel_name', 'LIKE', '%' . $hotelName . '%');
        }

        if (!empty($prefectureId)) {
            $query->where('prefecture_id', $prefectureId);
        }

        return $query->orderBy('hotel_id', 'desc');
    }

    /**
     * Search hotels by name (partial match) and/or prefecture ID
     *
     * @param string|null $hotelName
     * @param int|null $prefectureId
     * @return array
     */
    public static function searchHotels(?string $hotelName = null, ?int $prefectureId = null): array
    {
        return self::searchHotelsQuery($hotelName, $prefectureId)->get()->toArray();
    }

    /**
     * Search hotel by hotel name (partial match for backward compatibility)
     *
     * @param string $hotelName
     * @return array
     */
    public static function getHotelListByName(string $hotelName): array
    {
        return self::searchHotels($hotelName);
    }

    /**
     * Override serializeDate method to customize date format
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
