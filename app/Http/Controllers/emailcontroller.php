<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class emailcontroller extends Controller
{
   public function userRegistration(Request $request){
    \Mail::send('emails.emailview',request()->all(), function($message){
        $message->to(request()->input('email'), 'Constructo Company')->subject('Constructo Email Verification');
        $message->from('constructocompany@gmail.com','Constructo Company');
        $message->replyto('constructocompany@gmail.com','Constructo Company');
     });
   }
}
