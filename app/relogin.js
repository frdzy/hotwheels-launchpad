function relogin (team_id, team_password) {
  $.ajax({
    type: "GET",
    url: "app/entry.php",
    data: {request: "password", value: team_password},
    success: function(result){
      if (result != "no") {
        $("#lp_password_submit").attr("disabled", "disabled");
        $("#game").fadeIn(1000);
        ww.init(result);
        console.log("reiniting");
      }
      else {
        $('.success').fadeOut(200).hide();
        $('.error').fadeOut(200).show();
      }
    }
  });
}

