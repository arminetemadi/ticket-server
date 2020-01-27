<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * update user profile info.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required|string'
        ]);

        try {
            $user = Auth::user();
            $user->fullname = $request->input('fullname');
            $user->save();

            return response()->json([
                'user' => [
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames()
                ],
                'message' => 'Done'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update failed!'], 409);
        }
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function all()
    {
        try {
            $result = User::all();

            return response()->json(['result' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error while retrieving tickets!'], 404);
        }
    }

    /**
     * Get one user.
     *
     * @return Response
     */
    public function get($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'user not found!'], 404);
        }
    }

    /**
     * update user info.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'fullname' => 'required|string'
        ]);

        try {
            $user = User::findOrFail($request->input('id'));
            $user->fullname = $request->input('fullname');
            $user->save();

            return response()->json(['user' => $user, 'message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update failed!'], 409);
        }
    }

}
