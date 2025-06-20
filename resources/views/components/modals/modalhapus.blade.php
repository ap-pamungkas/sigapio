<!-- Modal -->
<div wire:ignore.self wire:click="close" wire:key="{{ $id }}" class="modal fade modal-post-delete" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered animate__animated animate__fadeInDown">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-3 me-2"></i>
                <h5 class="modal-title m-0">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <p class="fs-5 mb-1">Apakah Anda yakin ingin menghapus data ini?</p>
                <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
            </div>

            <div class="modal-footer justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <button style="z-index: 999 !important" class="btn btn-danger px-4" wire:click="{{ $click }}">
                    Tetap Hapus
                </button>
            </div>
        </div>
    </div>
</div>
