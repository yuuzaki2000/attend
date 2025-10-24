<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Worktime;
use App\Models\TempWorktime;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'worktime_id',
        'temp_worktime_id',
        'reason',
    ];

    public function worktime(){
        return $this->belongsTo(Worktime::class);
    }

    public function tempWorktime(){
        return $this->belongsTo(TempWorktime::class);
    }

    public function approval(){
        return $this->hasOne(Approval::class);
    }
}
