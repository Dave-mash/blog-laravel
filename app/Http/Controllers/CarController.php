<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

use App\Http\Requests;
use App\Car;
use App\User;
use App\Http\Resources\Car as CarResource;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car = Car::all();
        return CarResource::collection($car);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if (!User::find($id)) {
            return [
                'error' => 'Account not found or does not exist',
                'status' => 403
            ];
        }
        
        // $picture = $request->input('picture');
        $car = new Car;
        $car->vendor_id = $id;
        $car->make = $request->input('make');
        $car->model = $request->input('model');
        $car->color = $request->input('color');
        $car->description = $request->input('description');
        $car->condition = $request->input('condition');
        $car->price = $request->input('price');

        if ($car->save()) {
            $user = User::find($id);
            $user->isAdmin = true;
            $user->save();
            $newCar = new CarResource($car);
            return [
                'message' => 'Car posted successfully',
                'status' => 201,
                'car' => $user
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
        if (!Car::find($id)) {
            return [
                'error' => 'Car was not found or does not exist',
                'status' => 404
            ];
        }
        $car = Car::findOrFail($id);
        return new CarResource($car);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vendorId, $carId)
    {
        if (!Car::find($carId)) {
            return [
                'error' => 'Car does not exist',
                'status' => 404
            ];
        }

        if (!User::find($vendorId)) {
            return [
                'error' => 'Please create an account first',
                'status' => 403
            ];
        }

        $carObj = Car::findOrFail($carId);
        $user = User::findOrFail($vendorId);
        if ($user->id == $carObj->vendor_id) {

            $carObj->make = $request->input('make');
            $carObj->model = $request->input('model');
            $carObj->color = $request->input('color');
            $carObj->description = $request->input('description');
            $carObj->price = $request->input('price');
            $carObj->condition = $request->input('condition');
            $carObj->picture = $request->input('picture');

            if ($carObj->save()) {
                $updatedCar = new CarResource($carObj);
                return [
                    'message' => 'Car updated successfully',
                    'status' => 201,
                    'car' => $updatedCar
                ];
            } else {
                return [
                    'status' => 400
                ];
            }
        } else {
            return [
                'error' => 'You are not authorized to perform this action',
                'status' => 401
            ];
        }
    }
    
    /**
     * View vendor cars.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorCars($vendorId)
    {
        if (!User::find($vendorId)->get()) {
            return [
                'error' => 'Account not found or does not exist',
                'status' => 404
            ];
        }

        $user = User::find($vendorId);

        if ($user->isAdmin == false) {
            return [
                'error' => 'You are not authorized to access this resource',
                'status' => 401
            ];
        }
        $cars = Car::where('vendor_id', '=', $vendorId)->get();
        return CarResource::collection($cars);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($vendorId, $carId)
    {
        if (!Car::find($carId)) {
            return [
                'error' => 'Car does not exist',
                'status' => 404
            ];
        }

        if (!User::find($vendorId)) {
            return [
                'error' => 'Please create an account first',
                'status' => 403
            ];
        }

        $user = User::findOrFail($vendorId);
        $carObj = Car::findOrFail($carId);
        if ($user->id == $carObj->vendor_id) {
            if ($carObj->delete()) {
                return [
                    'message' => 'Deleted successfully',
                    'status' => 200,
                    'car' => new CarResource($carObj)
                ];
            }
        } else {
            return [
                'error' => 'You are not authorized to perform this action',
                'status' => 401
            ];
        }
    }
}
