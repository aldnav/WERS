<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(User $user)
    {   
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(User $user)
    { 
//        return response()->json(['result'=>'hi']);

        $this->validate(request(), [
            'name' => 'required',
            'email'=>'required',
            'contact_number'=>'min:13|regex:/(09)[0-9]{2}-[0-9]{3}-[0-9]{4}/'
        ]);

        $user->name = request('name');
        $user->email = request('email');
        $user->contact_number = request('contact_number');
        $user->lat=request('lat');
        $user->lng=request('lng');
        $user->save();
//       return view('front');

        return ['redirect' => route('home')];
    }

 //    public function userContact(Authenticatable $user){

	//     return response()->json(['result'=>$user->contact_number]);
	// }    
}
