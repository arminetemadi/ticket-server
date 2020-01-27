<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * register a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {
            $user = new User;
            $user->fullname = $request->input('fullname');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->save();

            return response()->json(['user' => $user, 'message' => 'Done'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registeration failed!'], 409);
        }

    }

    /**
     * Get a JWT by given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        return $this->respondWithToken($user, $token);
    }

    /**
     * logout user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();

            return response()->json(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout failed!'], 409);
        }
    }
}
