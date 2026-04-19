<x-app-layout>
    <div class="min-h-screen p-6 bg-white text-gray-800">

        <div class="max-w-xl mx-auto hover:shadow-md hover:shadow-indigo-800 p-6 rounded-2xl shadow">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold mb-6">
                    Edit Genre
                </h1>
                <a href="{{ route('admin.genres.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded text-sm">
                    Back
                </a>
            </div>

            <form method="POST" action="{{ route('admin.genres.update', $genre) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-sm">
                        Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $genre->name) }}"
                        class="w-full border rounded p-2 mt-1"
                    >
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm">
                        Status
                    </label>
                    <select
                        name="status"
                        class="w-full border rounded p-2 mt-1"
                    >
                        <option value="y" {{ old('status', $genre->status) === 'y' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="n" {{ old('status', $genre->status) === 'n' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2">

                    <a href="{{ route('admin.genres.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded text-sm">
                        Cancel
                    </a>

                    <button type="submit"
                        class="bg-indigo-700 text-white px-4 py-2 rounded-lg hover:bg-indigo-800">
                        Save
                    </button>

                </div>

            </form>

        </div>

    </div>
</x-app-layout>