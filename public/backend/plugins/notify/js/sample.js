// Generated by CoffeeScript 2.1.0
(function () {
  $(function () {
    $.growl({
      title: "Growl",
      message: "Hi I'm Noa!"
    });

	 $('.error').click(function (event) {
      event.preventDefault();
      event.stopPropagation();
      return $.growl.error({
        message: "please check Your details ...file is missing"
      });
    });
    $('.notice').click(function (event) {
      event.preventDefault();
      event.stopPropagation();
      return $.growl.notice({
        message: "You have 4 notification"
      });
    });
    return $('.warning').click(function (event) {
      event.preventDefault();
      event.stopPropagation();
      return $.growl.warning({
        message: "read all details carefully"
      });
    });
  });
}).call(this);

		 function not1(){
        notif({
				msg: "<b>Success:</b> Well done Details Submitted Successfully",
				type: "success"
			});
        }
        function not2(){
            notif({
				msg: "<b>Oops!</b> An Error Occurred",
				type: "error",
				position: "center"
			});
        }
        function not3(){
            notif({
				type: "warning",
				msg: "<b>Warning:</b> Something Went Wrong",
				position: "left"
			});
        }
        function not4(){
            notif({
				type: "info",
				msg: "<b>Info: </b>Some info here.",
				width: "all",
				height: 70,
				position: "center"
			});
        }
        function not5(){
        notif({
				type: "error",
				msg: "<b>Error: </b>This error will stay here until you click it.",
				position: "center",
				width: 500,
				height: 60,
				autohide: false
			});
        }
		function not6(){
			notif({
				type: "warning",
				msg: "Opacity is cool!",
				position: "center",
				opacity: 0.8
			});
		}
    function not7(){
			notif({
        type: "primary",
				msg: "Default Bottom Notification",
        position: "bottom",
        bottom:'10'
			});
		}
    function not13() {
      notif({
        type: "info",
        msg: "Testing a multiline text. Testing, one, two.. More.",
        position: "center",
        width: 150,
        autohide: false,
        multiline: true
      });
    }
    function not14() {
      notif({
        type: "success",
        msg: "Fade mode activated.",
        position: "right",
        fade: true
      });
    }
    
    function not15() {
      notif({
        msg: "Customize with your favourite color!",
        position: "left",
        bgcolor: "#FFA18A",
        color: "#fff"
      });
    }
    
    function not16() {
      notif({
        type: 'info',
        msg: "Customize the timeout!",
        position: "left",
        time: 1000
      });
    }
    function not17() {
      var myCallback = function(choice){
        if(choice){
          notif({
            'type': 't-success',
            'msg': 'Yeah!',
            'position': 'center'
          })
        }else{
          notif({
            'type': 't-error',
            'msg': '<i class="far fa-sad-tear"></i>',
            'position': 'center'
          })
        }
      }
    
      notif_confirm({
        'textaccept': 'Stay Here',
        'textcancel': 'Close The Window',
        'message': 'Are you Sure You Want to Close?',
        'callback': myCallback
      })
    }
    function not18() {
      var myCallback = function(input){
        if(input){
          notif({
            'type': 't-success',
            'msg': input,
            'position': 'center'
          })
        }else{
          notif({
            'type': 't-error',
            'msg': 'Empty or cancelled',
            'position': 'center'
          })
        }
      }
    
      notif_confirm({
        'textaccept': 'That\'s it!',
        'textcancel': 'I don\'t have a pet :(',
        'message': 'What\'s your pet\'s name?',
        'callback': myCallback
      })
    }