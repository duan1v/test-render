<div>
    <div>
        <input type="text"  wire:model.live="message">
        <p>你输入的消息是: {{ $message }}</p>
    </div>
    <div x-data="{ message: @entangle('message').live }">
        <input type="text" x-model="message">
        <p>你输入的消息是: {{ $message }}</p>
    </div>
    <div>
        <input type="text" wire:model.live="message" wire:ignore>
        <p>你输入的消息是: {{ $message }}</p>
    </div>
    <x-inputs.text wire:model.defer="foo" wire:loading.class="opacity-25"/>
    <div x-data="{ open: @entangle('showDropdown').live }">
        $showDropdown:{{$showDropdown}}
        <div x-text="open"></div>
        <button @click="open = true">Show More...</button>

        <ul x-show="open" @click.outside="open = false">
            <li><button wire:click="archive">Archive</button></li>
            <li><button wire:click="delete">Delete</button></li>
        </ul>
    </div>
</div>
