$(document).ready(function() {
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var $state = $(
            '<span><img src="' + $(state.element).data('image') + '" class="flag-width" alt="flag" /> ' + state.text + '</span>'
        );
        return $state;
    }

    $('#language-dropdown').select2({
        templateResult: formatState,
        templateSelection: formatState,
        width: 'resolve' // need to adjust the width to fit your dropdown
    }).on('change', function() {
        const selectedValue = $(this).val();
        document.cookie = "language=" + selectedValue + ";path=/";
        location.reload();
    });;
});
