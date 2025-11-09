<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Breaktime;
use App\Models\User;
use App\Models\Application;

class Worktime extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'user_id',
        'start_time',
        'end_time',
        'remarks',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function breaktimes(){
        return $this->hasMany(Breaktime::class);
    }

    public function application(){
        return $this->hasOne(Application::class);
    }
}
