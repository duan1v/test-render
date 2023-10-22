<div>
    <div x-data="{ step1: @entangle('step1'), step2: @entangle('step2') }">
        <div class="d-flex justify-content-start align-items-center mb-3">
            <button class="btn btn-info me-3" @click="$wire.handStep1;step1 = true">Go step1</button>
            <button class="btn btn-info" @click="step2 = true">Go step2</button>
        </div>
        <div class="d-flex justify-content-start align-items-center mb-3">
            <label class="form-label col-1" for="tableName">Table name: </label>
            <div class="col-5">
                <input class="form-control form-input" wire:model.live="tableName" id="tableName"/>
            </div>
        </div>
        <div class="d-flex justify-content-start align-items-center mb-3">
            <label class="form-label col-1" for="fields">Fields: </label>
            <div class="col-5">
                <textarea class="form-control form-textarea" name="fields" id="" cols="30" rows="10" wire:model.live="fields"></textarea>
            </div>
        </div>

        <div x-show="step1" class="mb-3">
            @if($step1Data)
                @foreach($step1Data as $sk=>$step1Datum)
                    <livewire:field-item :$step1Datum :$sk :$fieldTypes  wire:key="{{$sk}}"/>
                @endforeach
            @endif
        </div>
        <div x-show="step2">
            <button class="btn btn-info" wire:click="handStep2">Gen</button>
        </div>
    </div>
</div>
