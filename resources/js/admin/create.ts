$(document).ready(function() {
    $('#server_id').select2({
        placeholder: 'Select Servers',
    });
    $('#group_id').select2({
        placeholder: 'Select Groups',
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

    $('#flagsPermission').on('click' ,function(){
        $(".flags").show();
        $(".groups").hide();
        $("#group_id").attr('disabled', 'true');

    });
    $('#groups').on('click' ,function(){
        $(".flags").hide();
        $(".groups").show();
        $("#group_id").removeAttr('disabled');
        let checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    });
})
