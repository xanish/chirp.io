$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function getTweet() {
  var text = document.getElementById("tweetbox").value;
  var username =
  $.ajax({
    type: 'POST',
    url: '/chirp.io/public/tweet',
    data: { tweet: text },
    dataType: 'json',
    success: function(data){
          $("#tweetbox").val('');
          $("#tweetbox").keyup();
          $("#feed-tweet").load('ajaxfeed');
      },
    error: function(xhr) {
        console.log(xhr);
      }
    });
}

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
