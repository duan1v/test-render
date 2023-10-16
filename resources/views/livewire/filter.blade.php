<div>
    <div id="table-list-replace-element" class="d-flex justify-content-center align-items-center">
        <div class="input-group" style="width: 500px">
            <input class="form-control form-control-sm shadow-none"
                   placeholder="search name or brief" wire:keydown.enter="handleSearch"
                   wire:model="search"/>
            <button class="btn btn-sm btn-outline-secondary border-300 hover-border-secondary"
                    wire:click="handleSearch">
                search
            </button>
        </div>
        <button class="btn btn-sm btn-outline-secondary border-300 hover-border-secondary ms-2" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter"><span
                class="shadow-none">Filter</span></button>
        <button class="btn btn-sm btn-outline-secondary border-300 hover-border-secondary ms-2" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasSelCol" aria-controls="offcanvasSelCol"><span
                class="shadow-none myOffcanvas">Columns</span></button>
    </div>
    <div data-tab="filter" class="offcanvas myOffcanvas offcanvas-end @if($tab=='filter') show @endif"
         id="offcanvasFilter" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
         aria-labelledby="offcanvasFilterLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Filter</h5>
            <button class="btn-close text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <hr class="row"/>
            <form action="#" id="filter-form">

            </form>
        </div>
    </div>
    <div data-tab="table-columns" class="offcanvas myOffcanvas offcanvas-end @if($tab=='table-columns') show @endif"
         id="offcanvasSelCol" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
         aria-labelledby="offcanvasSelColLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Select columns</h5>
            <button class="btn-close text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="card-body">
                <div id="cols-list" style="height: 76vh; overflow-y: scroll;">
                    @foreach($colsOrder as $cid=>$column)
                        <div class="form-check cols-item" wire:key="item-{{ $loop->index }}"
                             data-col="{{$column}}">
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
                        wire:click="handleUpdateSelectedCols">
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
        const sortableList = document.getElementById('cols-list');
        new Sortable(sortableList, {
            onEnd: function (event) {
                const items = Array.from(event.item.parentNode.children);
                const itemTexts = items.map(item => item.dataset.col);
                let data = Array.from(new Set(itemTexts));
                console.log(data);
                Livewire.dispatch('updateColsOrder', {list: data});
            }
        });

        $("body").on('show.bs.offcanvas', '.myOffcanvas', function () {
            Livewire.dispatch('changeFilterTab', {tab: $(this).data('tab')});
        }).on('hidden.bs.offcanvas', '.myOffcanvas', function () {
            Livewire.dispatch('changeFilterTab', {tab: "none"});
        });

        document.addEventListener('livewire:init', function () {
            Livewire.on('localStorageSelectedColsSaved', function (data) {
                console.log(data)
                setCookie("selectedColsEe", JSON.stringify(...data), 365 * 10);
            });
            Livewire.on('localStorageColsOrderSaved', function (data) {
                setCookie("colsOrderEe", JSON.stringify(...data), 365 * 10);
            });
        });
    </script>
</div>
