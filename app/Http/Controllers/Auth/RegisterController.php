<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'contact_number'=>'required|min:11' 
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'contact_number' => $data['contact_number'],

        ]);
        
        // DB::beginTransaction();
        // $now = now();
        // $message = null;
        // $extras = array();
        // try {
        //     DB::insert('insert into users (name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?)',
        //     [
        //         $data['name'],
        //         $data['email'],
        //         bcrypt($data['password']),
        //         $now,
        //         $now
        //     ]);
        //     DB::commit();
        // } catch (\Illuminate\Database\QueryException $ex)
        // {
        //     $code = 400;
        //     $message = $ex->getMessage();
        //     DB::rollback();
        // } finally {
        //     // NOTE: Be specific on db error messages
        //     if ($message == null) {
        //         $code = 200;
        //         $message = 'Success';
        //         $extras['redirect'] = '/home';
        //     }
        // }

        // $data = array(
        //     'code'    => $code,
        //     'message' => $message,
        //     'extras'  => $extras,
        //     'data'    => $data
        // );

        // return response()->json($data, $code);

    }
}
