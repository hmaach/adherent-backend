<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    // public function index(Request $request)
    // {
    //     $perPage = $request->input('per_page', 25);
    //     $page = $request->input('page', 1) - 1;;
    //     // $sortModel = json_decode($request->input('sortModel', '[]'), true);

    //     $usersQuery = User::query();

    //     // // Handle sorting
    //     // if (is_array($sortModel)) {
    //     //     foreach ($sortModel as $sort) {
    //     //         if (isset($sort['field'])) {
    //     //             $usersQuery->orderBy($sort['field'], $sort['sort']);
    //     //         }
    //     //     }
    //     // }

    //     $users = $usersQuery->paginate($perPage, ['*'], 'page', $page);

    //     return $users;
    // }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1) - 1;
        $role = $request->input('role', "all");
        $query = $request->input('query', "");

        $usersQuery = User::query();

        if ($role && $role !== "all") {
            $usersQuery->where('role', $role);
        }

        if ($query && $query !== "") {
            $usersQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('nom', 'like', "%$query%")
                    ->orWhere('prenom', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%");
            });
        }

        $users = $usersQuery->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return $users;
    }


    public function searchUsers($q)
    {
        $users = User::where(function ($query) use ($q) {
            $query->where('nom', 'like', "%$q%")
                ->orWhere('prenom', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%");
        })
            ->latest('created_at')
            ->get();
        return $users;
    }

    public function changeRole(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'newRole' => ['required', Rule::in(['admin', 'user', 'adherent'])],
            ]);

            $validator->validate();

            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->role = $request->newRole;
            $user->save();

            if ($request->newRole === 'adherent') {
                $adherent = new Adherent();
                $adherent->user_id = $user->id;
                $adherent->save();
            }

            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Échec de la création'], 500);
        }
    }


    public function resetPassword(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->password = bcrypt('123456789');
            $user->save();
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Échec de la création'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $validator->setCustomMessages([
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Doit être une chaîne de caractères.',
                'max' => 'Ne doit pas dépasser :max caractères.',
                'email' => 'L\':attribute doit être valide.',
                'unique' => 'L\':attribute existe déjà !',
                'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
            ]);

            $validator->validate();

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
                DB::rollBack();

                if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                    return response()->json(
                        ['errors' => [['field' => 'email', 'message' => 'L\'adresse e-mail est déjà enregistrée.']]],
                        422
                    );
                } else {
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




    public function registerByAdmin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'role' => ['required', Rule::in(['admin', 'user', 'adherent'])],
                'password' => $request->input('defaultPasword')
                    ? []
                    : ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $validator->setCustomMessages([
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Doit être une chaîne de caractères.',
                'max' => 'Ne doit pas dépasser :max caractères.',
                'email' => 'L\':attribute doit être valide.',
                'unique' => 'L\':attribute existe déjà !',
                'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
                'in' => 'La valeur du champ :attribute doit être l\'une des valeurs suivantes :values.',
            ]);

            $validator->validate();

            DB::beginTransaction();

            try {
                $user = User::create([
                    'nom' => $request->input('nom'),
                    'prenom' => $request->input('prenom'),
                    'email' => $request->input('email'),
                    'role' => $request->input('role'),
                    'password' => $request->input('defaultPasword')
                        ? bcrypt('123456789')
                        : Hash::make($request->input('password')),
                ]);

                DB::commit();

                return response()->json(['user' => $user, 'message' => 'success'], 201);
            } catch (\Exception $e) {
                DB::rollBack();

                if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                    return response()->json(
                        ['errors' => [['field' => 'email', 'message' => 'L\'adresse e-mail est déjà enregistrée.']]],
                        422
                    );
                } else {
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

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        try {
            $validator = Validator::make($request->all(), [
                'nom' => ['string', 'max:255'],
                'prenom' => ['string', 'max:255'],
                'email' => ['string', 'email', 'max:255', 'unique:users'],
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);

            $validator->setCustomMessages([
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Doit être une chaîne de caractères.',
                'max' => 'Ne doit pas dépasser :max caractères.',
                'email' => 'L\':attribute doit être valide.',
                'unique' => 'L\':attribute existe déjà !',
                'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
            ]);

            $validator->validate();

            DB::beginTransaction();

            try {

                if (!$user) {
                    return response()->json(['message' => 'User not found'], 404);
                }
                if ($request->has('nom')) {
                    $user->nom = $request->input('nom');
                }
                if ($request->has('prenom')) {
                    $user->prenom = $request->input('prenom');
                }
                if ($request->has('email')) {
                    $user->email = $request->input('email');
                }
                if ($request->has('password')) {
                    $user->password = Hash::make($request->input('password'));
                }

                $user->save();

                DB::commit();

                return response()->json(['user' => $user, 'message' => 'success'], 201);
            } catch (\Exception $e) {
                DB::rollBack();

                if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                    return response()->json(
                        ['errors' => [['field' => 'email', 'message' => 'L\'adresse e-mail est déjà enregistrée.']]],
                        422
                    );
                } else {
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

    public function destroy(string $id)
    {
        $user = User::find($id);

        try {
            if ($user) {

                $user->delete();
                return response()->json(['message' => "success"]);
            } else {
                return response()->json(['message' => "User not found"], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => "An error occurred while deleting the announce"], 500);
        }
    }
}
