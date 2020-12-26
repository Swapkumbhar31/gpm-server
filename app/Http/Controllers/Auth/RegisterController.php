<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Chapter;
use App\Module;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/';

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
            'contact' => 'required|string|digits:10',
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
        $module_id = Module::where('mod_index',Module::min('mod_index'))->first()->id;
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'contact' => $data['contact'],
            'password' => bcrypt($data['password']),
            'chapter_id' => Chapter::where('module_id',$module_id)->where('chap_index', Chapter::where('module_id',$module_id)->min('chap_index'))->first()->id,
        ]);
    }
}
