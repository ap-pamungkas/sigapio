<!-- Modal -->
<div wire:ignore.self class="modal fade modal-post" id="{{ $id }}" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="{{ $id }}" aria-hidden="true">
    <div class="modal-dialog {{ $size ?? '' }} ">
        <form x-on:submit.prevent="{{ $action ? '$wire.' . $action : '' }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content ">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="{{ $id }}">{{ $title ?? 'Modal Header' }}</h1>
                </div>
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="close" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="simpan">

                        <span wire:loading.remove wire:target="simpan">
                            {{ $button ?? 'Simpan' }}
                        </span>

                        <span wire:loading wire:target="simpan">
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
