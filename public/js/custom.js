$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function() {
    $('#form,#mobile-form').submit(function() {
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: '/tweet',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#tweetbox").val('');
                $("#tweettext").val('');
                $("#tweetbox").keyup();
                $("#feed-tweet").load(' #feed-tweet');
            },
            error: function(xhr) {
                console.log(xhr);
            }
        });
        // .then(function(data) {
        //     $("#tweetbox").val('');
        //     $("#tweetbox").keyup();
        //     $("#feed-tweet").load(' #feed-tweet');
        // });
        return false;
    });
});

var text_max = 150;
$('#count_message').html(text_max);
$(document).ready(function() {
    $('#tweetbox').keyup(function() {
        var empty = false;
        if ($(this).val().length == 0) {
            empty = true;
        }
        if (empty) {
            $('#tweet-button').attr('disabled', 'disabled');
        } else {
            $('#tweet-button').attr('disabled', false);
        }

        var text_length = $('#tweetbox').val().length;
        var text_remaining = text_max - text_length;
        $('#count_message').html(text_remaining);
    });
});
