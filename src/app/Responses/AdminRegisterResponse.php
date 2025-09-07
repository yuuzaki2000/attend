<?php

namespace App\Responses;

use Illuminate\Contracts\Support\Responsable;
use Laravel\Fortify\Contracts\RegisterResponse;

class AdminRegisterResponse extends RegisterResponse implements Responsable
{
    //
    public function toResponse($request){
        return view('admin.admin-attendance-index');
    }
}
