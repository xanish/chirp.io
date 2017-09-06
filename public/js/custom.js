var HASHTAG_REGEX = /#([a-zA-Z]+[0-9]*)+/gi;
var EMOJI_REGEX = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|[\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|[\ud83c[\ude32-\ude3a]|[\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
var $newtweetbuffer = " ";
var tweetcounter = 0;
$('#search-results-dropdown').hide();
$('#main-results-dropdown').hide();
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
$('#count_message').html(text_max);
/*$('#tweetbox').keyup(function() {
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

$('#tweetbox').keydown(function() {
    $('#tweetbox').keyup();
});*/

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
            $("#notweetmessageprofile").hide();
            $response = '';
            console.log(data);
            //$("#tweetbox").html('');
            $("#tweeteditor").html('');
            $("#tweetbox").keyup();
            $("#tweet_image_file").val('');
            $('#count_message').html(text_max);
            $('#tweet-button').attr('disabled', 'disabled');
            $reponse =    tweetBuilder(data.element.id,
                data.element.avatar,
                data.element.name,
                data.element.username,
                data.element.date,
                data.element.text,
                data.element.tags,
                data.element.image,
                data.element.original,
                null,
                0
            );
            $("#feed-tweet").prepend($response);
            $("#count-bar").load(' #navcount');

            $('#attach').remove();
            $successmsg = '<div class="alert alert-success" id="postsuccess"><ul><li>Posted Successfully</li></ul></div>';
            $('#tweetform').append($successmsg);
            $('#postsuccess').fadeOut(2000, function () {
                $(this).remove();
            })
        },
        error: function(jqXHR, xhr) {
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
            console.log(xhr);
            console.log(jqXHR.status);
            if(jqXHR.status == 401 || jqXHR.status == 500) {
              redirectToLogin();
            }
        },
        complete: function() {
            if(__lastid == null) {
                showBackToTop();
            }
        }
    });
    return false;
});

