<?php

namespace App\Sport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'team_id',
        'guid',
        'first_name',
        'last_name',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'team_id' => 'integer',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        // generate default UUID when creating new data
        static::creating(function (self $player) {
            $player->guid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'guid';
    }

    /**
     * Player belongs to team.
     *
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
