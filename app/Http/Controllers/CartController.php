<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Car;
use App\User;
use App\Cart;
use App\Http\Resources\Cart as CartResource;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = Cart::all();
        return CartResource::collection($cart);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId, $carId)
    {
        if (!User::find($userId)) {
            return [
                'error' => 'Create an account first',
                'status' => 401
            ];
        }
        
        if (!Car::find($carId)) {
            return [
                'error' => 'Car was not found or does not exist',
                'status' => 404
            ];
        }

        $car = Car::find($carId);
        $cartItems = Cart::all();
        $cartCollection = CartResource::collection($cartItems);

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

        if ($cart->save()) {
            $addedCar = new CartResource($cart);

            return [
                'message' => 'Successfully added to cart',
                'status' => 200,
                'cart' => $addedCar
            ];
        } else {
            return [
                'status' => 400
            ];
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
}
