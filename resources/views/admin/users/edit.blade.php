<x-app-layout>
    <x-slot name="header">
        Edit User
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 border-l-4 border-indigo-500 shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Edit User</h2>

            <a href="{{ route('admin.users.show', $user) }}"
               class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <!-- Username -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Username</label>
                <input type="text" name="username"
                    value="{{ old('username', $user->username) }}"
                    class="w-full border rounded p-2 mt-1">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full border rounded p-2 mt-1">
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full border rounded p-2 mt-1">
                    <option value="y" {{ $user->status === 'y' ? 'selected' : '' }}>Active</option>
                    <option value="n" {{ $user->status === 'n' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">
                    Cancel
                </a>

                <button type="submit"
                    class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-indigo-500 px-4 py-2 rounded-xl">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-app-layout>