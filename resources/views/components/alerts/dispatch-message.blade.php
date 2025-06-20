@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session()->has('status'))
   <script>
    window.addEventListener('alert', event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.type === 'success' ? 'Success' : 'Error',
            text: event.detail.message,
            showConfirmButton: false,
            timer: event.detail.timer
        });
    });
</script>
@endif
@endpush