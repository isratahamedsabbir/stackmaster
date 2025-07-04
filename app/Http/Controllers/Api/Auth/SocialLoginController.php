<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public $select;
    public function __construct()
    {
        parent::__construct();
        $this->select = ['id', 'name', 'email', 'avatar'];   
    }

    public function RedirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function HandleProviderCallback($provider)
    {
        $data = Socialite::driver($provider)->stateless()->user();
        
    }

    public function SocialLogin(Request $request)
    {
        $request->validate([
            'token'         => 'required',
            'provider'      => 'required|in:google,facebook,apple',
            'role'          => 'required|in:user,trainer',
        ]);

        try {
            $provider   = $request->provider;
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);

            if ($socialUser) {
                $user      = User::withTrashed()->where('email', $socialUser->email)->first();
                if (!empty($user->deleted_at)) {
                    return Helper::jsonErrorResponse('Your account has been deleted.', 410);
                }
                $isNewUser = false;

                if (!$user) {
                    $password = Str::random(16);
                    /* if ($request->input('role') == 'trainer') {
                        $status = 'inactive';
                    } else {
                        $status = 'active';
                    } */
                    $user     = User::create([
                        'name'              => $socialUser->getName(),
                        'email'             => $socialUser->getEmail(),
                        'password'          => bcrypt($password),
                        'avatar'            => $socialUser->getAvatar(),
                        'status'            => $status ?? 'active',
                        'otp_verified_at' => now(),
                    ]);
                    $user->assignRole($request->input('role'));
                    $isNewUser = true;
                    //notify to admin start
                    /* $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new UserRegistrationNotification($user, "{$user->name} Has Joined the Platform – Review Details",));
                    } */
                    //notify to admin end
                }

                Auth::login($user);
                $token = auth('api')->login($user);

                $data = User::select($this->select)->with('roles')->find($user->id);

                return response()->json([
                    'status'     => true,
                    'message'    => 'User logged in successfully.',
                    'code'       => 200,
                    'token_type' => 'bearer',
                    'token'      => $token,
                    'expires_in' => auth('api')->factory()->getTTL() * 60,
                    'data'       => $data
                ], 200);
            } else {
                return Helper::jsonResponse(false, 'Unauthorized', 401);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Something went wrong', 500, ['error' => $e->getMessage()]);
        }
    }
}
