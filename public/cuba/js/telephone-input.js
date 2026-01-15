(function () {
  var input = document.querySelector("#country");
  var iti = window.intlTelInput(input, {
    // separateDialCode:true,
    utilsScript:
      "static/js/utils.js",
  });

  // store the instance variable so we can access it in the console e.g. window.iti.getNumber()
  window.iti = iti;
})();
