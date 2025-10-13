<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TempWorktime;

class TempBreaktime extends Model
{
    use HasFactory;
    protected $table = 'temp_breaktimes';
    protected $fillable = [
        'start_time',
        'end_time',
        'temp_breaktime_id',
    ];

    public function tempWorktime(){
        $this->belongsTo(TempWorktime::class);
    }
}
