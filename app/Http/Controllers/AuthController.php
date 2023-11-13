<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function user()
    {
        $user = Auth::user();
        return response([
            'user' => $user
        ]);
    }

    public function checkAuth()
    {
        $user = Auth::user();
        if ($user) {
            return response()->json(['message' => true]);
        } else {
            return response()->json(['message' => false]);
        }
    }

    public function register(Request $request)
    {
        User::create([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'email' => $request->input('email'),
            'tel' => $request->input('tel'),
            'sex' => $request->input('password'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password'))
        ]);
    }


    public function createUser()
    {
        User::create([
            'nom' => 'maach',
            'prenom' => 'Issam',
            'email' => 'a@a.com',
            'tel' => '5672892',
            'sex' => 'M',
            'role' => 'admin',
            'password' => Hash::make('aaaa')
        ]);
        return 'You just created a user with : \r\n email : a@a.com \r\n password : aaaa';
    }


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'invalid login'
            ], Response::HTTP_UNAUTHORIZED);
        }
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = Auth::user();
        $user->pdfCategories;
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'user' => $user,
            'token' => $token,
        ])->withCookie($cookie);
    }


    public function logout()
    {

        $cookie = Cookie::forget('jwt');
        return \response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $currentPassword = $request->input('currentPassword');
        $newPassword = $request->input('newPassword');

        // Verify the current password
        if (!Hash::check($currentPassword, $user->password)) {
            return response([
                'message' => 'Current password is incorrect'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        return response([
            'message' => 'Password changed successfully'
        ]);
    }
}
