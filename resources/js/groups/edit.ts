$('#server_id').on('change', function() {
    let selectedOption: any = $(this).val();
    if (selectedOption) {
        window.location.href = selectedOption;
    }
});
$(document).ready(function() {
    $('#server_id').select2();
})
