<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
    /**
     * [login Loggin user and create token]
     * @param  [string] email [description]
     * @param  [string] password [description]
     * @return [obj] user [description]
     * @return [string] token [description]
     */
    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        $customClaims = [];
        if ($request->rememberMe)
            $customClaims = ['exp' => Carbon::now()->addDay()->timestamp];

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials, $customClaims)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'Could not create token'], 500);
        }

        $user = Auth::user();
        // all good so return the token and user
        return response()->json(compact('user', 'token'));
    }

    /**
     * [logout Logout user and revoke token]
     * @return [bool] success [description]
     */
    public function logout()
    {
        $success = JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(compact('success'));
    }

    /**
     * [register Create user and send confirmation mail]
     * @param  [string] name [description]
     * @param  [string] email [description]
     * @param  [string] password [description]
     * @return [obj] user [description]
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( $request->email ) ) ) . '?d=retro'
        ]);
    }

    /**
     * [getAuthenticatedUser Get user authenticated]
     * @return [obj] user [description]
     */
    public function getAuthUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return $user;
    }
}
