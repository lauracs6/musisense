<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $isApiRequest = $request->is('api/*');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($isApiRequest) {
            $rules['lastname'] = ['required', 'string', 'max:255'];
            $rules['dni'] = ['required', 'string', 'max:255', 'unique:'.User::class];
            $rules['phone'] = ['required', 'string', 'max:255'];
        }

        $data = $request->validate($rules);

        if ($isApiRequest) {
            $data['lastname'] = mb_strtoupper($data['lastname']);
            $data['dni'] = mb_strtoupper($data['dni']);
        }

        $defaultRoleId = Role::where('name', 'listener')->value('id');

        if (! $defaultRoleId) {
            return response()->json([
                'message' => 'No existe el rol por defecto "listener". Ejecuta los seeders de roles.',
            ], 500);
        }

        $user = User::create([
            'name' => $data['username'],            
            'email' => $data['email'],            
            'password' => Hash::make($data['password']),
            'role_id' => $defaultRoleId,
        ]);

        event(new Registered($user));

        if ($isApiRequest || $request->expectsJson()) {
            $token = $user->createToken('api')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => new UserResource($user->load('role')),
            ], 201);
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
