<div class="modal fade @if($showNoteModal==1) show d-block @else d-none @endif" id="noteModal" data-keyboard="false"
     tabindex="-1"
     aria-labelledby="noteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="codeListModalLabel">Note</h5>
            </div>
            <div class="modal-body" id="codeListContent">
                <div class="form-group">
                    {{--                            <label for="note">Example textarea</label>--}}
                    @if($currentEmployee)
                        <textarea class="form-control" id="note" cols="30" rows="10"
                                  wire:model="currentEmployee.brief"></textarea>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="saveNote"
                        data-bs-dismiss="modal">Save
                </button>
                <button class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
    <script type="module">
        $("body").on('show.bs.modal', '#noteModal', function (event) {
            var button = event.relatedTarget;
            Livewire.dispatch('updateCurrentEmployee', {xid: $(button).data('xid')});
        }).on('hidden.bs.modal', '#noteModal', function () {
            Livewire.dispatch('updateShowNoteModal');
        }).on('click', '#saveNote', function (e) {
            Livewire.dispatch('saveNote');
        });
        document.addEventListener('livewire:init', function () {
        });
    </script>
</div>
