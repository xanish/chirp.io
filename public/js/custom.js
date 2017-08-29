var HASHTAG_REGEX = /#([a-zA-Z]+[0-9]*)+/gi;

$('#search-results-dropdown').hide();
try {
    document.getElementById('tweet_image_file').onchange = function () {
        try {
            $('#attach').remove();
        } catch(e) {}
        $upload = '<div class="alert alert-success" id="attach"><ul><li><i class="material-icons">attach_file</i>' + this.files.item(0).name + '</li></ul></div>';
        $('#tweetform').append($upload);
    };
} catch(e) {
    console.log('ok');
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var text_max = 150;
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

// ajax tweet post
$('#form').submit(function() {
    $('#RESPONSE_MSG').html('');
    var formData = new FormData($(this)[0]);
    var hashtags = ($('#tweetbox').val()).match(HASHTAG_REGEX);
    console.log(hashtags);
    formData.append('hashtags', JSON.stringify(hashtags));
    $.ajax({
        url: 'tweet',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $response = '';
            console.log(data);
            $("#tweetbox").val('');
            //$("#tweeteditor").html('');
            $("#tweetbox").keyup();
            $("#tweet_image_file").val('');

            if (data.element.image != 'tweet_images/') {
                $response = $response = "<div class='card'><div class='card-content'><div class='row'><div class='col-lg-2 col-md-2 col-sm-2 col-xs-3'><img class='img-responsive img-circle' src='" + data.element.avatar +
                "' alt=''></div><div class='col-lg-10 col-md-10 col-sm-10 col-xs-9'><ul class='list-unstyled list-inline'><li><h6>" + data.element.name + "</h6></li><li>" + data.element.username +
                "</li><li>" + data.element.date + "</li></ul><p>" + data.element.text + "</p><img src='" + data.element.image + "' class='img-responsive hidden-xs' alt=''></div><div class='col-xs-12 visible-xs'><img src='" + data.element.image +
                "' class='img-responsive' alt=''></div></div></div><div class='card-action'><h6><a class='red-text' href=''><i class='material-icons'>favorite_border</i> 0</a></h6></div></div>" + "<div class='margin-top-10'></div>";
            }
            else {
                $response = "<div class='card'><div class='card-content'><div class='row'><div class='col-lg-2 col-md-2 col-sm-2 col-xs-3'><img class='img-responsive img-circle' src='" + data.element.avatar +
                "' alt=''></div><div class='col-lg-10 col-md-10 col-sm-10 col-xs-9'><ul class='list-unstyled list-inline'><li><h6>" + data.element.name + "</h6></li><li>" + data.element.username +
                "</li><li>" + data.element.date + "</li></ul><p>" + data.element.text + "</p></div></div></div><div class='card-action'><h6><a class='red-text' href=''><i class='material-icons'>favorite_border</i> 0</a></h6></div></div>" + "<div class='margin-top-10'></div>";
            }
            $("#feed-tweet").prepend($response);
            //$("#tweettext").val('');
            //$("#tweetbox").keyup();
            //$("#count-bar").load(' #nav-links');
            //$("#feed").prepend(data.element);
            //parseEmoji();

            $('#attach').remove();
            $successmsg = '<div class="alert alert-success" id="postsuccess"><ul><li>Posted Successfully</li></ul></div>';
            $('#tweetform').append($successmsg);
            $('#postsuccess').fadeOut(2000, function () {
                $(this).remove();
            })
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                $errors = xhr.responseJSON;
                $errorsHtml = '<div class="alert alert-danger" id="ERRMSG"><ul>';
                $.each( $errors, function( key, value ) {
                    $errorsHtml += '<li>' + value[0] + '</li>';
                });
                $errorsHtml += '</ul></div>';
                $('#tweetform').append($errorsHtml);
                $('#ERRMSG').fadeOut(6000, function() {
                    $('#ERRMSG').remove();
                });
                console.log(xhr);
            }
        }
    });
    return false;
});

// ajax follow
$('#followbtn').submit(function() {
    $.ajax({
        url: '/follow/'+uname,
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#followbtn').html('Unfollow');
        },
        error: function(xhr) {
            console.log(xhr);
        }
    });
    return false;
});

// ajax unfollow
$('#unfollowbtn').submit(function() {
    $.ajax({
        url: '/unfollow/'+uname,
        type: 'DELETE',
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $(this).html('Follow');
        },
        error: function(xhr) {
            console.log(xhr);
        }
    });
    return false;
});

$('#navbar-search').keyup(function () {
    if ($(this).val() != '') {
        $('#search-page').attr('href','/search/' + $(this).val());
        $('#search-results-dropdown').show();
        $.ajax({
            url: '/search',
            type: 'GET',
            data: {
                'q': $(this).val()
            },
            success: function (data) {
                $('.search-item').remove();
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $element = "<li class='row search-item'><div class='col-xs-2'><img class='img-responsive img-circle' src='/avatars/" + data[i].profile_image +
                    "' alt=''></div><div class='col-xs-10'><a href='/" + data[i].username + "'><ul class='list-unstyled'><li><h6>" + data[i].name +
                    "</h6></li><li>" + data[i].username + "</li></ul></a></div></li>";
                    $('#search-results-dropdown').prepend($element);
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
        return false;
    }
    else {
        $('.search-item').remove();
        $('#search-results-dropdown').hide();
    }
});
