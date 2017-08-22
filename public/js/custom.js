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
            url: 'tweet',
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
                $("#count-bar").load(' #nav-links');
                $("#feed-tweet").prepend(data.element);
                $("#success-msg").html('<span class="green">Posted successfully.</span>').fadeOut(5000, function(){
                  $(this).css('display', '');
                  $(this).html('');
                });
                //setTimeout(function() { $("#success-msg").html(''); }, 5000);

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


$('#search-bar').keyup(function() {
  var searchData = $('#search-bar').val();
  $('#search-page').attr('href','/search?q=' + searchData);
  if (!$('#search-results-dropdown').is(':visible')) {
    $('#search-results-dropdown').show();
  }
  if (searchData != '') {
    $.ajax({
      url: '/search/' + searchData,
      success: function(data) {
        $('.search-item').remove();
        $("#search-result-list").prepend(data);
      },
      error: function(xhr) {
        console.log(xhr);
      }
    });
  }
  else {
    $('#search-results-dropdown').hide();
  }
})

$('ul.pagination').hide();
function scrolling(){
  if(tweetcount > 20)
  {
    $('#feed').jscroll({
    autoTrigger: true,
    loadingHtml: '<img class"center-block" src="avatars/loading.svg" alt="Loading" />',
    padding: 0,
    nextSelector: '.pagination li.active + li a',
    contentSelector: '#feed',
    //refresh: true,
    callback: function() {
        $('ul.pagination').remove();
      }

    });
  }
};

var text_max = 150;
$('#count_message').html(text_max);
$(document).ready(function() {
  $('#search-results-dropdown').hide();
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
    scrolling();
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
 });
