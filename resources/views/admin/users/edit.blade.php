<x-app-layout>
    <div class="min-h-screen p-6 bg-white text-gray-800">

        <div class="max-w-xl mx-auto border p-6 rounded-2xl hover:shadow-md hover:shadow-indigo-800">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Edit User</h1>

                <a href="{{ route('admin.users.show', $user) }}"
                   class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                    Back
                </a>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm">Username</label>
                    <input type="text" name="username" value="{{ $user->username }}"
                        class="w-full border rounded p-2 mt-1">
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="w-full border rounded p-2 mt-1">
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Status</label>
                    <select name="status" class="w-full border rounded p-2 mt-1">
                        <option value="y" {{ $user->status === 'y' ? 'selected' : '' }}>Active</option>
                        <option value="n" {{ $user->status === 'n' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>               

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">
                        Cancel
                    </a>
                    <button class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                        Save
                    </button>
                </div>
            </form>

        </div>

    </div>
</x-app-layout>