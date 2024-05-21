<!-- JAVASCRIPT -->
{{-- <script src="{{ asset('build/js/alpine.js') }}"></script> --}}
<script src="{{ asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
{{-- <script src="{{ asset('build/js/plugins.js') }}"></script> --}}
<script src="{{ asset('build/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('build/libs/selectize/selectize.js') }}"></script>
<script src="{{ asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('build/libs/flatpickr/l10n/id.js') }}"></script>
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script> --}}
<script src="{{ asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script src="{{ asset('build/js/mine.js') }}"></script>
<script src="{{ asset('build/js/app.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".select2").selectize();
        $(".multi-select2").selectize({
            maxItems: null
        });
        $('#wanna-swal').on('click', function() {
            var $btn = $(this);
            var title = $btn.data('sw-title') || 'Apakah Anda yakin?';
            var message = $btn.data('sw-message') || 'Anda tidak akan dapat membatalkan aksi ini.';
            var type = $btn.data('sw-type') || 'warning';
            var href = $btn.data('sw-href') || '{{ route('dashboard.index') }}';
            var yesBtn = $btn.data('sw-yes-btn') || 'Ya, Saya yakin';
            var cancelBtn = $btn.data('sw-cancel-btn') || 'Batalkan';

            Swal.fire({
                title: title,
                text: message,
                icon: type, // 'warning', 'error', 'success', 'info'
                buttons: true,
				showCancelButton: true,
				confirmButtonText: yesBtn,
                dangerMode: type === 'danger',
				showCloseButton: true
            }).then((willConfirm) => {
				if (willConfirm.isConfirmed) {
               		window.location.href = href;
				}
            });
        });
    });

    $(function() {
        // wisiwig
        richs = document.querySelectorAll('.rich-editor');
        richs.forEach(element => {
            ClassicEditor.create(element);
        });

        flatpickr.localize(flatpickr.l10ns.id);
        $(".datepicker").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
        });

        $('.nominal').on('keyup', function(event) {
            applyThousandSeparator($(this));
        });

        // datepicker
        // loadDatePicker('.datetimepicker');
        // loadDatePicker('.datepicker');
    });
</script>
@stack('script')
@yield('script')
