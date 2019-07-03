<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
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
    public function store(Request $request)
    {
        $user = new User;
        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->email = $request->input('email');
        $user->phoneNumber = $request->input('phoneNumber');
        $user->password = $request->input('password');
        
        if (User::where('email', '=', $user->email)->first()) {
            return [
                'error' => 'An account with this email already exists',
                'status' => 409
            ];
        }

        if (User::where('phoneNumber', '=', $user->phoneNumber)->first()) {
            return [
                'error' => 'Phone number is already taken',
                'status' => 409
            ];
        }

        if ($user->save()) {
            $newUser = new UserResource($user);

            return [
                'message' => 'You have been successfully registered',
                'status' => 201,
                'user' => $newUser
            ];
        } else {
            return [
                'status' => 400
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, $id)
    {
        //
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
            return [
                'error' => 'Account not found or does not exist',
                'status' => 403
            ];
        }

        $user = User::find($id);
        $user->firstName = $request->input('firstName') ? $request->input('firstName') : $user->firstName;
        $user->lastName = $request->input('lastName') ? $request->input('lastName') : $user->lastName;
        $user->email = $request->input('email') ? $request->input('email') : $user->email;
        $user->phoneNumber = $request->input('phoneNumber') ? $request->input('phoneNumber') : $user->phoneNumber;
        $user->password = $request->input('password') ? $request->input('password') : $user->password;

        if (User::where('email', '=', $user->email)->first()) {
            return [
                'error' => 'An account with this email already exists',
                'status' => 409
            ];
        }
        
        if (User::where('phoneNumber', '=', $user->phoneNumber)->first()) {
            return [
                'error' => 'Phone number is already taken',
                'status' => 409
            ];
        }

        if ($user->save()) {
            $updatedUser = new UserResource($user);
            return [
                'message' => 'User updated successfully',
                'status' => 201,
                'user' => $updatedUser
            ];
        } else {
            return [
                'status' => 400
            ];
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
            return [
                'message' => 'Deleted successfully',
                'status' => 200,
                'car' => new UserResource($user)
            ];
        } else {
            return [
                'error' => 'You are not authorized to perform this action',
                'status' => 401
            ];
        }
    }
}
