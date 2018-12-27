<?php

namespace App\Sport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Team extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'guid',
        'name',
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
        static::creating(function (self $team) {
            $team->guid = (string) Str::uuid();
        });
    }

    /**
     * Set default key for model binding.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'guid';
    }

    /**
     * Team has many players.
     *
     * @return HasMany
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
