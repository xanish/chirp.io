$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function() {
    $('#form,#mobile-form').submit(function() {
        $('#ERRORMSG').html('');
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
                // window.alert("We ran into some error while processing your request, please verify the details and try again.");
                if ($('#tweetbox').val() != '') {
                    $('#ERRORMSG').html("Unsupported file format.");
                }
                else {
                    $('#ERRORMSG').html("Can't submit an empty tweet");
                }
                console.log(xhr);
            }
        });
        return false;
    });
});

var text_max = 150;
$('#count_message').html(text_max);
$(document).ready(function() {
    $('#tweetbox').keyup(function() {
        $('#ERRORMSG').html('');
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
