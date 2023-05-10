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
        $('#FacultyCorePercentageModal').modal('hide');

        $('#PrintModal').modal('hide');
        $('#AddCommitteeModal').modal('hide');        
        $('#EditCommitteeModal').modal('hide');       
        $('#AddFilesModal').modal('hide');

        $('#AddInstituteModal').modal('hide');        
        $('#EditInstituteModal').modal('hide');  
        $('#EditPrintImageModal').modal('hide'); 
    });

    $('a.dropdown-notification').on('click', function (event) {
        $(this).toggleClass('show');

        $('ul.notification-dropdown').toggleClass('show');
    });
    $('body').on('click', function (e) {
        if (!$('div.notification-dropdown-group').is(e.target) 
            && $('a.dropdown-notification').has(e.target).length === 0 
            && $('.show').has(e.target).length === 0
            && $('ul.notification-dropdown').has(e.target).length === 0 
            && $('.show').has(e.target).length === 0
        ) {
            $('a.dropdown-notification').removeClass('show');
            $('ul.notification-dropdown').removeClass('show');
        }
    });

    
    $('#toggleshow').click(function () {
        if ($(this).hasClass('bi-eye-slash')) {
            $(this).removeClass('bi-eye-slash');
            $(this).addClass('bi-eye');
            $('.password-padding').attr('type', 'text');
        } else {
            $(this).removeClass('bi-eye');
            $(this).addClass('bi-eye-slash');
            $('.password-padding').attr('type', 'password');
        }
    });
    $('#toggleshow1').click(function () {
        if ($(this).hasClass('bi-eye-slash')) {
            $(this).removeClass('bi-eye-slash');
            $(this).addClass('bi-eye');
            $('#current_password').attr('type', 'text');
        } else {
            $(this).removeClass('bi-eye');
            $(this).addClass('bi-eye-slash');
            $('#current_password').attr('type', 'password');
        }
    });
    $('#toggleshow2').click(function () {
        if ($(this).hasClass('bi-eye-slash')) {
            $(this).removeClass('bi-eye-slash');
            $(this).addClass('bi-eye');
            $('#password').attr('type', 'text');
        } else {
            $(this).removeClass('bi-eye');
            $(this).addClass('bi-eye-slash');
            $('#password').attr('type', 'password');
        }
    });
    $('#toggleshow3').click(function () {
        if ($(this).hasClass('bi-eye-slash')) {
            $(this).removeClass('bi-eye-slash');
            $(this).addClass('bi-eye');
            $('#password_confirmation').attr('type', 'text');
        } else {
            $(this).removeClass('bi-eye');
            $(this).addClass('bi-eye-slash');
            $('#password_confirmation').attr('type', 'password');
        }
    });
    $('#toggleshowBrowser').click(function () {
        if ($(this).hasClass('bi-eye-slash')) {
            $(this).removeClass('bi-eye-slash');
            $(this).addClass('bi-eye');
            $('#password_browser').attr('type', 'text');
        } else {
            $(this).removeClass('bi-eye');
            $(this).addClass('bi-eye-slash');
            $('#password_browser').attr('type', 'password');
        }
    });
    $('#toggleshowDelete').click(function () {
        if ($(this).hasClass('bi-eye-slash')) {
            $(this).removeClass('bi-eye-slash');
            $(this).addClass('bi-eye');
            $('#password_delete').attr('type', 'text');
        } else {
            $(this).removeClass('bi-eye');
            $(this).addClass('bi-eye-slash');
            $('#password_delete').attr('type', 'password');
        }
    });
</script>

{{ $script ?? ''}}
