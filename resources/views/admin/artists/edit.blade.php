<x-app-layout>
    <x-slot name="header">
        Edit Artist
    </x-slot>

    <div class="max-w-xl mx-auto bg-white p-6 border-l-4 border-indigo-500 shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Edit Artist</h2>
            <a href="{{ route('admin.artists.index') }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Back</a>
        </div>

        <form method="POST" action="{{ route('admin.artists.update', $artist) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', $artist->name) }}" class="w-full border rounded p-2 mt-1">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full border rounded p-2 mt-1">
                    <option value="y" @selected($artist->status=='y')>Active</option>
                    <option value="n" @selected($artist->status=='n')>Inactive</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.artists.index') }}" class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-gray-300 px-4 py-2 rounded-xl">Cancel</a>
                <button class="bg-white text-gray-600 hover:bg-gray-100 hover:text-gray-400 border-2 border-indigo-500 px-4 py-2 rounded-xl">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
