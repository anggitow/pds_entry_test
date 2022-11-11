<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('email', $username)->first();

        // if user not exist
        if (!$user) {
            return [
                'status' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        $hashed = $user->password;

        if (Hash::check($password, $hashed)) {
            $jwtToken = $this->jwtService->generate($user);

            User::where('email', $username)
                ->update([
                    'token' => $jwtToken,
                    'expired_token' => date("Y-m-d H:i:s", strtotime('+2 hours'))
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $jwtToken,
                    'expired_token' => date("Y-m-d H:i:s", strtotime('+2 hours'))
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'username atau password salah',
                'data' => null
            ]);
        }
    }
}
