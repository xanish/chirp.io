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
                $("#tweettext").val('');
                $("#tweetbox").keyup();
                $("#count-bar").load(' #nav-links');
                $("#feed-tweet").load(' #feed', function(){
                  $('#feed').data('jscroll', null);
                  $('ul.pagination').hide();
                  scrolling();
                });
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
    $('#tweetbox').emojionePicker({
      pickerTop: 5,
      pickerRight: 5,
      type: "unicode"
    });

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

   scrolling();
});
