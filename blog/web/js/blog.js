jQuery(document).ready(function ($) {
    $('.button-cancel').on('click', function (event) {
        var $t = $(event.target);
        var text = $t.text();
        if (confirm("Really " + text + "?")) {
            window.location = 'list';
            event.preventDefault();
        }
    });
    $('.timeago').timeago();
});