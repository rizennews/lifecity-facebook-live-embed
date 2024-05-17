jQuery(document).ready(function($) {
    $('#enter-chat').on('click', function() {
        var userName = $('#user-name').val();
        if (userName) {
            $('#lifecity-facebook-name-input').hide();
            $('#lifecity-facebook-chat').show();
        } else {
            alert('Please enter your name.');
        }
    });
});
