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
          init();
          console.log("initing");
        }
      });
    }
    return false;
  });
});

