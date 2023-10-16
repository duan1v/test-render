<x-app-layout>
    <script type="module" src="{{asset("js/Sortable.min.js")}}"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Employee
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8 d-flex justify-content-end align-items-center mb-3">
            <div class="p-6 text-gray-900">
                <livewire:filter/>
            </div>
        </div>
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <livewire:employee/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
