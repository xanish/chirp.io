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
                $("#tweet_image_file").val('');
                $("#tweettext").val('');
                $("#tweetbox").keyup();
                $("#feed-tweet").prepend(data.element);
                $("#success-msg").html('<span class="green">Posted successfully.</span>');
                setTimeout(function() { $("#success-msg").html(''); }, 5000);

            },
            error: function(xhr) {
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
    $('#password-strength-meter').hide();
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

var strength = {
  0: "<span class='red'>Worst</span>",
  1: "<span class='orange'>Bad</span>",
  2: "<span class='yellow'>Weak</span>",
  3: "<span class='green'>Good</span>",
  4: "<span class='dark-green'>Strong</span>"
}

var password = document.getElementById('password');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

password.addEventListener('input', function() {
  if (!$('#password-strength-meter').is(':visible')) {
    $('#password-strength-meter').show();
  }

  var val = password.value;
  var result = zxcvbn(val);

  // Update the password strength meter
  meter.value = result.score;

  // Update the text indicator
  if (val !== "") {
    text.innerHTML = "Strength: " + strength[result.score];
    if (result.feedback.suggestions != "") {
      text.innerHTML += " Hint: " + result.feedback.suggestions;
    }
  } else {
    text.innerHTML = "";
  }
});
