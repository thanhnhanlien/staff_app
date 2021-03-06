<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

     /**
     * Get the user who manage the department.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
