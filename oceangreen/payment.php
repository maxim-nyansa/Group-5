<!doctype html>
<html lang="en">
<head>
	<meta charset = "utf-8"/>
	<title>room reservation</title>
	
	<?php
		$errmsg_arr = array();
	
		$errflag = false;
		if($errflag) {
			$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		
			session_write_close();
			header("location: guestinfo.php");
			exit();
		}
	?>

	<?php
		if (!isset($_POST['submit'])) {

		$errmsg_arr = array();
	
		$errflag = false;
	
		require_once './include/init.php';
		if (!$con)
		{
			die('Could not connect: ' . mysqli_error());
		}

		mysqli_select_db($con,"ocean");

		function createRandomPassword() {
			$chars = "abcdefghijkmnopqrstuvwxyz023456789";
			srand((double)microtime()*1000000);

			$i = 0;
			$pass = '' ;
			
			while ($i <= 7) {

				$num = rand() % 33;
				$tmp = substr($chars, $num, 1);
				$pass = $pass . $tmp;
				$i++;
			}
			return $pass;
		}
		
		$confirmation = createRandomPassword();
		$arival = $_POST['start'];
		$departure = $_POST['end'];	
		$nroom = $_POST['n_room'];
		$roomid = $_POST['rm_id'];
		$room_type = $_POST['room_type'];
		$result = $_POST['result'];
		$name = $_POST['name'];
		$last = $_POST['last'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$cnumber = $_POST['cnumber'];
		$stat= 'Active';
		
		$result1 = mysqli_query($con,"SELECT * FROM room where room_id='$roomid'");
		
		while($row = mysqli_fetch_array($result1))
		{
			$rate=$row['price'];
			$type=$row['room_type'];
		}
		$payable= $rate*$result*$nroom;
	
		//send the email
		$to = $email;
		$subject="Reservation notification From Tamera Plaza Inn";
		$from = 'info@oceangreen.com';
		$body = "First Name: $name\n".
		"Last Name: $last\n".
		"Email: $email \n".
		"Contact Number: $cnumber \n".
		"Password: $password \n".
		"Check In: $arival\n ".
		"Check Out: $departure\n ".
		"Total nights of stay: $result\n ".
		"Room Type: $type\n ".
		"Number of rooms: $nroom\n ".
		"Payable amount: $payable\n ".
		"Confirmation Number: $confirmation\n ";	
		
		$headers = "From: $from \r\n";
		$headers .= "Reply-To: $$from \r\n";
		
		mail($to, $subject, $body,$headers);
	
		mysqli_query($con, "INSERT INTO guests (firstname, lastname, email, contact, password) VALUES ('$name','$last','$email','$cnumber','$password')");
		
		$resu = mysqli_query($con,"SELECT guest_id FROM guests where email='$email'");
		
		while($row = mysqli_fetch_array($resu))
		{
			$user_id=$row['guest_id'];
		}
	
		$sql="INSERT INTO reservation (user_id, room_no, arival, departure, nroom)
		VALUES
		('$user_id','$roomid','$arival','$departure','$nroom')";
		
		mysqli_query($con, "INSERT INTO room_inventory (arrival, departure, qty_reserve, room_id) VALUES ('$arival','$departure','$nroom','$roomid')");
		
		if (!mysqli_query($con, $sql))
		{
			die('Error: ' . mysqli_error());
		}
	}
	mysqli_close($con)
	?>
</head>


<body>

	<form action="https://www.sandbox.paypal.com/cgi-bin/webscr"  method="post">
    <!-- the cmd parameter is set to _xclick for a Buy Now button -->
	
		<div class="reservation" style="margin-left: 176px; width: 400px;">
			<div align="center" style="padding-top: 7px; font-size:24px;"><strong>RESERVATION  DETAILS</strong></div>
			
			<div style="margin-top: 14px;">
				<label style="margin-left: 73px;">Check In Date : <?php echo $arival; ?></label><br />
				<label style="margin-left: 58px;">Check Out Date : <?php echo $departure; ?></label><br />
				<label style="margin-left: 42px;">Number of Rooms : <?php echo $nroom; ?></label><br />
				<label style="margin-left: 110px;">Room TYPE : <?php echo $room_type; ?></label><br />
				<label style="margin-left: 52px;">Number of nights : <?php echo $result; ?></label><br />
				<label style="margin-left: 101px;">Firstname : <?php echo $name; ?></label><br />
				<label style="margin-left: 102px;">Lastname : <?php echo $last; ?></label><br />
				<label style="margin-left: 133px;">Email : <?php echo $email; ?></label><br />
				<label style="margin-left: 56px;">Contact Number : <?php echo $cnumber; ?></label><br />  <br/>
			</div>
	
			<input type="hidden" name="cmd" value="_xclick" />
			<input type="hidden" name="business" value="jpabs78@gmail.com" />
			<input type="hidden" name="item_name" value="<?php echo $type; ?>" />
			<input type="hidden" name="item_number" value="<?php echo $nroom; ?>" />
			<input type="hidden" name="amount" value="<?php echo $payable; ?>" />
			<input type="hidden" name="no_shipping" value="1" />
			<input type="hidden" name="no_note" value="1" />
			<input type="hidden" name="currency_code" value="PHP" />
			<input type="hidden" name="lc" value="GB" />
			<input type="hidden" name="bn" value="PP-BuyNowBF" />
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but23.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!" style="margin-left: 157px;" />
			<img alt="fdff" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
			<!-- Payment confirmed -->
			<input type="hidden" name="return" value="https://www.chmscians.com/paypal/showconfirm.php" />
			<!-- Payment cancelled -->
			<input type="hidden" name="cancel_return" value="http://www.chmscians.com/paypal/cancel.php" />
			<input type="hidden" name="rm" value="2" />
			<input type="hidden" name="notify_url" value="http://www.chmscians.com/paypal/ipn.php" />
			<input type="hidden" name="custom" value="any other custom field you want to pass" />
		</div>
    </form>
</body>
</html>