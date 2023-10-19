<div>
    @if($rows->isNotEmpty())
        <div class="card-body p-0">
            <div class="table-responsive" id="data-table-wrap">
                <table class="table table-sm table-striped fs--1 mb-0 vertical-border overflow-x-scroll" id="data-table"
                       style="table-layout: auto; width: 100%;">
                    <thead class="bg-200 text-900">
                    <tr class="">
                        @foreach($colsOrder as $column)
                            @if(in_array($column,$selectedCols))
                                <th class="no-sort pe-1 align-middle white-space-nowrap"
                                    title="{{data_get($columns[$column],'title')}}">{{data_get($columns[$column],'name')}}</th>
                            @endif
                        @endforeach
                        {{--            <th class="align-middle no-sort">Action</th>--}}
                    </tr>
                    </thead>
                    <tbody class="list" id="table-list-body">
                    @foreach($rows as $k=>$a)
                        <tr class="btn-reveal-trigger">
                            @foreach($colsOrder as $column)
                                @if(in_array($column,$selectedCols))
                                    @php($val = data_get($a, $column))
                                    <td class="align-middle py-2">
                                        @if($column=="name")
                                            <span data-bs-trigger="hover focus"
                                                  data-bs-toggle="popover" data-bs-placement="top"
                                                  data-bs-title="Definition"
                                                  data-bs-custom-class="custom-popover"
                                                  data-bs-content='{{data_get($a,'brief')}}'>{{$val}}</span>
                                        @elseif($column=='job')
                                            <span class="cursor-pointer d-inline" data-bs-toggle="modal"
                                                  data-job='{{$val}}'
                                                  data-bs-target="#jobList">{{$val}}</span>
                                        @elseif($column=='brief')
                                            @if($val)
                                                <span data-bs-toggle="modal" class="cursor-default"
                                                      data-xid="{{data_get($a,'id')}}"
                                                      data-bs-target="#noteModal">
                                                    {{$val}}
                                                </span>
                                            @else
                                                <button class="btn btn-falcon-default btn-sm"
                                                        data-xid="{{data_get($a,'id')}}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#noteModal">
                                                    Add brief
                                                </button>
                                            @endif
                                        @else
                                            {{$val}}
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center mt-3">
            <div class="col-auto text-sm text-gray-700 leading-5">
                <span>{!! __('Showing') !!}</span>
                <span class="font-medium">{{ $rows->firstItem() }}</span>
                <span>{!! __('to') !!}</span>
                <span class="font-medium">{{ $rows->lastItem() }}</span>
                <span>{!! __('of') !!}</span>
                <span class="font-medium">{{ $rows->total() }}</span>
                <span>{!! __('results') !!}</span>
            </div>
            {{ $rows->links() }}
            <div style="width: 150px">
                <select class="form-select form-select-sm me-2" aria-label="Default select example"
                        wire:model.live="limit" wire:change="handleLimitChange">
                    <option selected="">Limit</option>
                    <option value="15">15</option>
                    <option value="50">50</option>
                    <option value="200">200</option>
                    <option value="500">500</option>
                </select>
            </div>
        </div>
        <livewire:code/>
        <livewire:update-note/>
    @else
        <div class="text-center">
            <p class="fw-bold fs-1 mt-3">No data found</p>
        </div>
    @endif
    <script type="module">
        document.addEventListener('livewire:init', function () {
            Livewire.on('resetPopover', function () {
                $('.popover').remove();
                $('[data-bs-toggle="popover"]').popover('dispose').popover();
            });
            Livewire.on('localStorageLimitSaved', function (data) {
                setCookie("ee-limit", data, 365 * 10);
            });
        });
        // document.addEventListener('livewire:initialized', () => {
        //     @this.on('localStorageLimitSaved', (data) => {
        //         setCookie("ee-limit", data, 365 * 10);
        //     });
        // });
    </script>
</div>
