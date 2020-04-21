<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Mail\Auth\VerifyMail;
use Mail;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password' => ['required', 'string', 'min:8', 'regex:/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\da-zA-Z]{8,}/', 'confirmed'],
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
        // original method
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);

        $user = User::create([
            'uuid' => Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => User::STATUS_INACTIVE,
            'verify_token' => Str::random(),
        ]);

        if ( !$user ) {
            return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        }

        $user->attachRole(8); // user role

        // sending email notification with queue
        Mail::to($user->email)
            ->queue(new VerifyMail($user));
        return $user;
    }


    /**
     * Overriding the parent method to disable automatic login
     */
    public function register()
    {
        $this->validator(request()->all())->validate();
        event(new Registered($user = $this->create(request()->all())));

        // return redirect()->route('login')
        //     ->with('success', 'Check your email and click on the link to verify.');

        session()->flash('message', __('Check your email and click on the link to verify.'));
        return redirect()->route('login');
    }

    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect()->route('login')
                ->with('error', 'Sorry your link cannot be identified.');
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->email_verified_at = date('Y-m-d H:i:s');

        $user->save();

        // return redirect()->route('login')
        //     ->with('success', 'Your e-mail is verified. You can now login.');

        session()->flash('message', __('Your e-mail is verified. You can now login.'));
        return redirect()->route('login');
    }
}
