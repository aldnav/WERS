<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\Authenticatable;

class UserController extends Controller
{
    public function userContact(Authenticatable $user){

	    return response()->json(['result'=>$user->contact_number]);
	}    
}
