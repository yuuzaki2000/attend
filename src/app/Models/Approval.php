<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id',
        'is_approved',
        'created_at',
        'updated_at',
    ];

    public function application(){
        return $this->belongsTo(Application::class);
    }
}
