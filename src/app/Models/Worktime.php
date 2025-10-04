<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Breaktime;
use App\Models\User;

class Worktime extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'user_id', 'start_time', 'end_time', 'breaktime_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function breaktime(){
        return $this->belongsTo(Breaktime::class);
    }
}
