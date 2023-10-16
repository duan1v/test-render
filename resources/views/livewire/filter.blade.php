<div>
    <div id="table-list-replace-element" class="d-flex justify-content-center align-items-center">
        <div class="input-group" style="width: 500px">
            <input class="form-control form-control-sm shadow-none search" type="search"
                   name="attribute_name" wire:keydown.enter="$dispatch('search',{ search: '{{$search}}' })"
                   placeholder="{{trans('lan.search')}} attribute name" aria-label="search"
                   wire:model.live="search"/>
            <button class="btn btn-sm btn-outline-secondary border-300 hover-border-secondary"
                    wire:click="$dispatch('search','{{$search}}')"
                    type="submit">
                <span class="fs--1">Search</span>
            </button>
        </div>
        <button class="btn btn-sm btn-outline-secondary border-300 hover-border-secondary ms-2" type="button"
                wire:click="$dispatch('changeFilterTab','filter')"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter"><span
                class="shadow-none">Filter</button>
        <button class="btn btn-sm btn-outline-secondary border-300 hover-border-secondary ms-2" type="button"
                wire:click="$dispatch('changeFilterTab','table-columns')"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasSelCol" aria-controls="offcanvasSelCol"><span
                class="shadow-none">Columns</span></button>
    </div>
    <div class="offcanvas offcanvas-end @if($tab=='filter') show @endif" id="offcanvasFilter" data-bs-scroll="true"
         data-bs-backdrop="false" tabindex="-1" aria-labelledby="offcanvasFilterLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Filter</h5>
            <button class="btn-close text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-3 mt-n2">
                <div class="input-group">
                    <input class="form-control form-control-sm shadow-none search" type="search"
                           name="attribute_name" wire:keydown.enter="$dispatch('search','{{$search}}')"
                           placeholder="{{trans('lan.search')}} attribute name" aria-label="search"
                           wire:model.live="search"/>
                    <button class="btn btn-sm btn-outline-secondary border-300 hover-border-secondary"
                            wire:click="$dispatch('search','{{$search}}')"
                            type="submit">
                        <span class="fs--1">Search</span>
                    </button>
                </div>
            </div>
            <hr class="row"/>
            <form action="#" id="filter-form">

            </form>
        </div>
    </div>
    <div class="offcanvas offcanvas-end @if($tab=='table-columns') show @endif" id="offcanvasSelCol"
         data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" aria-labelledby="offcanvasSelColLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Select columns</h5>
            <button class="btn-close text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="card-body">
                <div id="cols-list" style="height: 76vh; overflow-y: scroll;">
                    @foreach($colsOrder as $cid=>$column)
                        <div class="form-check cols-item" wire:key="item-{{ $loop->index }}"
                             data-col-id="{{$column}}">
                            <input class="form-check-input" id="{{$column}}" type="checkbox" name="selectedCols[]"
                                   wire:click="changeSelectedCols('{{$column}}')"
                                   @if(in_array($column,$selectedCols)) checked @endif
                                   value="{{$column}}">
                            <label class="form-check-label"
                                   for="{{$column}}">
                                {{data_get($columns,$column.'.name')}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer border-top border-200 py-x1 d-flex justify-content-around">
                <button class="btn btn-primary w-40" id="update-cols"
                        wire:click="$dispatch('updateCols','{{json_encode($selectedCols)}}')">
                    Update
                </button>
                <button class="btn btn-primary w-40" id="reset-cols"
                        wire:click="$dispatch('resetCols')">
                    Reset
                </button>
            </div>
        </div>
    </div>
    <script type="module">
        const sortableList = new window.Draggable.Sortable(document.getElementById('cols-list'), {
            draggable: 'div.cols-item',
            handle: 'div',
        });

        sortableList.on('sortable:stop', (event) => {
            const sortedItems = Array.from(document.getElementsByClassName("cols-item")).map(item => item.getAttribute('data-col-id').trim());
            // console.log(Array.from(new Set(sortedItems)));
            let data = Array.from(new Set(sortedItems));
            window.livewire.dispatch('updateColsOrder', data);
            setCookie("colsOrderEe", JSON.stringify(data), 365 * 10);
        });

        document.addEventListener('livewire:init', function () {
            window.livewire.on('localStorageSelectedColsSaved', function (data) {
                setCookie("selectedColsEe", data, 365 * 10);
            });
            window.livewire.on('localStorageLimitSaved', function (data) {
                setCookie("ee-limit", data, 365 * 10);
            });
        });
    </script>
</div>
