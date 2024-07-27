// Add VIP create functionality here

document.addEventListener('change', function(event) {
    if (event.target.matches('#permanent')) {
        var endsInput = document.getElementById('expires');
        endsInput.disabled = event.target.checked;
        if (event.target.checked) {
            endsInput.value = '';
        }
    }
});
