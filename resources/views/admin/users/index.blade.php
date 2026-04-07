@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Barra de navegación -->
    <nav class="bg-[#27313D] text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Musisense - Admin</h1>
        <div>
            <a href="{{ route('admin.users.index') }}" class="hover:text-[#3A6699]">Usuarios</a>
            <!-- Aquí puedes añadir enlaces a otros módulos -->
        </div>
    </nav>

    <div class="p-6">
        <h2 class="text-2xl font-semibold text-[#3A6699] mb-4">Listado de usuarios</h2>

        <!-- Filtros de búsqueda -->
        <form method="GET" class="mb-4 flex gap-4">
            <input type="text" name="q" placeholder="Buscar..." value="{{ $search }}" class="border rounded px-3 py-2 w-1/3">
            <select name="role" class="border rounded px-3 py-2">
                <option value="all">Todos los roles</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}" {{ $role === $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                @endforeach
            </select>
            <select name="status" class="border rounded px-3 py-2">
                <option value="all">Todos</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Activo</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactivo</option>
            </select>
            <button type="submit" class="bg-[#3A6699] text-white px-4 py-2 rounded hover:bg-[#274f7f]">Filtrar</button>
        </form>

        <!-- Tabla de usuarios -->
        <table class="min-w-full border border-gray-200">
            <thead class="bg-[#3A6699] text-white">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Username</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $user->id }}</td>
                    <td class="px-4 py-2 text-[#3A6699] font-semibold">{{ $user->username }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->role->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        @if($user->active)
                            <span class="text-green-600 font-semibold">Activo</span>
                        @else
                            <span class="text-red-600 font-semibold">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-[#3A6699] text-white px-3 py-1 rounded hover:bg-[#274f7f]">Editar</a>
                        @if($user->active)
                        <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Desactivar</button>
                        </form>
                        @else
                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Activar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection