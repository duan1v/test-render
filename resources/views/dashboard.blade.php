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
                    {{ __("You're logged in!") }}
                </div>
            </div>
{{--            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">--}}
{{--                <div class="p-6 text-gray-900">--}}
{{--                    <livewire:search/>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit.prevent="schedule">
                        <label for="title">Event Title</label>
                        <input wire:model="title" id="title" type="text">

                        <label for="date">Event Date</label>
                        <x-date-picker wire:model="date" id="date"/>

                        <button>Schedule Event</button>
                    </form>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div x-init="date = new Date()"  x-effect="console.log('date is '+date)">
                    </div>

                    <div x-data="{ count: 0 }">
                        <button x-on:click="count++">Increment</button>
                        <span x-text="count"></span>
                        <div x-effect="console.log('Count is '+count)"></div>
                        <input type="text" x-ref="content">
                        <button x-on:click="navigator.clipboard.writeText($refs.content.value)">
                            Copy
                        </button>
                        <button x-on:click="navigator.clipboard.writeText(count)">
                            Copy1
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-white  min-h-screen shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <x:dropdown align="right" width="48" contentClasses="py-1 bg-white">
                            <x-slot name="trigger">
                                <!-- 触发器内容 -->
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-md">下拉菜单</button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- 下拉内容 -->
                                <ul class="border border-gray-300 bg-white">
                                    <li class="px-4 py-2 hover:bg-gray-100">选项 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100">选项 2</li>
                                    <li class="px-4 py-2 hover:bg-gray-100">选项 3</li>
                                </ul>
                            </x-slot>
                        </x:dropdown>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
