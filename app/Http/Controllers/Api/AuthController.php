<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:32'],
            'lastname'  => ['required', 'string', 'max:32'],
            'email'     => ['required', 'email', 'unique:customers,email'],
            'telephone' => ['required', 'string', 'max:32'],
            'password'  => ['required', 'string', 'min:8'],
        ]);

        $customer = Customer::create([
            'firstname'         => $request->firstname,
            'lastname'          => $request->lastname,
            'email'             => $request->email,
            'telephone'         => $request->telephone,
            'password'          => Hash::make($request->password),
            'customer_group_id' => 1,
            'status'            => true,
            'safe'              => false,
        ]);

        $token = $customer->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'token'   => $token,
            'customer'=> $customer->only('customer_id', 'firstname', 'lastname', 'email'),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $customer = Customer::where('email', $request->email)->where('status', true)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $customer->createToken('api')->plainTextToken;

        return response()->json([
            'token'    => $token,
            'customer' => $customer->only('customer_id', 'firstname', 'lastname', 'email'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->only(
            'customer_id', 'firstname', 'lastname', 'email', 'telephone', 'newsletter', 'date_added'
        ));
    }
}
