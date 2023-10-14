<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function sendEmailOTP(Request $request){
        dispatch(new SendEmailJob($request->email));
    }
}
