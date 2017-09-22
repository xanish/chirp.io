var HASHTAG_REGEX = /#([a-zA-Z]+[0-9]*)+/gi;
var EMOJI_REGEX = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|[\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|[\ud83c[\ude32-\ude3a]|[\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
var $newtweetbuffer = " ";
var tweetcounter = 0;
var currentuser;

var messageFail = '<div id="fail" class="alert alert-danger float-success"><h6>Try Again Later</h6></div>';

try {
    if ($('#'+color).length > 0) {
        $('#'+color).prop('checked', true);
    }
} catch (e) {

} finally {

}

$('#search-results-dropdown').hide();
$('#password-strength-meter').hide();
$('#main-results-dropdown').hide();
try {
    document.getElementById('tweet_image_file').onchange = function () {
        try {
            $('#attach').remove();
        } catch(e) {}
        $upload =   '<div class="alert alert-success row" id="attach"><ul id="attach-item"><li><i class="material-icons">attach_file</i>' +
                    this.files.item(0).name +
                    '<i class="material-icons remove-attach-file">close</i>' +
                    '</li></ul>' +
                    '</div>';
        var files = !!this.files ? this.files : [];
        if (/^image/.test( files[0].type)) { // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function() { // set image data as background of div
                $("#imagePreview").css("background-image", "url("+this.result+")");
            }
        }
        $('#tweetform').append($upload);
        $('#attach-item').append('<li><div id="imagePreview"></div></li>');
    };
} catch(e) {

}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var text_max = 150;
$('#count_message').html(text_max);

function load_popular_tags() {
    $('#populartagloading').show();
    $.ajax({
        url: '/popular_tags',
        type: 'GET',
        success: function (data) {
            data.forEach(function(element){
                $('#popular-tags').append('<tr><td><a href="/tag/' + element.tag + '/tweets">' + '#' + element.tag + '</a></td><td>' + element.tag_count + '</td></tr>')
            });
        },
        error: function (xhr) {
            $('#popular-tags').append('<h6 class="text-center">Unable To Load Popular Tweets Try Again Later</h6>');
        },
        complete: function () {
            $("#populartagloading").hide();
        }
    });
}

// ajax tweet post
$('#form').submit(function() {
    $('#tweet-button').attr('disabled', 'disabled');
    $('#RESPONSE_MSG').html('');
    var formData = new FormData($(this)[0]);
    var hashtags = ($('#tweetbox').val()).match(HASHTAG_REGEX);
    //console.log(hashtags);
    formData.append('hashtags', JSON.stringify(hashtags));
    $.ajax({
        url: '/tweet',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $("#notweetmessageprofile").hide();
            $response = '';
            console.log(data);
            $("#tweeteditor").html('');
            $("#tweetbox").keyup();
            $("#tweet_image_file").val('');
            $('#count_message').html(text_max);
            $('#tweet-button').attr('disabled', 'disabled');
            $reponse =   tweetBuilder(data.element.id,
                                      data.element.avatar,
                                      data.element.name,
                                      data.element.username,
                                      getFormattedDate(data.element.date),
                                      data.element.text,
                                      data.element.tags,
                                      data.element.image,
                                      data.element.original,
                                      null,
                                      0
                                     );
            if(currentuser == data.element.username) {
              $("#feed-tweet").prepend($response);
              $("#count-bar").load(' #navcount');
            }
            if($("#feed").length) {
                $("#count-bar").load(' #navcount');
                $("#feed").prepend($response);
            }
            $('#attach').remove();
            $successmsg = '<div class="alert alert-success" id="postsuccess"><ul><li>Posted Successfully</li></ul></div>';
            $('#tweetform').append($successmsg);
            $('#postsuccess').fadeOut(2000, function () {
                $(this).remove();
            })
        },
        error: function(jqXHR, xhr) {
            if (jqXHR.status === 422) {
                $errors = jqXHR.responseJSON;
                console.log($errors);
                $errorsHtml = '';
                $.each( $errors, function( key, value ) {
                    $errorsHtml += '<li>' + value[0] + '</li>';
                });
                $("#ERRMSG ul").html($errorsHtml);
                $('#ERRMSG').stop(false, true);
                $('#ERRMSG').show();
                $('#tweet-button').attr('disabled', false);
                $('#ERRMSG').fadeOut(6000, function() {
                    $('#ERRMSG').hide();
                });
                //console.log(xhr);
            }
            console.log(xhr);
            console.log(jqXHR.status);
            if(jqXHR.status == 401 || jqXHR.status == 500) {
                redirectToLogin();
            }
        },
        complete: function() {
            if(__lastid == null && $('#feed-tweet').length) {
                showBackToTop();
            }
        }
    });
    return false;
});

function getText( obj ) {
    return obj.textContent ? obj.textContent : obj.innerText;
}

$(document).ready(function() {
    try {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            defaultValue: bdate,
        });
    } catch (e) {

    } finally {

    }

    $('body').on('click', '.tweetimage', function() {
        $(window).disablescroll();
    });

    $('body').on('click', '.remove-attach-file', function() {
        $("#tweet_image_file").val('');
        $('#attach').remove();
        $('#imagePreview').remove();
    });

    if ($("#popular-tags").length > 0) {
        load_popular_tags();
    }

    if ($("#success-update-msg").length > 0) {
        $("#success-update-msg").fadeOut(5000, function() {
            $("#success-update-msg").remove();
        });
    }

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

                    var count = $('#count_message');
                    var emojis = $("#tweeteditor > div > img").length + $("#tweeteditor > img").length;
                    var characters = $(editor).text().length;
                    var newlines = $('#tweeteditor div').length;
                    var checker = $("#tweeteditor").contents().first();

                    if(checker.html() == "<br>") {
                        if (!!newlines) newlines -= 1;
                    }

                    characters += newlines + emojis;

                    if (characters > (text_max - 11)) {
                        count.addClass('over');
                    } else {
                        count.removeClass('over');
                    }

                    var text_remaining = text_max - characters;
                    if(text_remaining < 0)
                    {
                        $('#tweet-button').attr('disabled', 'disabled');
                        $("#count_message").css("color", "red");
                    }
                    else {
                        $("#count_message").css("color", "");
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
        // To prevent emojioneArea is not a function error.
    }

$("body").tooltip({ selector: '[data-toggle=tooltip]' });

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

if ( $('#searchfeed').length ) {
    unbindscroll();
    $("#loading").show();
    loadSearchedByTagTweets(null);
}

if ( $('#welcomefeed').length ) {
    loadLatestTweetsOnWelcomePage();
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

$(document).on('click', '.likes', function() {
    $id = $(this).attr('id');
    like_unlike($id, '/like/', 'POST', 'likes', 'unlikes', 'favorite', 1, 'Unlike');
    return false;
});

$(document).on('click', '.unlikes', function() {
    $id = $(this).attr('id');
    like_unlike($id, '/unlike/', 'DELETE', 'unlikes', 'likes', 'favorite_border', -1, 'Like');
    return false;
});

$(document).on('click', '.follow', function() {
    $id = $(this).attr('id');
    follow_unfollow($id, '/follow/', 'POST', 'unfollow', 'follow', 'btn-danger', 'btn-default', 'Unfollow');
    return false;
});

$(document).on('click', '.unfollow', function() {
    $id = $(this).attr('id');
    follow_unfollow($id, '/unfollow/', 'DELETE', 'follow', 'unfollow', 'btn-default', 'btn-danger', 'Follow');
    return false;
});

});

