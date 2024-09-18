<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a
                    href="{{ url('/medidor') }}"
                    class="rounded-md px-3 py-2 text-black ring-1  transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black dark:hover:text-black/80 dark:focus-visible:ring-white"
                >
                    Medidor
                </a>
                    {{-- {{ __("You're logged in!") }} --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
