<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List of users (admin only).
     */
    public function index()
    {
        $users = User::with('role')->orderBy('id')->get();
        return UserResource::collection($users);
    }

    /**
     * Show a specific user.
     */
    public function show(User $user): UserResource
    {
        $user->load('role');
        return new UserResource($user);
    }

    /**
     * Update a user.
     */
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $data = $request->validated();

        if (array_key_exists('username', $data)) {
            $data['username'] = $data['username'];
        }
        if (array_key_exists('email', $data)) {
            $data['email'] = mb_strtolower($data['email']);
        }

        $user->update($data);

        return new UserResource($user->fresh()->load('role'));
    }

    /**
     * Deactivate a user (soft delete with status='n').
     */
    public function destroy(UserDestroyRequest $request, User $user): JsonResponse
    {
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->update(['status' => 'n']);
        });

        return response()->json(['message' => 'User deactivated']);
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(UserPasswordUpdateRequest $request): JsonResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return response()->json(['message' => 'Password updated']);
    }
}
