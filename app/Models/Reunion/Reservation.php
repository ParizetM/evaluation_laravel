<?php

namespace App\Models\Reunion;

use App\Models\User;
use App\Models\Reunion\Salle;
use App\Traits\LogAction;
use Database\Factories\Reunion\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;
    // use SoftDeletes;

    /**
     * @var list<string>
     */


    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'salle_id',
        'start_time',
        'end_time',
    ];

    /**
     * @return string
     */


    /**
     * A reservation belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User,$this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A reservation belongs to a salle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Salle,$this>
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
    /**
     * Get the user_id attribute.
     *
     * @return int
     */
    public function getUserIdAttribute(): int
    {
        return $this->attributes['user_id'] ?? 0;
    }

    /**
     * Get the salle_id attribute.
     *
     * @return int
     */
    public function getSalleIdAttribute(): int
    {
        return $this->attributes['salle_id'] ?? 0;
    }

    /**
     * Get the start_time attribute.
     *
     * @return string
     */
    public function getStartTimeAttribute(): string
    {
        return $this->attributes['start_time'] ?? '';
    }

    /**
     * Get the end_time attribute.
     *
     * @return string
     */
    public function getEndTimeAttribute(): string
    {
        return $this->attributes['end_time'] ?? '';
    }
}
