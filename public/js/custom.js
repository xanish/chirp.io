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
          $("#tweetbox").keyup();
          $("#count-bar").load(' #nav-links');
          $("#feed-tweet").load(' #feed');
          $('ul.pagination').hide();
          scrolling();
      },
    error: function(xhr) {
        console.log(xhr);
      }
    });
}

$('ul.pagination').hide();
function scrolling(){
    $('#feed').jscroll({
    autoTrigger: true,
    loadingHtml: '<img class"center-block" src="avatars/loading.svg" alt="Loading" />',
    padding: 0,
    nextSelector: '.pagination li.active + li a',
    contentSelector: '#feed',
    refresh: true,
    callback: function() {
        $('ul.pagination').remove();
      }

    });
};

var text_max = 150;
$('#tweetbox').keypress(function ()
{
  $("#tweetbox").keyup();
}
);

$('#tweetbox').keydown(function ()
{
  $("#tweetbox").keyup();
}
);

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

   scrolling();
});
