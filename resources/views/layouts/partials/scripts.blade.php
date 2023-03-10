<script src="{{ mix('js/app.js') }}"></script>

<script src="{{ asset('/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('/vendors/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('/vendors/toastify/toastify.js') }}"></script>
<script src="{{ asset('/vendors/apexcharts/apexcharts.js') }}"></script>


<script src="//unpkg.com/alpinejs" defer></script>
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
@livewireScripts
<script src="{{ asset('/js/main.js') }}"></script>
<script src="{{ asset('/js/jquery.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@stack('script')

@stack('target')
@stack('assignment')
@stack('rating')
<script>
    
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            left: 0,
            behavior: "smooth"
        });
    }

    $(window).on('load', function() {
        window.addEventListener('password', event => {
            $('#PasswordModal').modal('show');
        });
    });

    window.addEventListener('toastify', event => {
        Toastify({
            text: event.detail.message,
            duration: 3000,
            close: true,
            gravity:"top",
            position: "center",
            backgroundColor: event.detail.color,
        }).showToast();
    });

    window.addEventListener('close-modal', event => {
        $('#AddOSTModal').modal('hide');
        $('#EditOSTModal').modal('hide');
        $('#DeleteModal').modal('hide');
        $('#AddRatingModal').modal('hide');
        $('#EditRatingModal').modal('hide');
        $('#AddStandardModal').modal('hide');
        $('#EditStandardModal').modal('hide');
        $('#AddTTMAModal').modal('hide');
        $('#EditTTMAModal').modal('hide');
        $('#DeclineModal').modal('hide');
        $('#DoneModal').modal('hide');
        $('#AddOfficeModal').modal('hide');
        $('#EditOfficeModal').modal('hide');
        $('#AddAccountTypeModal').modal('hide');
        $('#EditAccountTypeModal').modal('hide');
        $('#AddDurationModal').modal('hide');
        $('#EditDurationModal').modal('hide');
        $('#AddPercentageModal').modal('hide');
        $('#EditPercentageModal').modal('hide');
        $('#AddTrainingModal').modal('hide');
        $('#EditTrainingModal').modal('hide');
        $('#EditScoreEqModal').modal('hide');
        $('#EditStandardValueModal').modal('hide');
        $('#AddTargetOutputModal').modal('hide');
        $('#EditTargetOutputModal').modal('hide');
    });
</script>

{{ $script ?? ''}}
