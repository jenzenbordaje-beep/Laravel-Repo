<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'description',
        'status',
        'assigned_to',
        'assigned_at',
        'is_archived',
        'archived_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    /**
     * Get the user who is assigned this equipment.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all requests for this equipment.
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
