<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Car;
use App\User;
use App\Cart;
use App\Http\Controllers\AuthHelper;
use App\Http\Resources\Cart as CartResource;

function error() {
    return response()->json([
        'error' => 'You are not authorized to access this resource',
        'status' => 401
    ], 401);
}

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
                
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!User::where('id', '=', $userObj->id)->first()) {
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
                
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!$user = User::where('id', '=', $userObj->id)->first()) {
                return error();
            } elseif ($userObj->id !== (int)$userId) {
                return error();
            }

            // if (Car::where('vendor_id', '=', $userId) {
            //     return response()->json([
            //         'error' => 'you cannot add your cars to cart',
            //         'status' => 400
            //     ], 400);
            // }

            if (!$car = Car::find($carId)) {
                return response()->json([
                    'error' => 'Car was not found or does not exist',
                    'status' => 404
                ], 404);
            }
                
            if (Cart::where('car_id', '=', $carId)->first()) {
                return response()->json([
                    'error' => 'This car is currently unavailable',
                    'status' => 409
                ]);
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
    public function show(Request $request, $userId, $cartId)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
    
            $userObj = null;
            $user = null;
            $cart = null;
            $car = null;
                
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!$user = User::where('id', '=', $userObj->id)->first()) {
                return error();
            } elseif ($userObj->id !== (int)$userId) {
                return error();
            }

            if (!$cart = Cart::where('id', '=', $cartId)->first()) {
                return response()->json([
                    'error' => 'Cart not found',
                    'status' => 404
                ]);
            } elseif (!$car = Car::where('id', '=', $cart->car_id)->first()) {
                return response()->json([
                    'error' => 'Car not found',
                    'status' => 404
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'cart' => $car
            ]);

        } catch (JWTException $exception) {
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
    public function destroy(Request $request, $userId, $cartId)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
    
            $userObj = null;
            $cart = null;
                
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!User::where('id', '=', $userObj->id)->first()) {
                return error();
            } elseif ($userObj->id !== (int)$userId) {
                return error();
            }

            if (!$cart = Cart::where('id', '=', $cartId)->first()) {
                return response()->json([
                    'error' => 'Cart not found',
                    'status' => 404
                ]);
            } elseif (!Car::where('id', '=', $cart->car_id)->first()) {
                return response()->json([
                    'error' => 'Car not found',
                    'status' => 404
                ]);
            }
            $cart->delete();

            return response()->json([
                'message' => 'Cart deleted successfully',
                'status' => 200,
                'cart' => new CartResource($cart)
            ]);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    // Clear cart
    public function clearCart(Request $request, $userId)
    {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
    
            $userObj = null;
                
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!User::where('id', '=', $userObj->id)->first()) {
                return error();
            } elseif ($userObj->id !== (int)$userId) {
                return error();
            }

            $userCart = Cart::where('buyer_id', '=', $userId)->get();

            foreach ($userCart as $car) {
                $car->delete();
            }

            return response()->json([
                'message' => 'Cart was cleared successfully',
                'status' => 200,
                'cart' => $userCart,

            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * View vendor cars.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function checkout(Request $request, $userId)
     {
        try {
            $this->validate($request, [
                'token' => 'required'
            ]);
    
            $userObj = null;
                
            if (!$userObj = JWTAuth::parseToken()->authenticate()) {
                return error();
            } elseif (!User::where('id', '=', $userObj->id)->first()) {
                return error();
            } elseif ($userObj->id !== (int)$userId) {
                return error();
            }

            $userCart = Cart::where('buyer_id', '=', $userId)->get();

            $car = null;
            $readyCart = [];

            // Set purchase property to true
            
            foreach ($userCart as $car) {
                Car::where('id', '=', $car->car_id)->update(['purchased' => true]);
                $carItem = Car::where('id', '=', $car->buyer_id)->get();
                array_push($readyCart, $carItem);
            }

            return response()->json([
                'message' => 'Cart was cleared successfully',
                'status' => 200,
                'cart' => $readyCart,
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => 400
            ]);
        }

     }
}
