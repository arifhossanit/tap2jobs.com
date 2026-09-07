/******/ (() => { // webpackBootstrap
/*!***************************************************************!*\
  !*** ./resources/assets/js/web/js/news_letter/news_letter.js ***!
  \***************************************************************/
listenSubmit('#newsLetterForm', function (event) {
  event.preventDefault();
  var email = $('#mc-email').val();
  var emailExp = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  var emailCheck = email == '' ? true : emailExp.test(email) ? true : false;
  if (!emailCheck) {
    displayErrorMessage(Lang.get("js.please_enter_a_valid_email"));
    return false;
  }
  // loadingButton.button('loading');
  processingBtn('#newsLetterForm', '#btnLetterSave', 'loading');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    url: $('#createNewLetterUrl').val(),
    type: 'post',
    data: new FormData($(this)[0]),
    processData: false,
    contentType: false,
    success: function success(result) {
      displaySuccessMessage(result.message);
    },
    error: function error(result) {
      displayErrorMessage(result.responseJSON.message);
    },
    complete: function complete() {
      $('#mc-email').val('');
      // loadingButton.button('reset');
      processingBtn('#newsLetterForm', '#btnLetterSave');
    }
  });
});
/******/ })()
;