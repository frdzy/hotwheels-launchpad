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
          var json = $.parseJSON(result);
          if (result != "no") {
            $("#lp_password_submit").attr("disabled", "disabled");
            $("#game").fadeIn(1000);
            ww.init(json);
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

