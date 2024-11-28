<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerSetting extends Model
{
    use HasFactory;

    protected $fillable = ['lecturer_id', 'is_identity_visible'];

    /**
     * Get the lecturer associated with the settings.
     */
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}
