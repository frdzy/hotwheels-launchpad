$(function() {
  $("#lp_password_submit").click(function() {

    var password = $("#lp_password").val();

    if(password=='') {
      $('.success').fadeOut(200).hide();
      $('.error').fadeOut(200).show();
    }
    else {
      $.ajax({
        type: "GET",
        url: "app/entry.php",
        data: {request: "password", value: password},
        success: function(result){
          if (result != "no") {
            // console.log('result' + result);
            $("#lp_password_submit").attr("disabled", "disabled");
            $("#failtext").fadeIn(500);
            $("#game").fadeIn(1000);
            ww.init(result);
            console.log("initing");
          }
          else {
            $('.success').fadeOut(200).hide();
            $('.error').fadeOut(200).show();
          }
        }
      });

    }

    return false;
  });
});

