<!DOCTYPE html>
<!-- Website template by freewebsitetemplates.com -->
<html>
<head>
	<meta charset="UTF-8">
	<title>About</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">

	<script type="text/javascript" src="js/datepicker.js"></script>
    <link href="css/demo.css"       rel="stylesheet" type="text/css" />
    <link href="css/datepicker.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript">
//<![CDATA[

/*
        A "Reservation Date" example using two datePickers
        --------------------------------------------------

        * Functionality

        1. When the page loads:
                - We clear the value of the two inputs (to clear any values cached by the browser)
                - We set an "onchange" event handler on the startDate input to call the setReservationDates function
        2. When a start date is selected
                - We set the low range of the endDate datePicker to be the start date the user has just selected
                - If the endDate input already has a date stipulated and the date falls before the new start date then we clear the input's value

        * Caveats (aren't there always)

        - This demo has been written for dates that have NOT been split across three inputs

*/

function makeTwoChars(inp) {
        return String(inp).length < 2 ? "0" + inp : inp;
}

function initialiseInputs() {
        // Clear any old values from the inputs (that might be cached by the browser after a page reload)
        document.getElementById("sd").value = "";
        document.getElementById("ed").value = "";

        // Add the onchange event handler to the start date input
        datePickerController.addEvent(document.getElementById("sd"), "change", setReservationDates);
}

var initAttempts = 0;

function setReservationDates(e) {
        // Internet Explorer will not have created the datePickers yet so we poll the datePickerController Object using a setTimeout
        // until they become available (a maximum of ten times in case something has gone horribly wrong)

        try {
                var sd = datePickerController.getDatePicker("sd");
                var ed = datePickerController.getDatePicker("ed");
        } catch (err) {
                if(initAttempts++ < 10) setTimeout("setReservationDates()", 50);
                return;
        }

        // Check the value of the input is a date of the correct format
        var dt = datePickerController.dateFormat(this.value, sd.format.charAt(0) == "m");

        // If the input's value cannot be parsed as a valid date then return
        if(dt == 0) return;

        // At this stage we have a valid YYYYMMDD date

        // Grab the value set within the endDate input and parse it using the dateFormat method
        // N.B: The second parameter to the dateFormat function, if TRUE, tells the function to favour the m-d-y date format
        var edv = datePickerController.dateFormat(document.getElementById("ed").value, ed.format.charAt(0) == "m");

        // Set the low range of the second datePicker to be the date parsed from the first
        ed.setRangeLow( dt );
        
        // If theres a value already present within the end date input and it's smaller than the start date
        // then clear the end date value
        if(edv < dt) {
                document.getElementById("ed").value = "";
        }
}

function removeInputEvents() {
        // Remove the onchange event handler set within the function initialiseInputs
        datePickerController.removeEvent(document.getElementById("sd"), "change", setReservationDates);
}

datePickerController.addEvent(window, 'load', initialiseInputs);
datePickerController.addEvent(window, 'unload', removeInputEvents);

//]]>
</script>
</head>
<body>
	<div id="background">
		<div id="page">
			<div id="header">
				<div id="logo">
					<a href="index.html"><img src="images/log.png" alt="LOGO" height="112" width="118"></a>
				</div>
				<div id="navigation">
					<ul>
						<li class="selected">
							<a href="index.html">Home</a>
						</li>
						<li>
							<a href="about.html">About</a>
						</li>
						<li>
							<a href="rooms.html">Rooms</a>
						</li>
						<li>
							<a href="foods.html">Food</a>
						</li>
						<li>
							<a href="contact.html">Contact</a>
						</li>
						<li>
							<a href="reservation.html">Reservation</a>
						</li>
					</ul>
				</div>
			</div>
			<div id="contents">
				<div class="box">
					<div>


    <?php
        $arival = $_POST['start'];
        $departure = $_POST['end']; 
        $no_rooms = $_POST['no_rooms'];
        $roomid = $_POST['roomid'];
        $room_type = $_POST['room_type'];
        $result = $_POST['result'];
    ?>
    <form action="payment.php" method="post" style="margin-top: -31px;" onsubmit="return validateForm()" name="personal">
    
        <input name="start" type="hidden" value="<?php echo $arival; ?>" />
        <input name="end" type="hidden" value="<?php echo $departure; ?>" />
        <input name="n_room" type="hidden" value="<?php echo $no_rooms; ?>" />
        <input name="rm_id" type="hidden" value="<?php echo $roomid; ?>" />
        <input name="result" type="hidden" value="<?php echo $result; ?>" />
    <?php
    if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
        echo '<ul class="err">';
        foreach($_SESSION['ERRMSG_ARR'] as $msg) {
            echo '<li>',$msg,'</li>'; 
        }
        echo '</ul>';
        unset($_SESSION['ERRMSG_ARR']);
    }
    ?>
    
    <br />
        <input name="name" type="text" class="ed" id="name" placeholder="FirstName" /> 
        <input name="last" type="text" class="ed" id="last" placeholder="LastName"/> <br />
        <input name="email" type="text" class="ed" id="email" placeholder="E-mail"/> 
        <input name="cemail" type="text" class="ed" id="cemail" placeholder="Confirm E-mail"/> <br />
        <input name="password" type="text" class="ed" id="password" placeholder="Choose a password"/> 
        <input name="cnumber" type="text" class="ed" id="cnumber" placeholder="Phone Number"/><span id="errmsg1"></span>
    <br />
    
    <input name="but" type="submit" value="Confirm" />
  </form>
  
  <div class="reservation">
      <div align="center" style="padding-top: 7px; font-size:24px;"><strong>RESERVATION  DETAILS</strong></div>
        <div style="margin-top: 14px;">
            <label style="margin-left: 16px;">Check In Date : <?php echo $arival; ?></label><br />
            <label style="margin-left: 3px;">Check Out Date : <?php echo $departure; ?></label><br />
            <label style="margin-left: -12px;">Number of Rooms : <?php echo $no_rooms; ?></label><br />
            <label style="margin-left: 53px;">Room TYPE : <?php echo $room_type; ?></label><br />
            <label style="margin-left: -9px;">Number Of Nights : <?php echo $result; ?></label><br />
        </div>
    </div>
    




						
					</div>
				</div>
			</div>
		</div>
		
			
		</div>
	</div>
</body>
</html>