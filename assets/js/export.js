  //$('#change').replaceWith('');
  var checkSelectOption1 = "no";
  var checkSelectOption2 = "no";
  var checkCaptcha = "no";
  $("input:radio[name='option[]']").on('click', function () {
      var $box1 = $(this);
      if ($box1.is(":checked")) {
          var group1 = "input:radio[name='option[]']";
          console.log(group1);
          $(group1).prop("checked", false);
          $box1.prop("checked", true);
          checkSelectOption1 = "yes";
          $('#divCaptcha').removeAttr('hidden');
      } else {
          $box1.prop("checked", false);
      }
  });
  $(function () {
      $("#downloadLink").click(function () {
          //Version Demo
          //window.location =
          //    "https://docs.google.com/forms/d/1lHiGRgmimU2ss2qawR1yY3TLOzr9z0Sgk4xaIEDivTg";
          window.open(
              'https://docs.google.com/forms/d/1lHiGRgmimU2ss2qawR1yY3TLOzr9z0Sgk4xaIEDivTg',
              '_blank'
          );
      });
  });
  function callback() {
      if (checkSelectOption1 == "yes") {
          $("#downloadLink").removeAttr('disabled');
      }
  }