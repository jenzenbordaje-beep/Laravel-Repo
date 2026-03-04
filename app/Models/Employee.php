<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = ['name', 'email', 'admin_id'];

    /**
     * Get the admin that manages this employee.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get all equipment assigned to this employee.
     */
    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }
}
