<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
   document.addEventListener('DOMContentLoaded', function() {
        Echo.private('notify.{{ auth()->user()->id }}').listen('RegistrationNotificationEvent', (e) => {
            toastr.success(e.data.name + ' ' + e.data.message);
        });
    });
</script>