<div class="modal fade @if($showJobList==1) show d-block @else d-none @endif" id="jobList"
     data-keyboard="false" tabindex="-1"
     aria-labelledby="codeListLabel" style="--falcon-modal-width: 80%;"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="codeListModalLabel">{{$job}}</h5>
                <div>
                    <select class="form-select form-select-sm me-2" aria-label="" id="num-sel"
                            wire:model.live="num" wire:change="handleNumChange">
                        <option value="0">All</option>
                        @foreach($nums as $k=>$cb)
                            <option value="{{$k}}">{{$cb}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-body" id="codeListContent" style="height: 80vh;overflow-y: scroll">
                <table style="width: 100%;" class="table table-sm table-striped fs--1 mb-0 overflow-hidden mb-3">
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>age</th>
                        <th>email</th>
                        <th>type</th>
                    </tr>
                    @if($ems)
                        @foreach($ems as $em)
                            <tr>
                                <td>{{data_get($em,'id')}}</td>
                                <td>{{data_get($em,'name')}}</td>
                                <td>{{data_get($em,'age')}}</td>
                                <td>{{data_get($em,'email')}}</td>
                                <td>{{data_get($em,'type')}}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">{{trans('lan.close')}}</button>
            </div>
        </div>
    </div>
    <script type="module">
        $("body").on('show.bs.modal', '#jobList', function (event) {
            var button = event.relatedTarget;
            Livewire.dispatch('updateJob', {job: $(button).data('job')});
        }).on('hidden.bs.modal', '#jobList', function () {
            Livewire.dispatch('closeJobList');
        });
        document.addEventListener('livewire:init', function () {
            Livewire.on('localStorageNumSaved', function (data) {
                setCookie("ee-num", $("#num-sel").val(), 365 * 10);
            });
        });
    </script>
</div>
