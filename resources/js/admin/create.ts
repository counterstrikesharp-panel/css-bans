$(document).ready(function() {
    $('#server_id').select2({
        placeholder: 'Select Servers',
    });
    document.addEventListener('change', function(event) {
        if (event.target.matches('#permanent')) {
            var endsInput = document.getElementById('ends');
            endsInput.disabled = event.target.checked;
            if (event.target.checked) {
                endsInput.value = '';
            }
        }
    });
})
