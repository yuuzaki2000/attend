<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Worktime;

class Breaktime extends Model
{
    use HasFactory;

    protected $fillable = ['start_time', 'end_time'];

    public function worktime()
    {
        return $this->belongsTo(Worktime::class);
    }

}
