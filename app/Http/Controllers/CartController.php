<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Car;
use App\User;
use App\Cart;
use App\Http\Controllers\AuthHelper;
use App\Http\Resources\Cart as CartResource;

class CartController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $userId)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
    
            $userObj = null;
            $user = null;
    
            function error() {
                return response()->json([
                    'error' => 'You are not authorized to access this resource',
                    'status' => 401
                ], 401);
            }
            
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!$user = User::where('id', '=', $userObj->id)->first()) {
                return error();
            } elseif ($userObj->id !== (int)$userId) {
                return error();
            }

            $cart = Cart::where('buyer_id', '=', $userId)->get();
            return CartResource::collection($cart);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId, $carId)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
    
            $userObj = null;
            $user = null;
            $car = null;
    
            function error() {
                return response()->json([
                    'error' => 'You are not authorized to access this resource',
                    'status' => 401
                ], 401);
            }
            
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!$user = User::where('id', '=', $userObj->id)->first()) {
                return error();
            } elseif ($userObj->id !== (int)$userId) {
                return error();
            }

            if (!$car = Car::find($carId)) {
                return [
                    'error' => 'Car was not found or does not exist',
                    'status' => 404
                ];
            }
                
            if (Cart::where('car_id', '=', $carId)->first()) {
                return [
                    'error' => 'This car is currently unavailable',
                    'status' => 409
                ];
            }
    
            $cart = new Cart;
            $cart->buyer_id = (int)$userId;
            $cart->vendor_id = $car->vendor_id;
            $cart->car_id = (int)$carId;
            $cart->save();
    
            $addedCar = new CartResource($cart);
    
            return response()->json([
                'message' => 'Successfully added to cart',
                'status' => 200,
                'cart' => $addedCar
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => 400
            ]);
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId, $cartId)
    {
        if (!Cart::find($cartId)) {
            return [
                'error' => 'Cart does not exist',
                'status' => 404
            ];
        }

        if (!User::find($userId)) {
            return [
                'error' => 'Please create an account first',
                'status' => 403
            ];
        }

        $cartObj = Cart::find($cartId);
        if ($cartObj->delete()) {
            return [
                'message' => 'Cart deleted successfully',
                'status' => 200,
                'cart' => new CartResource($cartObj)
            ];
        } else {
            return [
                'status' => 400
            ];
        }
    }

    /**
     * View vendor cars.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function purchasedCars()
     {
 
     }
}
