<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TempBreaktime;
use App\Models\Application;

class TempWorktime extends Model
{
    use HasFactory;
    protected $table = 'temp_worktimes';

    protected $fillable = [
        'date',
        'user_id',
        'start_time',
        'end_time',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tempBreaktimes(){
        return $this->hasMany(TempBreaktime::class);
    }

    public function application(){
        return $this->hasOne(Application::class);
    }
}
