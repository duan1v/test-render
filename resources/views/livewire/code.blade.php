<div class="modal fade @if($showCodeList==1) show d-block @else d-none @endif" id="codeList"
     data-keyboard="false" tabindex="-1"
     aria-labelledby="codeListLabel" style="--falcon-modal-width: 80%;"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="codeListModalLabel">{{$codeTag}}</h5>
                <div>
                    <select class="form-select form-select-sm me-2" aria-label="" id="code-book-sel"
                            wire:model.live="bookId" wire:change="handleBookIdChange">
                        <option value="0">All</option>
                        @foreach($allBooks as $cb)
                            <option value="{{$cb->id}}">{{$cb->book_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-body" id="codeListContent" style="height: 80vh;overflow-y: scroll">
                @if($codeTables)
                    @foreach($codeTables as $codeTable)
                        <h5>Code Book: {{data_get($codeTable,'book.book_name')}}</h5>
                        <table style="width: 100%;"
                               class="table table-sm table-striped fs--1 mb-0 overflow-hidden mb-3">
                            <tr>
                                <th>Code</th>
                                <th>Systeemomschrijving</th>
                                <th>Verklaring / wettelijke omschrijving</th>
                                <th>Bijzonderheden</th>
                            </tr>
                            @foreach(data_get($codeTable,'codes') as $code)
                                <tr>
                                    <td>{{data_get($code,'code')}}</td>
                                    <td>{{data_get($code,'sys_desc')}}</td>
                                    <td>{{data_get($code,'declaration')}}</td>
                                    <td>{{data_get($code,'note')}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">{{trans('lan.close')}}</button>
            </div>
        </div>
    </div>
    <script type="module">
        $("body").on('show.bs.modal', '#codeList', function (event) {
            var button = event.relatedTarget;
            Livewire.dispatch('updateCodeTag', $(button).data('code-tag'));
        }).on('hidden.bs.modal', '#codeList', function () {
            Livewire.dispatch('updatesShowCodeList');
        });
        document.addEventListener('livewire:init', function () {
            window.livewire.on('localStorageBookIdSaved', function (data) {
                setCookie("book-id", $("#code-book-sel").val(), 365 * 10);
            });
        });
    </script>
</div>
