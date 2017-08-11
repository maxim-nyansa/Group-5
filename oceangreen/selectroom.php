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

	<?php
		$arival = $_POST['start'];
		$departure = $_POST['end'];
	?>
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

					<form action="guestinfo.php" method="post" onsubmit="return validateForm()" name="room">
	<input name="start" type="hidden" value="<?php echo $arival; ?>" />
	<input name="end" type="hidden" value="<?php echo $departure; ?>" />
  
	<label style="margin-left: 119px;">Number of rooms: </label><INPUT id="txtChar" onkeypress="return isNumberKey(event)" type="text" name="no_rooms" class="ed">
	<span id="errmsg"></span></INPUT><br/>
	<br />
	<?php
	require_once './include/init.php';
	if (!$con)
	{
		die('Could not connect: ' . mysqli_error());
	}

	mysqli_select_db($con, "ocean");

	$result = mysqli_query($con, "SELECT * FROM room");

	while($row = mysqli_fetch_array($result))
	{
		$a=$row['room_id'];
		$query = mysqli_query($con,"SELECT sum(qty_reserve) FROM room_inventory where arrival <= '$arival' and departure >= '$departure' and room_id='$a'");
		while($rows = mysqli_fetch_array($query))
		{
			$inogbuwin=$rows['sum(qty_reserve)'];
		}
		$angavil = $row['qty'] - $inogbuwin;
		echo '<div style="height: 117px;">';
		echo '<div style="float: left; width: 100px; margin-left: 19px;">';
		echo "<img width=150 height=110 alt='Unable to View' src='" . $row["image"] . "'>";
		echo '</br></div>';
		echo '<div style="float: right; width: 575px; margin-top: -10px;">';
		echo '<span class="style5">'.'Avalable Rooms: '.$angavil.'</span>';
		if ($angavil > 0)
		{
			echo '<input name="roomid" type="checkbox" value="' .$row["room_id"]. '" />';	
			echo '<input type="submit" name="Submit" value="reserve" onclick="setDifference(this.form);"/>';
					
		}
		if ($angavil <= 0)
		{
			echo '<span class="style5">'.'This room type is not available'.'</span>';
		}	
		echo '<br>';		
		echo '<span class="style5">'.'Room Type: '.$row['room_type'].'</span><br>';
		echo '<span class="style5">'.'Room Price: '.$row['price'].'</span><br>';
		echo '<input name="avail" type="hidden" value="' .$angavil. '" />';
		echo '<span class="style5">'.'Room Description: '.$row['description'].'</span><br><br/>';
		echo '</div>';
		echo '</div></br></br>';
	}

	mysqli_close($con);
	?>
	
		<input type="hidden" name="result" id="result" />
	</form>
						
					</div>
				</div>
			</div>
		</div>
		
			
		</div>
	</div>
</body>
</html>