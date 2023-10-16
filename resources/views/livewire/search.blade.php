<div>
    <div>
        <input wire:model.live="message" type="text">

        <h1>{{ $message }}</h1>
    </div>

    <div class="relative w-96">
        <input type="text" wire:model.live="search" class="w-full rounded-md mb-3"
               placeholder="Search employees by name..."/>
        <div class="shadow bg-white mt-2 top-100 z-40 w-full rounded p-2">
            <div class="w-full">
                @foreach ($employees as $employee)
                    <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-teal-100">
                        <p>
                            {{ $employee->name }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="px-2 w-10">
        <svg wire:loading wire:target="search" class="animate-spin -ml-1 mr-3 h-6 w-6 text-pink-500"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>

