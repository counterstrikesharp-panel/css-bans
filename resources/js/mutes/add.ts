$(document).ready(function () {
    $('#server_ids').select2({
        placeholder: window.translations.selectServers,
    });
});
document.addEventListener('change', function(event) {
    if (event.target.matches('#permanent')) {
        var endsInput = document.getElementById('duration');
        endsInput.disabled = event.target.checked;
        if (event.target.checked) {
            endsInput.value = '';
        }
    }
});
