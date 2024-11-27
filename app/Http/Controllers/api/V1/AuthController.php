<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all(), "status" => false], 400);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $credentials = $request->only('email', 'password');
        return response()->json([
            'message' => 'User created',
            'token'   => JWTAuth::attempt($credentials),
            'user'    => $user,
            'status'  => true,
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator   = Validator::make($credentials, [
            'email'    => 'required|email',
            'password' => 'required|string|min:6|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                return response()->json([
                    'message' => 'Login failed',
                    'status'  => false,
                ], 401);
            }
        } catch (JWTException $e) {

            return response()->json([
                'message' => 'Error',
            ], 500);
        }

        return response()->json([
            'token'  => $token,
            'user'   => Auth::user(),
            'status' => true,
        ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'User disconnected',
            ]);
        } catch (JWTException $exception) {

            return response()->json([
                'success' => false,
                'message' => 'Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUser(Request $request)
    {
        if (!$request->user) {
            return response()->json([
                'message' => 'Invalid token / token expired',
            ], 401);
        }

        return response()->json(['user' => $request->user]);
    }
}
