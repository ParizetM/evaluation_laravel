<?php

namespace App\Models\Reunion;

use App\Models\User;
use App\Models\Reunion\Salle;
use App\Traits\LogAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    // use SoftDeletes;
    use LogAction;

    /**
     * @var list<string>
     */
    protected $appends = [
        'actions',
    ];

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
    public function getActionsAttribute()
    {
        return '';
    }

    /**
     * A reservation belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A reservation belongs to a salle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
