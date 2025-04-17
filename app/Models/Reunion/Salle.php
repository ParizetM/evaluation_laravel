<?php

namespace App\Models\Reunion;

use App\Traits\LogAction;
use Database\Factories\Reunion\SalleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salle extends Model
{
    /** @use HasFactory<SalleFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'capacite',
        'surface',
    ];


    /**
     * @var list<string>
     */
    protected $appends = [
        'actions',
    ];

    /**
     * @return string
     */
    public function getActionsAttribute()
    {
        return '';
    }

    /**
     * Une salle peut avoir plusieurs réservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Reservation,$this>
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Vérifie si une réservation est possible pour un créneau donné.
     *
     * @param string $start
     * @param string $end
     * @return bool
     */
    public function isAvailableFor($start, $end): bool
    {
        return !$this->reservations()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end])
                      ->orWhere(function ($query) use ($start, $end) {
                          $query->where('start_time', '<=', $start)
                                ->where('end_time', '>=', $end);
                      });
            })
            ->exists();
    }
    /**
     * @return string
     * @param string $value
     */
    public function getSurfaceAttribute($value): string
    {
        return $value;
    }
    /**
     * @return string
     * @param string $value
     */
    public function getCapaciteAttribute($value): string
    {
        return $value;
    }
    /**
     * @return string
     * @param string $value
     */
    public function getNomAttribute($value): string
    {
        return $value;
    }
}
