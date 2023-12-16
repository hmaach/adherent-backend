<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


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
        try {
            $validator = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
    
            // Customize error messages in French
            $validator->setCustomMessages([
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Doit être une chaîne de caractères.',
                'max' => 'Ne doit pas dépasser :max caractères.',
                'email' => 'L\':attribute doit être valide.',
                'unique' => 'L\':attribute existe déjà !',
                'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
            ]);
    
            $validator->validate();
    
            // Use a database transaction to handle errors related to database operations
            DB::beginTransaction();
    
            try {
                $user = User::create([
                    'nom' => $request->input('nom'),
                    'prenom' => $request->input('prenom'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password'))
                ]);
    
                DB::commit();
    
                return response()->json(['user' => $user, 'message' => 'success'], 201);
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollBack();
    
                if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                    // Unique constraint violation (email already exists)
                    return response()->json(['errors' => [['field' => 'email', 'message' => 'L\'adresse e-mail est déjà enregistrée.']]],
                        422);
                } else {
                    // General error
                    return response()->json(['error' => 'L\'inscription a échoué. Veuillez réessayer.'], 500);
                }
            }
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
    
            $formattedErrors = [];
            foreach ($errors as $field => $error) {
                $formattedErrors[] = [
                    'field' => $field,
                    'message' => $error[0],
                ];
            }
    
            return response()->json(['errors' => $formattedErrors], 422);
        }
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
        $emailExists = User::where('email', $request->email)->exists();

        if (!$emailExists) {
            return response([
                'error' => 'email',
                'message' => "L'email n'existe pas !"
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'error' => 'password',
                'message' => 'Le mot de passe est incorrect !'
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


    // public function login(Request $request)
    // {
    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response([
    //             'message' => 'invalid login'
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }
    //     $credentials = $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $user = Auth::user();
    //     $user->pdfCategories;
    //     $token = $user->createToken('token')->plainTextToken;
    //     $cookie = cookie('jwt', $token, 60 * 24);

    //     return response([
    //         'user' => $user,
    //         'token' => $token,
    //     ])->withCookie($cookie);
    // }




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
