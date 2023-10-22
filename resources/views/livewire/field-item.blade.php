<div id="table-list-replace-element" class="d-flex justify-content-start align-items-center mb-2">
    <div class="input-group ms-2" style="width: 200px">
        <input class="form-control form-control-sm shadow-none" type="text" wire:model.live="step1Datum.name"
               wire:change="handleChangeDatum"/>
    </div>
    <div class="input-group ms-2" style="width: 200px">
        <select class="form-control form-select-sm form-select shadow-none" id="group"
                style="width: 200px" wire:model.live="step1Datum.type" wire:change="handleChangeDatum">
            @if($fieldTypes)
                @foreach($fieldTypes as $fk=>$fieldType)
                    <option value="{{$fk}}" @if($fk==$step1Datum->type) selected @endif >{{$fieldType}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input-group ms-2" style="width: 200px">
        <input class="form-control form-control-sm shadow-none" type="text" wire:model.live="step1Datum.comment"
               wire:change="handleChangeDatum"/>
    </div>
</div>