function getFormattedDate(timestamp) {
    var d = new Date(parseInt(timestamp)*1000);
    var options = {
        weekday: "short", year: "numeric", month: "short",
        day: "numeric", hour: "2-digit", minute: "2-digit"
    };
    return d.toLocaleTimeString("en-us", options);
}

var __lastid;
function loadTweet(_lastid) {
    try {
        $.ajax({
            url: '/gettweets',
            type: 'GET',
            data: {
                username : _username,
                lastid : _lastid
            },
            success: function(data) {
                console.log(data);
                var $finaldata = " ";
                if(data.posts.length != 0) {
                    tweetcounter += data.posts.length;
                    currentuser = data.user.username;
                }
                for( i=0; i<data.posts.length; i++ ) {
                    $finaldata += tweetBuilder(data.posts[i].id,
                        data.user.profile_image,
                        data.user.name,
                        data.user.username,
                        getFormattedDate(data.posts[i].created_at),
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
    try {
        $.ajax({
            url: '/getfeed',
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
                        getFormattedDate(data.posts[i].created_at),
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
    }catch(e){}
    return false;
};

var __searchbytaglastid;
function loadSearchedByTagTweets(_searchbytaglastid) {
    try {
        $.ajax({
            url: '/getsearchbytagtweets',
            type: 'GET',
            data: {
                tag : _tag,
                lastid : _searchbytaglastid
            },
            success: function(data) {
                console.log(data);
                console.log('tag : ' + _tag);
                var $finaldata = " ";
                if(data.posts.length != 0) {
                    //$("#notweetmessageprofile").hide();
                    tweetcounter += data.posts.length;
                }
                for( i=0; i<data.posts.length; i++ ) {
                    $finaldata += tweetBuilder(data.posts[i].id,
                        data.posts[i].profile_image,
                        data.posts[i].name,
                        data.posts[i].username,
                        getFormattedDate(data.posts[i].created_at),
                        data.posts[i].text,
                        data.tags,
                        data.posts[i].tweet_image,
                        data.posts[i].original_image,
                        data.liked,
                        data.posts[i].likes
                    );
                    __searchbytaglastid = data.posts[i].id;
                    //$finaldata = $finaldata + $response;
                }
                if(data.posts.length == 0) {
                    __searchbytaglastid = null;
                }
                $("#searchfeed").append($finaldata);
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
                    $("#tagname").html('#' + _tag);
                    $("#notweetmessage").show();
                }
                if(__searchbytaglastid == null) {
                    //$("#loading").hide();
                    showBackToTop();
                }
            }
        });
    }catch(e) {}
    return false;
};

function loadLatestTweetsOnWelcomePage() {
    try {
        $("#latesttweetloading").show();
        $.ajax({
            url: '/getlatesttweets',
            type: 'GET',
            success: function(data) {
                console.log(data);
                var $finaldata = " ";
                for( i=0; i<data.length; i++ ) {
                    $finaldata += tweetBuilder(data[i].id,
                        data[i].profile_image,
                        data[i].name,
                        data[i].username,
                        getFormattedDate(data[i].created_at),
                        data[i].text,
                        data[i].tags,
                        data[i].tweet_image,
                        data[i].original_image,
                        -1,
                        data[i].likes
                    );
                }
                $("#welcomefeed").append($finaldata);
            },
            complete: function() {
                $("#latesttweetloading").hide();
            }
        });
    }catch(e) {}
    return false;
};

function backtotop() {
    $('html, body').animate({scrollTop : 0},600);
    return false;
}

function bindscroll() {
    $(window).scroll(function() {
        if( $(window).scrollTop() + $(window).height() + 2 >= $(document).height() ) { //scrolled to bottom of the page
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

            if(__searchbytaglastid != null) {
                unbindscroll();
                $("#loading").show();
                loadSearchedByTagTweets(__searchbytaglastid);
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
        var emoji = new EmojiConvertor();
        chirptext = emoji.replace_unified(chirptext);
        if(jQuery.inArray(chirptext, tagArr) != -1) {
            var taggedtext = ltrim(chirptext, '#');
            $response += "<a href='/tag/" + taggedtext + "/tweets'>" + chirptext + "</a>" + " ";
        }
        else if(chirptext == "") {
            $response += "&nbsp;";
        }
        else {
            $response += chirptext + " ";
        }
    }
}

function addLikes(likedArr, likescount, id) {
    if(likedArr != -1) {
        if(jQuery.inArray(id, likedArr) != -1) {
            $response += "<div class='card-action'>" + "<h6><a class='red-text unlikes' id='" + id + "'><i class='material-icons' data-toggle='tooltip' data-placement='bottom' title='Unlike'>favorite</i> <span>" + likescount + "</span></a></h6></div>";
        }
        else {
            $response += "<div class='card-action'>" + "<h6><a class='red-text likes' id='" + id + "'><i class='material-icons' data-toggle='tooltip' data-placement='bottom' title='Like'>favorite_border</i> <span>" + likescount + "</span></a></h6></div>";
        }
    }
    else {
        $response += "<div class='card-action'>" + "<h6><a class='red-text'  href='/login'><i class='material-icons'>favorite_border</i> <span>" + likescount + "</span></a></h6></div>";
    }
}

function showBackToTop() {
    if ($('#feed-tweet').outerHeight(true) || $('#feed').outerHeight(true) || $('#searchfeed').outerHeight(true) > $(window).height()) {
        $('.stream-end').show();
    }
}

function tweetBuilder(id, profile_image, name, username, created_at, textArr, tagArr, tweet_image, original_image, likedArr, likescount) {
    $response =   "<div class='card hoverable overflow'>" +
    "<div class='card-content'>" +
    "<div class='row'>" +
    "<div class='col-lg-2 col-md-2 col-sm-2 col-xs-3'>" +
    "<img class='img-responsive img-circle' src='/" + profile_image + "' alt=''>" +
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

    if (tweet_image != '/tweet_images/') {
        $response += "<div class='chirp_image hidden-xs'>" +
        "<a href='/" + original_image + "' data-lightbox='box-" + id + "'>" +
        "<img class='tweetimage img-responsive' src='/" + tweet_image + "' class='img-responsive hidden-xs lightboxed' alt=''>" +
        "</a>" +
        "</div>";
    }

    $response += "</div>";

    if (tweet_image != '/tweet_images/') {
        $response += "<div class='col-xs-12 visible-xs'>" +
        "<a href='/" + original_image + "' data-lightbox='box-" + id + "-mini'>" +
        "<img class='tweetimage img-responsive center-block' src='/" + tweet_image + "' class='img-responsive visible-xs lightboxed' alt=''>" +
        "</a>" +
        "</div>";
    }

    $response += "</div>" +
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
        var criteria =  $(this).val();
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
                    $('#search-results-dropdown').prepend("<li class='row search-item'><div class='col-xs-12'><ul class='list-unstyled'><li><a href='/search/" + criteria + "'><h6>Show All Matching Users</h6></a></li></ul></div></li>");
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
                    $('#search-results-dropdown').prepend("<li class='row search-item'><div class='col-xs-12'><ul class='list-unstyled'><li><a href='/tag/" + criteria + "'><h6>Show All Matching Tags</h6></a></li></ul></div></li>");
                    for (var i = 0; i < data.tags.length; i++) {
                        $element = "<li class='row search-item'><div class='col-xs-12 highlight'><a href='/tag/" + data.tags[i].tag + "/tweets'><ul class='list-unstyled'><li><h6>#" + data.tags[i].tag +
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
                        $element = "<li class='search-item col-lg-6 col-md-6 col-sm-6 col-xs-12'><div class='col-lg-2 col-md-2 col-sm-2 col-xs-3'><img class='img-responsive img-circle' src='/avatars/" + data.users[i].profile_image +
                        "' alt=''></div><div class='col-lg-10 col-md-10 col-sm-10 col-xs-9'><a href='/" + data.users[i].username + "'><ul class='list-unstyled'><li><h6>" + data.users[i].name +
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
                        $element = "<li class='search-item col-lg-4 col-md-4 col-sm-6 col-xs-12'><a href='/tag/" + data.tags[i].tag + "/tweets'><ul class='list-unstyled'><li><h6>#" + data.tags[i].tag +
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

// password strength indicator
var strength = {
    0: "<span class='red-text'>Worst</span>",
    1: "<span class='pink-text'>Bad</span>",
    2: "<span class='orange-text'>Weak</span>",
    3: "<span class='light-green-text'>Good</span>",
    4: "<span class='green-text'>Strong</span>"
}
var password = document.getElementById('password');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');
try {
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
}
catch (e) {

}

function follow_unfollow(id, url, method, add_class, remove_class, add_class_btn, remove_class_btn, text) {
    $.ajax({
        url: url + id,
        type: method,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $("#count-bar").load(' #navcount');
            $('#' + $id).removeClass(remove_class).addClass(add_class);
            $('#' + $id).removeClass(remove_class_btn).addClass(add_class_btn);
            $('#' + $id).text(text);
        },
        error: function (jqXHR, xhr) {
            $('#app').append(messageFail);
            $('#fail').fadeOut(5000, function () {
                $(this).remove();
            });
            if(jqXHR.status == 401) {
                redirectToLogin();
            }
        }
    });
}

function like_unlike(id, url, method, remove_class, add_class, icon, increment, title) {
    $.ajax({
        url: url + id,
        type: method,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);
            $('#' + $id).removeClass(remove_class).addClass(add_class);
            $('#' + $id + ' i.material-icons').text(icon);
            $current = $('#' + $id + ' span').text();
            $('#' + $id + ' span').text(parseInt($current) + increment);
            $('#' + $id).children().first().attr('title', title).tooltip('fixTitle').tooltip('show');
        },
        error: function (jqXHR, xhr) {
            $('#app').append(messageFail);
            $('#fail').fadeOut(5000, function () {
                $(this).remove();
            });
            if(jqXHR.status === 401 || jqXHR.status === 500) {
                redirectToLogin();
            }
        }
    });
}
