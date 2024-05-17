$('#server_id').on('change', function() {
    let selectedOption: any = $(this).val();
    if (selectedOption) {
        window.location.href = selectedOption;
    }
});
$(document).ready(function() {
    $('#server_id').select2();
    $("#group_id").select2();
})
document.addEventListener('change', function(event) {
    if (event.target.matches('#permanent')) {
        var endsInput = document.getElementById('ends');
        endsInput.disabled = event.target.checked;
        if (event.target.checked) {
            endsInput.value = '';
        }
    }
});
