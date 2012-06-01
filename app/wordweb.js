var labelType, useGradients, nativeTextSupport, animate, ww;

ww = new WordWeb();

// console.log(ww.getFd());

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};

function WordWeb() {
  var fd = 'uninitialized';

  this.getFd = function() {
    return fd;
  }

  this.init = function (json_str){
    var json = $.parseJSON(json_str);
    // init data
    // end
    // init ForceDirected
    fd = new $jit.ForceDirected({
      //id of the visualization container
      injectInto: 'infovis',
      //Enable zooming and panning
      //by scrolling and DnD
      Navigation: {
        enable: true,
        //Enable panning events only if we're dragging the empty
        //canvas (and not connections)
        panning: 'avoid nodes',
        zooming: 10 //zoom speed. higher is more sensible
      },
      // Change node and edge styles such as
      // color and width.
      // These properties are also set per node
      // with dollar prefixed data-properties in the
      // JSON structure.
      Node: {
        overridable: true
      },
      Edge: {
        overridable: true,
        color: '#23A4FF',
        lineWidth: 0.4
      },
      //Native canvas text styling
      Label: {
        type: labelType, //Native or HTML
        size: 10,
        style: 'bold'
      },
      //Add Tips
      Tips: {
        enable: true,
        onShow: function(tip, node) {
        //count connections
        var count = 0;
        node.eachAdjacency(function() { count++; });
        //display node info in tooltip
        tip.innerHTML = "<div class=\"tip-title\">" + node.name + "</div>"
          + "<div class=\"tip-text\"><b>connections:</b> " + count + "</div>";
        }
      },
      // Add node events
      Events: {
        enable: true,
        type: 'Native',
        //Change cursor style when hovering a node
        onMouseEnter: function() {
          fd.canvas.getElement().style.cursor = 'move';
        },
        onMouseLeave: function() {
          fd.canvas.getElement().style.cursor = '';
        },
        //Update node positions when dragged
        onDragMove: function(node, eventInfo, e) {
            var pos = eventInfo.getPos();
            node.pos.setc(pos.x, pos.y);
            fd.plot();
        },
        //Implement the same handler for touchscreens
        onTouchMove: function(node, eventInfo, e) {
          $jit.util.event.stop(e); //stop default touchmove event
          this.onDragMove(node, eventInfo, e);
        },
        //Add also a click handler to nodes
        onClick: function(node) {
          /*
          $.ajax({
            type: "GET",
            url: "app/entry.php",
            data: {request: "update"},
            success: function(result){
              if (result != "no") {
                $("#lp_next_phrase_submit").attr("disabled", "disabled");
              }
              else {
                $('.next_phrase_success').fadeOut(200).hide();
                $('.next_phrase_error').fadeOut(200).show();
              }
            }
          });
          */
        
          if(true) return;
          // Build the right column relations list.
          // This is done by traversing the clicked node connections.
          var html = "<h4>" + node.name + "</h4><b> connections:</b><ul><li>",
              list = [];
          fd.animate({
            modes: ['linear'],
            transition: $jit.Trans.Elastic.easeOut,
            duration: 2500
          });
          node.eachAdjacency(function(adj){
            list.push(adj.nodeTo.name);
          });
          //append connections information
          $jit.id('inner-details').innerHTML = html + list.join("</li><li>") + "</li></ul>";
        }
      },
      //Number of iterations for the FD algorithm
      iterations: 200,
      //Edge length
      levelDistance: 130,
      // Add text to the labels. This method is only triggered
      // on label creation and only for DOM labels (not native canvas ones).
      onCreateLabel: function(domElement, node){
        domElement.innerHTML = node.name;
        var style = domElement.style;
        style.fontSize = "0.8em";
        style.color = "#ddd";
      },
      // Change node styles when DOM labels are placed
      // or moved.
      onPlaceLabel: function(domElement, node){
        var style = domElement.style;
        var left = parseInt(style.left);
        var top = parseInt(style.top);
        var w = domElement.offsetWidth;
        style.left = (left - w / 2) + 'px';
        style.top = (top + 10) + 'px';
        style.display = '';
      }
    });
    // load JSON data.
    fd.loadJSON(json);
    // compute positions incrementally and animate.
    fd.computeIncremental({
      iter: 40,
      property: 'end',
      onStep: function(perc){
        Log.write(perc + '% loaded...');
      },
      onComplete: function(){
        Log.write('Bigger Faster Stronger');
        fd.animate({
          modes: ['linear'],
          transition: $jit.Trans.Elastic.easeOut,
          duration: 2500
        });
      }
    });

    $.ajax({
      type: "GET",
      url: "app/entry.php",
      data: {request: "reveal", value: ''},
      success: function(result){
        fd.graph.nodes['1'].name = 'rocket';

        // console.log(result);
        var rows = $.parseJSON(result);
        // console.log(rows);
        for (i in rows) {
          row=rows[i];
          // console.log("row = " + row);
          fd.graph.nodes[row['0']].name = row['1'];
        }
        fd.animate({
          modes: ['linear'],
          transition: $jit.Trans.Elastic.easeOut,
          duration: 500
        });
      }
    });
  // end
  };

  /*
  var delay = 1;
  var func = function () {
    $.ajax(
      {
        type: "GET",
        url: "app/update.php",
        data: {request: "password", value: password},
        success: function(result){
          if (result != "no") {
            $("#lp_next_phrase_submit").attr("disabled", "disabled");
          }
          else {
            $('.next_phrase_success').fadeOut(200).hide();
            $('.next_phrase_error').fadeOut(200).show();
          }
        }
      }
  };

  setInterval(func, delay);
  */
}


$(function() {
  $("#lp_next_phrase_submit").click(function() {

    var password = $("#lp_next_phrase").val();
    if(password=='') {
      $('.next_phrase_success').fadeOut(200).hide();
      $('.next_phrase_error').fadeOut(200).show();
    }
    else {
      $.ajax({
        type: "GET",
        url: "app/entry.php",
        data: {request: "phrase", value: password},
        success: function(result_str){

          if (result_str != "no") {
          var result = $.parseJSON(result_str);
          if (result['redirect']) {
            window.location = result['redirect'];
          }
          else  {
            console.log("new_id" + result);
            if (result != "no" && result['result']) {
              var new_id = result['result'];
              var value = result['value'];
              fd = ww.getFd();
              $('.next_phrase_error').fadeOut(200).hide();
              $('.next_phrase_success').html("Found <u>" + value + "</u>");
              $('.next_phrase_success').fadeOut(200).show();
              fd.graph.nodes[new_id].name = value;
              fd.animate({
                modes: ['linear'],
                transition: $jit.Trans.Elastic.easeOut,
                duration: 500
              });
            $.ajax({
              type: "GET",
              url: "app/entry.php",
              data: {request: "reveal", value: ''},
              success: function(result){
                fd.graph.nodes['1'].name = 'rocket';

                var rows = $.parseJSON(result);
                for (i in rows) {
                  row=rows[i];
                  console.log("row = " + row);
                  fd.graph.nodes[row['0']].name = row['1'];
                }

                fd.animate({
                  modes: ['linear'],
                  transition: $jit.Trans.Elastic.easeOut,
                  duration: 500
                });
              }
            });
                        

            }
            else {
              $('.next_phrase_success').fadeOut(200).hide();
              $('.next_phrase_error').fadeOut(200).show();
            }
          }
        }

          else {
            $('.next_phrase_success').fadeOut(200).hide();
            $('.next_phrase_error').fadeOut(200).show();
          }


        }
      });
    }

    return false;
  });
});

