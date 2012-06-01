function alertTime() {
  $.ajax({
    type: "GET",
    url: "app/time.php",
    data: "",
    success: function(result) {
      console.log(result);
      if (result != 0) {
        window.location = result;
      }
    }
  });
}
var timeAt3pm = new Date("5/24/2012 11:50:00 AM").getTime()
  , timeNow = new Date().getTime()
  , offsetMillis = timeAt3pm - timeNow;
setTimeout('alertTime()', offsetMillis);
console.log(offsetMillis);
