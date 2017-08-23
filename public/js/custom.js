var text_max = 150;
var fileData;

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

// jQuery stuff
$(document).ready(function () {

  $('#search-results-dropdown').hide();
  $('#password-strength-meter').hide();
  $('ul.pagination').hide();
  $('#count_message').html(text_max);

  // init jScroll on start
  scrolling();

  // Display reemaining characters on tweetbox
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

  // edit profile ajax
  $('#edit-profile').submit(function() {
    formData = new FormData($(this)[0]);
    formData.set("profile_image", fileData, $('#file').val());
    console.log(formData);
    $.ajax({
      url: 'edit-profile',
      type: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function(data) {
        console.log("Hurray");
      },
    error: function(xhr) {
      console.log(xhr);
    }
  });
  return false;
});
;
// ajax search
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
});

// autocomplete for cities and countries
$.fn.autoComplete = function(options) {
  try {
    var autocompleteService = new google.maps.places.AutocompleteService();
  } catch (e) {
    console.log("Google maps script not available.");
  }
  var predictionsDropDown = $('<div class="autocomplete"></div>').appendTo('body');
  var input = this;
  input.keydown(function() {
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
});

// jScroll setup
function scrolling(){
  try {
    if(tweetcount > 20) {
      var loader = '<div class="sk-wave"><div class="sk-rect sk-rect1"></div><div class="sk-rect sk-rect2"></div><div class="sk-rect sk-rect3"></div><div class="sk-rect sk-rect4"></div><div class="sk-rect sk-rect5"></div></div>';
      $('#feed-posts,#feed').jscroll({
        autoTrigger: true,
        loadingHtml: loader,
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: '#feed-posts,#feed',
        callback: function() {
          $('ul.pagination').remove();
        }
      });
    }
  }
  catch(e) {
    console.log("tweetcount not present on this page");
  }
};

// password strength indicator
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
  console.log("Password input not found.");
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
    console.log("Can't set css property for dropdown as it is not available on this page.");
  }
}

// cropbox
window.onload = function() {
  try {
    var options =
    {
      imageBox: '.imageBox',
      thumbBox: '.thumbBox',
      spinner: '.spinner',
      imgSrc: profile_image,
    }
    var cropper = new cropbox(options);
    document.querySelector('#file').addEventListener('change', function(){
      var reader = new FileReader();
      reader.onload = function(e) {
        options.imgSrc = e.target.result;
        cropper = new cropbox(options);
      }
      reader.readAsDataURL(this.files[0]);
      this.files = [];
    })
    fileData = cropper.getBlob();
    document.querySelector('#btnCrop').addEventListener('click', function(){
      var img = cropper.getDataURL()
      document.querySelector('.cropped').innerHTML += '<img src="'+img+'">';
    })
    document.querySelector('#btnZoomIn').addEventListener('click', function(){
      cropper.zoomIn();
    })
    document.querySelector('#btnZoomOut').addEventListener('click', function(){
      cropper.zoomOut();
    })
  }
  catch (e) {
    console.log("Profile image not defined.");
  }
};
