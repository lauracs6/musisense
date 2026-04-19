<x-app-layout>
    <x-slot name="header">
        <h2 class="p-6 text-indigo-800 text-center font-bold text-3xl animate-pulse">
            {{ __("Welcome to Musisense") }}
        </h2>
    </x-slot>

    <div class="py-24 bg-white">        
        <img class="animate-bounce mx-auto w-1/2 md:w-1/3 lg:w-1/4"
                src="{{ asset('images/MS.png') }}"
                alt="Welcome to Musisense">                
        </img>        
    </div>
</x-app-layout>