$(document).ready(function() {

  try {
    $('#tweetbox').emojioneArea({
      pickerPosition: "bottom",
      tonesStyle: "bullet",
      events: {
          // Display remaining characters on tweetbox
          keyup: function (editor, event) {
          $('#ERRORMSG').html('');
          var empty = false;
          if ($(editor).html().length == 0) {
            empty = true;
          }
          if (empty) {
            $('#tweet-button').attr('disabled', 'disabled');
          } else {
            $('#tweet-button').attr('disabled', false);
          }
          var text_length = $("#tweeteditor > img").length + $(editor).text().length;
          var text_remaining = text_max - text_length;
          if(text_remaining < 0)
          {
            $('#tweet-button').attr('disabled', 'disabled');
          }
          $('#count_message').html(text_remaining);
        },
        keydown: function (editor, event) {
          $(editor).keyup();
        },
        emojibtn_click: function (button, event) {
          $("div#tweeteditor.emojionearea-editor").keyup();
        }
      }
    });
    }
    catch(e)
    {
  console.log(e);
      // To prevent emojioneArea is not a function error.
    }

    if ( $('#feed-tweet').length ) {
        unbindscroll();
        $("#loading").show();
        loadTweet(null);
    }
    if ( $('#feed').length ) {
        unbindscroll();
        $("#loading").show();
        loadFeed(null, null);
    }

    // autocomplete for cities and countries
    $.fn.autoComplete = function(options) {
        try {
            var autocompleteService = new google.maps.places.AutocompleteService();
        } catch (e) {
            // console.log("Google maps script not available.");
        }
        var predictionsDropDown = $('<div class="autocomplete"></div>').appendTo('body');
        var input = this;
        input.keyup(function() {
            var searchStr = $(this).val();
            var caller = $(this).attr('id');
            if (searchStr.length > 0) {
                if (caller == 'city') {
                    var params = {
                        input: searchStr,
                        types: ['(cities)']
                    };
                }
                else if(caller == 'country') {
                    var params = {
                        input: searchStr,
                        types: ['(regions)']
                    };
                }
                autocompleteService.getPlacePredictions(params, updatePredictions);
            }
            else {
                predictionsDropDown.hide();
            }
        });
        predictionsDropDown.delegate('div', 'click', function() {
            input.val($(this).text());
            predictionsDropDown.hide();
        });
        $(document).mouseup(function (e) {
            if (!predictionsDropDown.is(e.target) && predictionsDropDown.has(e.target).length === 0) {
                predictionsDropDown.hide();
            }
        });
        $(window).resize(function() {
            updatePredictionsDropDownDisplay(predictionsDropDown, input);
        });
        updatePredictionsDropDownDisplay(predictionsDropDown, input);
        function updatePredictions(predictions, status) {
            if (google.maps.places.PlacesServiceStatus.OK != status) {
                predictionsDropDown.hide();
                return;
            }
            predictionsDropDown.empty();
            $.each(predictions, function(i, prediction) {
                predictionsDropDown.append('<div>' + $.fn.autoComplete.transliterate(prediction.terms[0].value) + '</div');
            });
            predictionsDropDown.show();
        }
        return input;
    };

    $.fn.autoComplete.transliterate = function (s) {
        s = String(s);
        var char_map = {
            // Latin
            'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C',
            'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I',
            'Ð': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ő': 'O',
            'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', 'Ý': 'Y', 'Þ': 'TH',
            'ß': 'ss',
            'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c',
            'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
            'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o',
            'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th',
            'ÿ': 'y',
            // Latin symbols
            '©': '(c)',
            // Greek
            'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
            'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', 'Ν': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
            'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
            'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', 'Ώ': 'W', 'Ϊ': 'I',
            'Ϋ': 'Y',
            'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
            'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
            'ρ': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
            'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
            'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', 'ΐ': 'i',
            // Turkish
            'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
            'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g',
            // Russian
            'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh',
            'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O',
            'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
            'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
            'Я': 'Ya',
            'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
            'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
            'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
            'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
            'я': 'ya',
            // Ukrainian
            'Є': 'Ye', 'І': 'I', 'Ї': 'Yi', 'Ґ': 'G',
            'є': 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',
            // Czech
            'Č': 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U',
            'Ž': 'Z',
            'č': 'c', 'ď': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
            'ž': 'z',
            // Polish
            'Ą': 'A', 'Ć': 'C', 'Ę': 'e', 'Ł': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z',
            'Ż': 'Z',
            'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
            'ż': 'z',
            // Latvian
            'Ā': 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N',
            'Š': 'S', 'Ū': 'u', 'Ž': 'Z',
            'ā': 'a', 'č': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
            'š': 's', 'ū': 'u', 'ž': 'z'
        };
        for (var k in char_map) {
            s = s.replace(new RegExp(k, 'g'), char_map[k]);
        }
        return s;
    };
    // enable autocomplete after initializing function to prevent ERR: autocomplete is not a function
    $('input#city').autoComplete();
    $('input#country').autoComplete();

    $messageFail = '<div id="fail" class="alert alert-danger float-success"><h6>Try Again Later</h6></div>';
    $(document).on('click', '.likes', function() {
        $id = $(this).attr('id');
        $.ajax({
            url: '/like/' + $(this).attr('id'),
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                $('#' + $id).removeClass('likes').addClass('unlikes');
                $('#' + $id + ' i.material-icons').text('favorite');
                $current = $('#' + $id + ' span').text();
                $('#' + $id + ' span').text(parseInt($current) + 1);
            },
            error: function (jqXHR, xhr) {
                $(body).append($messageFail);
                $('#fail').fadeOut(5000, function () {
                    $(this).remove();
                });
                if(jqXHR.status == 401 || jqXHR.status == 500) {
                  redirectToLogin();
                }
            }
        });
        return false;
    });
    $(document).on('click', '.unlikes', function() {
        $id = $(this).attr('id');
        $.ajax({
            url: '/unlike/' + $(this).attr('id'),
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#' + $id).removeClass('unlikes').addClass('likes');
                $('#' + $id + ' i.material-icons').text('favorite_border');
                $current = $('#' + $id + ' span').text();
                $('#' + $id + ' span').text(parseInt($current) - 1);
            },
            error: function (jqXHR, xhr) {
                $('#app').append($messageFail);
                $('#fail').fadeOut(5000, function () {
                    $(this).remove();
                });
                if(jqXHR.status == 401 || jqXHR.status == 500) {
                  redirectToLogin();
                }
            }
        });
        return false;
    });

    $(document).on('click', '.follow', function() {
        $id = $(this).attr('id');
        $.ajax({
            url: '/follow/' + $(this).attr('id'),
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#count-bar").load(' #navcount');
                $('#' + $id).removeClass('follow').addClass('unfollow');
                $('#' + $id).removeClass('btn-default').addClass('btn-danger');
                $('#' + $id).text('Unfollow');
            },
            error: function (jqXHR, xhr) {
                $('#app').append($messageFail);
                $('#fail').fadeOut(5000, function () {
                    $(this).remove();
                });
                if(jqXHR.status == 401 || jqXHR.status == 500) {
                  redirectToLogin();
                }
            }
        });
        return false;
    });
    $(document).on('click', '.unfollow', function() {
        $id = $(this).attr('id');
        $.ajax({
            url: '/unfollow/' + $(this).attr('id'),
            type: 'DELETE',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#count-bar").load(' #navcount');
                $('#' + $id).removeClass('unfollow').addClass('follow');
                $('#' + $id).removeClass('btn-danger').addClass('btn-default');
                $('#' + $id).text('Follow');
            },
            error: function (jqXHR, xhr) {
                $('#app').append($messageFail);
                $('#fail').fadeOut(5000, function () {
                    $(this).remove();
                });
                if(jqXHR.status == 401 || jqXHR.status == 500) {
                  redirectToLogin();
                }
            }
        });
        return false;
    });

});

