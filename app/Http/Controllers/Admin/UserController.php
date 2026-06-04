<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $query = User::with(['playlists'])->withCount('playlists');

        // Search: by username or email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Show
    public function show(User $user)
    {
        $user->load('playlists');

        return view('admin.users.show', compact('user'));
    }

    // Edit
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:y,n',
        ]);

        $user->update($data);

        // si el usuario se desactiva, desactivar playlists
        if ($data['status'] === 'n') {
            $user->playlists()->update(['status' => 'n']);
        }
        // si el usuario se activa, activar playlists
        if ($data['status'] === 'y') {
            $user->playlists()->update(['status' => 'y']);
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated');
    }
}
