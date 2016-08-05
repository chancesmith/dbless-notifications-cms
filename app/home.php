<?php
$fileJSON = file_get_contents("notifications.json");
$notifications = json_decode( $fileJSON, true );
?>


<!DOCTYPE html>
<head>
	<meta charset="utf-8">

	<title>Notification with PHP | DBless</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- fonts -->
	<link href='https://fonts.googleapis.com/css?family=Passion+One:400,700,900|Oswald:400,700,300' rel='stylesheet' type='text/css'>

	<!-- styles -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/animate.css" />

	<style>
		.notification-box {
	    background-color: #b7c6cb;
	    position: fixed;
	    right: 5px;
	    bottom: 20px;
	    color: #000;
	    padding: 20px 20px 15px 22px;
	    border:none;
	    -webkit-border-top-right-radius: 5px;
	    -moz-border-radius: 5px;
	    border-radius: 5px;
	    background-repeat: no-repeat;
	    background-position: 7px center;
	    opacity: 0.7;
	    filter: alpha(opacity=70);
	    vertical-align: middle;
	    box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.35);
	    -webkit-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.35);
	    -moz-box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.35);
	    width:375px;
		}
		.notification-box .btn-primary {
		  color: #fff;
		  background-color: initial;
		  border-color: #c05e48;
		  background-color: #c05e48;
		  padding: 9px 12px;
		}
		.notification-box .btn-primary:hover {
		  border-color: rgba(192, 94, 72, 0.71);
		  background-color: rgba(192, 94, 72, 0.71);
		}
		#close {
			float: right;
			margin: -13px -3px 0 0;
			font-size: 16px;
			opacity: 0.4;
			cursor: pointer;
		}
	</style>

</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<h1>Homepage</h1>
				<a href="./#/">edit jobs</a>
			</div>
		</div>
	</div>
	<?php $cookieValuePHP = ""; ?>
	<?php if ($notifications != '' || isset($notifications)): ?>
		<?php foreach($notifications as $notification) { //foreach element in job list ?>
			<div class="notification-box">
				<span id="close"><i class="fa fa-times"></i></span>
				<p><?php echo $notification['text']; ?><p>
				<?php if ( !$notification['url'] !== "" && $notification['button'] !== "" ) :?>
					<a class="btn btn-primary" href="<?php echo $notification['url']; ?>"><?php echo $notification['btn']; ?></a>
				<?php endif; ?>
			</div>
			<?php $cookieValuePHP = $notification['editDate']; ?>
		<?php } ?>
	<?php else: ?>
		<div class="notification-box">
			<h2>Sorry, no career opportunities are avilable at this time. Come back later to see future udpates.</h2>
		</div>
	<?php endif ?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script>
		// vars
		var cookieName = "companyNotification";
		var cookieValue = "<?php echo $cookieValuePHP; ?>"; //php inputs last editDate
		var daysUntilExpired = 3; // num of days

		// cookie logic
		function createCookie(name,value,days) {
			if (days) {
				var date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				var expires = "; expires="+date.toUTCString();
			}
			else var expires = "";
			document.cookie = name+"="+value+expires+"; path=/";
		}

		function readCookie(name) {
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
			}
			return null;
		}

		function eraseCookie(name) {
			createCookie(name,"",-1);
		}

		function getCookie(name) {
			var dc = document.cookie;
			var prefix = name + "=";
			var begin = dc.indexOf("; " + prefix);
			if (begin == -1) {
				begin = dc.indexOf(prefix);
				if (begin != 0) return null;
			}
			else
			{
				begin += 2;
				var end = document.cookie.indexOf(";", begin);
				if (end == -1) {
					end = dc.length;
				}
			}
			return unescape(dc.substring(begin + prefix.length, end));
		}

		// check if needs to show notify-box
		var notificationCookie = getCookie(cookieName);

		if (notificationCookie == null) {
      // cookie doesn't exist
      // show notification box
			$('.notification-box').show();
    }
    else {
      // cookie exists + not expired
      // do nothing
    }

    // close notification box click
		$('.notification-box span#close').click(function() {
			// hide notification
			$('.notification-box').hide();
			// set cookie
			createCookie(cookieName,cookieValue,daysUntilExpired);
		});
	</script>
</body>
</html>