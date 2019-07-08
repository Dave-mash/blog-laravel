<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\RegisterAuthRequest;

class UserController extends Controller
{
    public $loginAfterSignUp = true;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return UserResource::collection($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterAuthRequest $request)
    {

        $user = new User;
        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phoneNumber = $request->input('phoneNumber');
        
        if ($user->password !== $user->c_password) {
            return response()->json([
                'error' => 'Your passwords don\'t match',
                'status' => 422
            ], 422);
        }
        
        $user->password = Hash::make($request->password);

        if (User::where('email', '=', $user->email)->first()) {
            return response()->json([
                'error' => 'An account with this email already exists',
                'status' => 409
            ]);
        }

        if (User::where('phoneNumber', '=', $user->phoneNumber)->first()) {
            return response()->json([
                'error' => 'Phone number is already taken',
                'status' => 409
            ]);
        }
        
        if ($user->save()) {
            if ($this->loginAfterSignUp) {
                return $this->login($request);
            }
    
            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        
        if (!$jwt_token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
 
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }

    // Log out

    public function logout(Request $request)
    {
        // $this->validate($request, [
        //     'token' => 'required'
        // ]);
 
        try {
            JWTAuth::parseToken()->authenticate();
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
            ], 500);
        }
    }

    // Get authenticated user
    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
            // Authentication logic here
        //
        
        if (!User::find($id)) {
            return response()->json([
                'error' => 'Account not found or does not exist',
                'status' => 403
            ]);
        }

        $user = User::find($id);
        $user->firstName = $request->input('firstName') ? $request->input('firstName') : $user->firstName;
        $user->lastName = $request->input('lastName') ? $request->input('lastName') : $user->lastName;
        $user->email = $request->input('email') ? $request->input('email') : $user->email;
        $user->phoneNumber = $request->input('phoneNumber') ? $request->input('phoneNumber') : $user->phoneNumber;
        $user->password = $request->input('password') ? $request->input('password') : $user->password;

        if (User::where('email', '=', $user->email)->first()) {
            return response()->json([
                'error' => 'An account with this email already exists',
                'status' => 409
            ]);
        }
        
        if (User::where('phoneNumber', '=', $user->phoneNumber)->first()) {
            return response()->json([
                'error' => 'Phone number is already taken',
                'status' => 409
            ]);
        }

        if ($user->save()) {
            $updatedUser = new UserResource($user);
            return response()->json([
                'message' => 'User updated successfully',
                'status' => 201,
                'user' => $updatedUser
            ]);
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if user is logged in
        
            // Authentication logic here
        
        $user = User::find($id); // loginId == $id
        if ($user->delete()) {
            return response()->json([
                'message' => 'Deleted successfully',
                'status' => 200,
                'user' => new UserResource($user)
            ]);
        } else {
            return response()->json([
                'error' => 'You are not authorized to perform this action',
                'status' => 401
            ]);
        }
    }
}