var __lastid;
function loadTweet(_lastid) {
    try {
    $.ajax({
        url: 'gettweets',
        type: 'GET',
        data: {
          username : _username,
          lastid : _lastid
        },
        success: function(data) {
            console.log(data);
            var $finaldata = " ";
            if(data.posts.length != 0) {
              //$("#notweetmessageprofile").hide();
              tweetcounter += data.posts.length;
            }
            for( i=0; i<data.posts.length; i++ ) {
              $finaldata += tweetBuilder(data.posts[i].id,
                                         data.user.profile_image,
                                         data.user.name,
                                         data.user.username,
                                         data.posts[i].created_at,
                                         data.posts[i].text,
                                         data.posts[i].tags,
                                         data.posts[i].tweet_image,
                                         data.posts[i].original_image,
                                         data.liked,
                                         data.posts[i].likes
                                       );
            __lastid = data.posts[i].id;
            //$finaldata = $finaldata + $response;
          }
          if(data.posts.length == 0) {
            __lastid = null;
          }
          $("#feed-tweet").append($finaldata);
        },
        error: function(jqXHR, xhr) {
          console.log(xhr);
          console.log(jqXHR.status);
          if(jqXHR.status == 401) {
            redirectToLogin();
          }
        },
        complete: function() {
          $("#loading").hide();
          bindscroll();
          if(tweetcounter == 0) {
            $("#notweetmessageprofile").show();
          }
            if(__lastid == null) {
              //$("#loading").hide();
              showBackToTop();
            }
        }
      });
    }catch(e) {}
    return false;
};

var __feedlastid;
var __feedcurrentid = null;
function loadFeed(_feedlastid, _feedcurrentid) {
    $.ajax({
        url: 'getfeed',
        type: 'GET',
        data: {
            lastid : _feedlastid,
            currentid : _feedcurrentid
        },
        success: function(data) {
            console.log(data);
            var $finaldata = " ";
            if(data.posts.length !=0) {
                if(data.posts[0].id > __feedcurrentid) {
                    __feedcurrentid = data.posts[0].id;
                }
                $("#notweetmessage").hide();
                tweetcounter += parseFloat(data.posts.length);
            }
            for( i=0; i<data.posts.length; i++ ) {
                $finaldata += tweetBuilder(data.posts[i].id,
                    data.posts[i].profile_image,
                    data.posts[i].name,
                    data.posts[i].username,
                    data.posts[i].created_at,
                    data.posts[i].text,
                    data.posts[i].tags,
                    data.posts[i].tweet_image,
                    data.posts[i].original_image,
                    data.liked,
                    data.posts[i].likes
                );
                if(data.currentdata == 0) {
                    __feedlastid = data.posts[i].id;
                }
                //$finaldata = $finaldata + $response;
            }
            if(data.posts.length == 0 && data.currentdata == 0) {
                __feedlastid = null;
            }
            if(data.currentdata == 1) {
                var count = parseFloat($("#newcount").text());
                count += parseFloat(data.posts.length);
                $("#newcount").text(count);
                if(count > 0) {
                    $(".tweet-alert").show();
                }
                $newtweetbuffer = $finaldata + $newtweetbuffer;
            }
            else {
                $("#feed").append($finaldata);
            }
        },
        error: function(jqXHR, xhr) {
            console.log(xhr);
            console.log(jqXHR.status);
            if(jqXHR.status == 401) {
              redirectToLogin();
            }
        },
        complete: function() {
            $("#loading").hide();
            bindscroll();
            if(tweetcounter == 0) {
                $("#notweetmessage").show();
            }
            if(__feedlastid == null) {
                //$("#loading").hide();
                showBackToTop();
            }
        }
    });
    return false;
};

function backtotop() {
    $('html, body').animate({scrollTop : 0},600);
    return false;
}

function bindscroll() {
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() + 2 >= $(document).height()){ //scrolled to bottom of the page
            if(__lastid != null) {
                unbindscroll();
                $("#loading").show();
                loadTweet(__lastid);
            }

            if(__feedlastid != null) {
                unbindscroll();
                $("#loading").show();
                loadFeed(__feedlastid, null);
            }
        }
    });
}

function unbindscroll() {
    $(window).off('scroll');
}

function ltrim(str, chr) {
    var rgxtrim = (!chr) ? new RegExp('^\\s+') : new RegExp('^'+chr+'+');
    return str.replace(rgxtrim, '');
}

