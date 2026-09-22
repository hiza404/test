<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'booking_id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['booking_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'chekin_time' => 'datetime',
        'checkout_time' => 'datetime',
    ];

    /**
     * Get the hotel associated with the booking.
     *
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Accessor for checkin_time alias
     */
    public function getCheckinTimeAttribute()
    {
        return $this->attributes['chekin_time'] ?? null;
    }

    /**
     * Mutator for checkin_time alias
     */
    public function setCheckinTimeAttribute($value)
    {
        $this->attributes['chekin_time'] = $value;
    }

    /**
     * Build the query for searching bookings
     *
     * @param string|null $customerName
     * @param string|null $customerContact
     * @param string|null $checkinTime
     * @param string|null $checkoutTime
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function searchBookingsQuery(
        ?string $customerName = null,
        ?string $customerContact = null,
        ?string $checkinTime = null,
        ?string $checkoutTime = null
    ) {
        $query = self::with('hotel.prefecture');

        if (!empty($customerName)) {
            $query->where('customer_name', 'LIKE', '%' . $customerName . '%');
        }

        if (!empty($customerContact)) {
            $query->where('customer_contact', 'LIKE', '%' . $customerContact . '%');
        }

        if (!empty($checkinTime)) {
            $query->where('chekin_time', '>=', $checkinTime);
        }

        if (!empty($checkoutTime)) {
            $query->where('checkout_time', '<=', $checkoutTime);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Search bookings by customer name, contact, check-in, and check-out
     *
     * @param string|null $customerName
     * @param string|null $customerContact
     * @param string|null $checkinTime
     * @param string|null $checkoutTime
     * @return array
     */
    public static function searchBookings(
        ?string $customerName = null,
        ?string $customerContact = null,
        ?string $checkinTime = null,
        ?string $checkoutTime = null
    ): array {
        return self::searchBookingsQuery(
            $customerName,
            $customerContact,
            $checkinTime,
            $checkoutTime
        )->get()->toArray();
    }

    /**
     * Customize the date format for array and JSON serialization.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}

