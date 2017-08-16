$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function getTweet() {
  var text = document.getElementById("tweetbox").value;
  $.ajax({
    type: 'POST',
    url: '/chirp.io/public/tweet',
    data: { tweet: text },
    dataType: 'json',
    success: function(data){
          $("#tweetbox").val('');
          $("#feed-tweet").load("ajaxfeed #feed-tweet");
      },
    error: function(xhr) {
        console.log(xhr);
      }
    });
}

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
   });
 });