function addHashTags(tagArr, textArr) {
    for( j=0; j<textArr.length; j++) {
        var chirptext = textArr[j];
        if(jQuery.inArray(chirptext, tagArr) != -1) {
            var taggedtext = ltrim(chirptext, '#');
            $response += "<a href='/tag/" + taggedtext + "'>" + chirptext + "</a>" + " ";
        }
        else {
            $response += chirptext + " ";
        }
        var twemoji = /\s+|([\uD800-\uDBFF][\uDC00-\uDFFF](?:[\u200D\uFE0F][\uD800-\uDBFF][\uDC00-\uDFFF]){2,}|\uD83D\uDC69(?:\u200D(?:(?:\uD83D\uDC69\u200D)?\uD83D\uDC67|(?:\uD83D\uDC69\u200D)?\uD83D\uDC66)|\uD83C[\uDFFB-\uDFFF])|\uD83D\uDC69\u200D(?:\uD83D\uDC69\u200D)?\uD83D\uDC66\u200D\uD83D\uDC66|\uD83D\uDC69\u200D(?:\uD83D\uDC69\u200D)?\uD83D\uDC67\u200D(?:\uD83D[\uDC66\uDC67])|\uD83C\uDFF3\uFE0F\u200D\uD83C\uDF08|(?:\uD83C[\uDFC3\uDFC4\uDFCA]|\uD83D[\uDC6E\uDC71\uDC73\uDC77\uDC81\uDC82\uDC86\uDC87\uDE45-\uDE47\uDE4B\uDE4D\uDE4E\uDEA3\uDEB4-\uDEB6]|\uD83E[\uDD26\uDD37-\uDD39\uDD3D\uDD3E\uDDD6-\uDDDD])(?:\uD83C[\uDFFB-\uDFFF])\u200D[\u2640\u2642]\uFE0F|\uD83D\uDC69(?:\uD83C[\uDFFB-\uDFFF])\u200D(?:\uD83C[\uDF3E\uDF73\uDF93\uDFA4\uDFA8\uDFEB\uDFED]|\uD83D[\uDCBB\uDCBC\uDD27\uDD2C\uDE80\uDE92])|(?:\uD83C[\uDFC3\uDFC4\uDFCA]|\uD83D[\uDC6E\uDC6F\uDC71\uDC73\uDC77\uDC81\uDC82\uDC86\uDC87\uDE45-\uDE47\uDE4B\uDE4D\uDE4E\uDEA3\uDEB4-\uDEB6]|\uD83E[\uDD26\uDD37-\uDD39\uDD3C-\uDD3E\uDDD6-\uDDDF])\u200D[\u2640\u2642]\uFE0F|\uD83C\uDDFD\uD83C\uDDF0|\uD83C\uDDF6\uD83C\uDDE6|\uD83C\uDDF4\uD83C\uDDF2|\uD83C\uDDE9(?:\uD83C[\uDDEA\uDDEC\uDDEF\uDDF0\uDDF2\uDDF4\uDDFF])|\uD83C\uDDF7(?:\uD83C[\uDDEA\uDDF4\uDDF8\uDDFA\uDDFC])|\uD83C\uDDE8(?:\uD83C[\uDDE6\uDDE8\uDDE9\uDDEB-\uDDEE\uDDF0-\uDDF5\uDDF7\uDDFA-\uDDFF])|(?:\u26F9|\uD83C[\uDFCB\uDFCC]|\uD83D\uDD75)(?:\uFE0F\u200D[\u2640\u2642]|(?:\uD83C[\uDFFB-\uDFFF])\u200D[\u2640\u2642])\uFE0F|(?:\uD83D\uDC41\uFE0F\u200D\uD83D\uDDE8|\uD83D\uDC69(?:\uD83C[\uDFFB-\uDFFF])\u200D[\u2695\u2696\u2708]|\uD83D\uDC69\u200D[\u2695\u2696\u2708]|\uD83D\uDC68(?:(?:\uD83C[\uDFFB-\uDFFF])\u200D[\u2695\u2696\u2708]|\u200D[\u2695\u2696\u2708]))\uFE0F|\uD83C\uDDF2(?:\uD83C[\uDDE6\uDDE8-\uDDED\uDDF0-\uDDFF])|\uD83D\uDC69\u200D(?:\uD83C[\uDF3E\uDF73\uDF93\uDFA4\uDFA8\uDFEB\uDFED]|\uD83D[\uDCBB\uDCBC\uDD27\uDD2C\uDE80\uDE92]|\u2764\uFE0F\u200D(?:\uD83D\uDC8B\u200D(?:\uD83D[\uDC68\uDC69])|\uD83D[\uDC68\uDC69]))|\uD83C\uDDF1(?:\uD83C[\uDDE6-\uDDE8\uDDEE\uDDF0\uDDF7-\uDDFB\uDDFE])|\uD83C\uDDEF(?:\uD83C[\uDDEA\uDDF2\uDDF4\uDDF5])|\uD83C\uDDED(?:\uD83C[\uDDF0\uDDF2\uDDF3\uDDF7\uDDF9\uDDFA])|\uD83C\uDDEB(?:\uD83C[\uDDEE-\uDDF0\uDDF2\uDDF4\uDDF7])|[#\*0-9]\uFE0F\u20E3|\uD83C\uDDE7(?:\uD83C[\uDDE6\uDDE7\uDDE9-\uDDEF\uDDF1-\uDDF4\uDDF6-\uDDF9\uDDFB\uDDFC\uDDFE\uDDFF])|\uD83C\uDDE6(?:\uD83C[\uDDE8-\uDDEC\uDDEE\uDDF1\uDDF2\uDDF4\uDDF6-\uDDFA\uDDFC\uDDFD\uDDFF])|\uD83C\uDDFF(?:\uD83C[\uDDE6\uDDF2\uDDFC])|\uD83C\uDDF5(?:\uD83C[\uDDE6\uDDEA-\uDDED\uDDF0-\uDDF3\uDDF7-\uDDF9\uDDFC\uDDFE])|\uD83C\uDDFB(?:\uD83C[\uDDE6\uDDE8\uDDEA\uDDEC\uDDEE\uDDF3\uDDFA])|\uD83C\uDDF3(?:\uD83C[\uDDE6\uDDE8\uDDEA-\uDDEC\uDDEE\uDDF1\uDDF4\uDDF5\uDDF7\uDDFA\uDDFF])|\uD83C\uDFF4\uDB40\uDC67\uDB40\uDC62(?:\uDB40\uDC77\uDB40\uDC6C\uDB40\uDC73|\uDB40\uDC73\uDB40\uDC63\uDB40\uDC74|\uDB40\uDC65\uDB40\uDC6E\uDB40\uDC67)\uDB40\uDC7F|\uD83D\uDC68(?:\u200D(?:\u2764\uFE0F\u200D(?:\uD83D\uDC8B\u200D)?\uD83D\uDC68|(?:(?:\uD83D[\uDC68\uDC69])\u200D)?\uD83D\uDC66\u200D\uD83D\uDC66|(?:(?:\uD83D[\uDC68\uDC69])\u200D)?\uD83D\uDC67\u200D(?:\uD83D[\uDC66\uDC67])|\uD83C[\uDF3E\uDF73\uDF93\uDFA4\uDFA8\uDFEB\uDFED]|\uD83D[\uDCBB\uDCBC\uDD27\uDD2C\uDE80\uDE92])|(?:\uD83C[\uDFFB-\uDFFF])\u200D(?:\uD83C[\uDF3E\uDF73\uDF93\uDFA4\uDFA8\uDFEB\uDFED]|\uD83D[\uDCBB\uDCBC\uDD27\uDD2C\uDE80\uDE92]))|\uD83C\uDDF8(?:\uD83C[\uDDE6-\uDDEA\uDDEC-\uDDF4\uDDF7-\uDDF9\uDDFB\uDDFD-\uDDFF])|\uD83C\uDDF0(?:\uD83C[\uDDEA\uDDEC-\uDDEE\uDDF2\uDDF3\uDDF5\uDDF7\uDDFC\uDDFE\uDDFF])|\uD83C\uDDFE(?:\uD83C[\uDDEA\uDDF9])|\uD83C\uDDEE(?:\uD83C[\uDDE8-\uDDEA\uDDF1-\uDDF4\uDDF6-\uDDF9])|\uD83C\uDDF9(?:\uD83C[\uDDE6\uDDE8\uDDE9\uDDEB-\uDDED\uDDEF-\uDDF4\uDDF7\uDDF9\uDDFB\uDDFC\uDDFF])|\uD83C\uDDEC(?:\uD83C[\uDDE6\uDDE7\uDDE9-\uDDEE\uDDF1-\uDDF3\uDDF5-\uDDFA\uDDFC\uDDFE])|\uD83C\uDDFA(?:\uD83C[\uDDE6\uDDEC\uDDF2\uDDF3\uDDF8\uDDFE\uDDFF])|\uD83C\uDDEA(?:\uD83C[\uDDE6\uDDE8\uDDEA\uDDEC\uDDED\uDDF7-\uDDFA])|\uD83C\uDDFC(?:\uD83C[\uDDEB\uDDF8])|(?:\u26F9|\uD83C[\uDFCB\uDFCC]|\uD83D\uDD75)(?:\uD83C[\uDFFB-\uDFFF])|(?:\uD83C[\uDFC3\uDFC4\uDFCA]|\uD83D[\uDC6E\uDC71\uDC73\uDC77\uDC81\uDC82\uDC86\uDC87\uDE45-\uDE47\uDE4B\uDE4D\uDE4E\uDEA3\uDEB4-\uDEB6]|\uD83E[\uDD26\uDD37-\uDD39\uDD3D\uDD3E\uDDD6-\uDDDD])(?:\uD83C[\uDFFB-\uDFFF])|(?:[\u261D\u270A-\u270D]|\uD83C[\uDF85\uDFC2\uDFC7]|\uD83D[\uDC42\uDC43\uDC46-\uDC50\uDC66\uDC67\uDC70\uDC72\uDC74-\uDC76\uDC78\uDC7C\uDC83\uDC85\uDCAA\uDD74\uDD7A\uDD90\uDD95\uDD96\uDE4C\uDE4F\uDEC0\uDECC]|\uD83E[\uDD18-\uDD1C\uDD1E\uDD1F\uDD30-\uDD36\uDDD1-\uDDD5])(?:\uD83C[\uDFFB-\uDFFF])|\uD83D\uDC68(?:\u200D(?:(?:(?:\uD83D[\uDC68\uDC69])\u200D)?\uD83D\uDC67|(?:(?:\uD83D[\uDC68\uDC69])\u200D)?\uD83D\uDC66)|\uD83C[\uDFFB-\uDFFF])|(?:[\u261D\u26F9\u270A-\u270D]|\uD83C[\uDF85\uDFC2-\uDFC4\uDFC7\uDFCA-\uDFCC]|\uD83D[\uDC42\uDC43\uDC46-\uDC50\uDC66-\uDC69\uDC6E\uDC70-\uDC78\uDC7C\uDC81-\uDC83\uDC85-\uDC87\uDCAA\uDD74\uDD75\uDD7A\uDD90\uDD95\uDD96\uDE45-\uDE47\uDE4B-\uDE4F\uDEA3\uDEB4-\uDEB6\uDEC0\uDECC]|\uD83E[\uDD18-\uDD1C\uDD1E\uDD1F\uDD26\uDD30-\uDD39\uDD3D\uDD3E\uDDD1-\uDDDD])(?:\uD83C[\uDFFB-\uDFFF])?|(?:[\u231A\u231B\u23E9-\u23EC\u23F0\u23F3\u25FD\u25FE\u2614\u2615\u2648-\u2653\u267F\u2693\u26A1\u26AA\u26AB\u26BD\u26BE\u26C4\u26C5\u26CE\u26D4\u26EA\u26F2\u26F3\u26F5\u26FA\u26FD\u2705\u270A\u270B\u2728\u274C\u274E\u2753-\u2755\u2757\u2795-\u2797\u27B0\u27BF\u2B1B\u2B1C\u2B50\u2B55]|\uD83C[\uDC04\uDCCF\uDD8E\uDD91-\uDD9A\uDDE6-\uDDFF\uDE01\uDE1A\uDE2F\uDE32-\uDE36\uDE38-\uDE3A\uDE50\uDE51\uDF00-\uDF20\uDF2D-\uDF35\uDF37-\uDF7C\uDF7E-\uDF93\uDFA0-\uDFCA\uDFCF-\uDFD3\uDFE0-\uDFF0\uDFF4\uDFF8-\uDFFF]|\uD83D[\uDC00-\uDC3E\uDC40\uDC42-\uDCFC\uDCFF-\uDD3D\uDD4B-\uDD4E\uDD50-\uDD67\uDD7A\uDD95\uDD96\uDDA4\uDDFB-\uDE4F\uDE80-\uDEC5\uDECC\uDED0-\uDED2\uDEEB\uDEEC\uDEF4-\uDEF8]|\uD83E[\uDD10-\uDD3A\uDD3C-\uDD3E\uDD40-\uDD45\uDD47-\uDD4C\uDD50-\uDD6B\uDD80-\uDD97\uDDC0\uDDD0-\uDDE6])|(?:[#\*0-9\xA9\xAE\u203C\u2049\u2122\u2139\u2194-\u2199\u21A9\u21AA\u231A\u231B\u2328\u23CF\u23E9-\u23F3\u23F8-\u23FA\u24C2\u25AA\u25AB\u25B6\u25C0\u25FB-\u25FE\u2600-\u2604\u260E\u2611\u2614\u2615\u2618\u261D\u2620\u2622\u2623\u2626\u262A\u262E\u262F\u2638-\u263A\u2640\u2642\u2648-\u2653\u2660\u2663\u2665\u2666\u2668\u267B\u267F\u2692-\u2697\u2699\u269B\u269C\u26A0\u26A1\u26AA\u26AB\u26B0\u26B1\u26BD\u26BE\u26C4\u26C5\u26C8\u26CE\u26CF\u26D1\u26D3\u26D4\u26E9\u26EA\u26F0-\u26F5\u26F7-\u26FA\u26FD\u2702\u2705\u2708-\u270D\u270F\u2712\u2714\u2716\u271D\u2721\u2728\u2733\u2734\u2744\u2747\u274C\u274E\u2753-\u2755\u2757\u2763\u2764\u2795-\u2797\u27A1\u27B0\u27BF\u2934\u2935\u2B05-\u2B07\u2B1B\u2B1C\u2B50\u2B55\u3030\u303D\u3297\u3299]|\uD83C[\uDC04\uDCCF\uDD70\uDD71\uDD7E\uDD7F\uDD8E\uDD91-\uDD9A\uDDE6-\uDDFF\uDE01\uDE02\uDE1A\uDE2F\uDE32-\uDE3A\uDE50\uDE51\uDF00-\uDF21\uDF24-\uDF93\uDF96\uDF97\uDF99-\uDF9B\uDF9E-\uDFF0\uDFF3-\uDFF5\uDFF7-\uDFFF]|\uD83D[\uDC00-\uDCFD\uDCFF-\uDD3D\uDD49-\uDD4E\uDD50-\uDD67\uDD6F\uDD70\uDD73-\uDD7A\uDD87\uDD8A-\uDD8D\uDD90\uDD95\uDD96\uDDA4\uDDA5\uDDA8\uDDB1\uDDB2\uDDBC\uDDC2-\uDDC4\uDDD1-\uDDD3\uDDDC-\uDDDE\uDDE1\uDDE3\uDDE8\uDDEF\uDDF3\uDDFA-\uDE4F\uDE80-\uDEC5\uDECB-\uDED2\uDEE0-\uDEE5\uDEE9\uDEEB\uDEEC\uDEF0\uDEF3-\uDEF8]|\uD83E[\uDD10-\uDD3A\uDD3C-\uDD3E\uDD40-\uDD45\uDD47-\uDD4C\uDD50-\uDD6B\uDD80-\uDD97\uDDC0\uDDD0-\uDDE6])\uFE0F)/;
        //console.log(textArr.split(twemoji));
    }
}

function addLikes(likedArr, likescount, id) {
    if(likedArr != -1) {
        if(jQuery.inArray(id, likedArr) != -1) {
            $response += "<div class='card-action'>" + "<h6><a class='red-text unlikes' id='" + id + "'><i class='material-icons'>favorite</i> <span>" + likescount + "</span></a></h6></div>";
        }
        else {
            $response += "<div class='card-action'>" + "<h6><a class='red-text likes' id='" + id + "'><i class='material-icons'>favorite_border</i> <span>" + likescount + "</span></a></h6></div>";
        }
    }
    else {
        $response += "<div class='card-action'>" + "<h6><a class='red-text'  href='/login'><i class='material-icons'>favorite_border</i> <span>" + likescount + "</span></a></h6></div>";
    }
}

function showBackToTop() {
    if ($('#feed-tweet').outerHeight(true) || $('#feed').outerHeight(true) > $(window).height()) {
        $('.stream-end').show();
    }
}

function tweetBuilder(id, profile_image, name, username, created_at, textArr, tagArr, tweet_image, original_image, likedArr, likescount) {
    $response =   "<div class='card hoverable'>" +
    "<div class='card-content'>" +
    "<div class='row'>" +
    "<div class='col-lg-2 col-md-2 col-sm-2 col-xs-3'>" +
    "<img class='img-responsive img-circle' src='" + profile_image + "' alt=''>" +
    "</div>" +
    "<div class='col-lg-10 col-md-10 col-sm-10 col-xs-9'>" +
    "<ul class='list-unstyled list-inline'>" +
    "<li><a href='/" + username + "'><h6>" + name + "</h6></li>" +
    "<li> @" + username + "</a></li>" +
    "<li>" + created_at + "</li>" +
    "</ul>" +
    "<p class='text'>";
    addHashTags(tagArr, textArr) +
    "</p>";

    if (tweet_image != 'tweet_images/') {
        $response +=          "<a href='" + original_image + "' data-lightbox='box-" + id + "'>" +
        "<img src='" + tweet_image + "' class='img-responsive hidden-xs lightboxed' alt=''>" +
        "</a>" +
        "</div>" +
        "<div class='col-xs-12 visible-xs'>" +
        "<a href='" + original_image + "' data-lightbox='box-" + id + "-mini'>" +
        "<img src='" + tweet_image + "' class='img-responsive visible-xs lightboxed' alt=''>" +
        "</a>";
    }

    $response +=        "</div>" +
    "</div>" +
    "</div>";
    addLikes(likedArr, likescount, id);
    $response +=  "</div>" +
    "<div class='margin-top-10'>" +
    "</div>";

    return $response;
}

window.setInterval(function() {
    if(__feedcurrentid != null) {
        loadFeed(null, __feedcurrentid);
    }

}, 30000);

$(".tweet-alert").click(function() {
    $(".tweet-alert").hide();
    $("#newcount").text(0);
    $($newtweetbuffer).hide().prependTo("#feed").fadeIn("slow");
    $newtweetbuffer = " ";
});

  function redirectToLogin() {
    window.location="http://chirp.io/login";
  }

  // update the dropdown city and country values
  function updatePredictionsDropDownDisplay(dropDown, input) {
    try {
        dropDown.css({
            'width': input.outerWidth(),
            'left': input.offset().left,
            'top': input.offset().top + input.outerHeight()
        });
    }
    catch (e) {
        // console.log("Can't set css property for dropdown as it is not available on this page.");
    }
}

$('#navbar-search').keyup(function () {
    if ($(this).val() != '') {
        $('#search-page').attr('href','/search/' + $(this).val());
        $('#navsearch').attr('action', '/search/' + $(this).val());
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
                if (data.users.length != 0) {
                    for (var i = 0; i < data.users.length; i++) {
                        $element = "<li class='row search-item'><div class='col-xs-2'><img class='img-responsive img-circle' src='/avatars/" + data.users[i].profile_image +
                        "' alt=''></div><div class='col-xs-10'><a href='/" + data.users[i].username + "'><ul class='list-unstyled'><li><h6>" + data.users[i].name +
                        "</h6></li><li>@" + data.users[i].username + "</li></ul></a></div></li>";
                        $('#search-results-dropdown').prepend($element);
                    }
                    $('#search-results-dropdown').prepend("<li class='row search-item'><div class='col-xs-12'><ul class='list-unstyled'><li><h6>Users</h6></li></ul></div></li>");
                }
                if (data.tags.length == 0 && data.users.length == 0) {
                    $element = "<li class='row search-item'><a class='col-xs-12'>No Results Found</a></li>"
                    $('#search-results-dropdown').prepend($element);
                }
                else {
                    for (var i = 0; i < data.tags.length; i++) {
                        $element = "<li class='row search-item'><div class='col-xs-12'><a href='/tag/" + data.tags[i].tag + "'><ul class='list-unstyled'><li><h6>#" + data.tags[i].tag +
                        "</h6></li></ul></a></div></li>";
                        $('#search-results-dropdown').prepend($element);
                    }
                    $('#search-results-dropdown').prepend("<li class='row search-item'><div class='col-xs-12'><ul class='list-unstyled'><li><h6>Tags</h6></li></ul></div></li>");
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

$('#main-page-search-field').keyup(function() {
    if ($(this).val() != '') {
        $('#main-results-dropdown').show();
        $.ajax({
            url: '/search',
            type: 'GET',
            data: {
                'q': $(this).val()
            },
            success: function (data) {
                $('.search-item').remove();
                console.log(data);
                if (data.users.length != 0) {
                    $('#search-results').prepend("<li class='row search-item' id='user-search-items'></div></li>");
                    for (var i = 0; i < data.users.length; i++) {
                        $element = "<li class='search-item col-lg-6 col-md-6 col-sm-6 col-xs-12'><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'><img class='img-responsive img-circle' src='/avatars/" + data.users[i].profile_image +
                        "' alt=''></div><div class='col-lg-10 col-md-10 col-sm-10 col-xs-10'><a href='/" + data.users[i].username + "'><ul class='list-unstyled'><li><h6>" + data.users[i].name +
                        "</h6></li><li>@" + data.users[i].username + "</li></ul></a></div></li>";
                        $('#user-search-items').prepend($element);
                    }
                    $('#user-search-items').prepend("<li class='row search-item blue-text'><div class='col-xs-12'><ul class='list-unstyled'><li><h6>Users</h6></li></ul></div></li>");
                }
                if (data.tags.length == 0 && data.users.length == 0) {
                    $element = "<li class='row search-item'><a class='col-xs-12'>No Results Found</a></li>"
                    $('#search-results').prepend($element);
                }
                else {
                    $('#search-results').prepend("<li class='row search-item' id='tag-search-items'></div></li>");
                    for (var i = 0; i < data.tags.length; i++) {
                        $element = "<li class='search-item col-lg-4 col-md-4 col-sm-6 col-xs-12'><a href='/tag/" + data.tags[i].tag + "'><ul class='list-unstyled'><li><h6>#" + data.tags[i].tag +
                        "</h6></li></ul></a></li>";
                        $('#tag-search-items').prepend($element);
                    }
                    $('#tag-search-items').prepend("<li class='row search-item blue-text'><div class='col-xs-12'><ul class='list-unstyled'><li><h6>Hashtags</h6></li></ul></div></li>");
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
        $('#main-results-dropdown').hide();
    }
})
