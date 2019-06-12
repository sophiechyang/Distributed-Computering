<?php
	require('./MySqlDBHelper.class.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$accountid = $_POST['accountid'];
		$amount = $_POST['amount'];

		$socket = Socket::singleton();
		$socket->connect();
		$socket->sendRequest('W');
		$socket->sendRequest('<'.$accountid.','.$amount.'>');
		$str = $socket->getResponse();
		if($str[0] == 'B'){
			echo '<script>alert("Account does not exist.");window.location.href="./bank.html";</script>';
		}else if($str[0] == 'I'){
			echo '<script>alert("Insufficient funds in account.");window.location.href="./bank.html";</script>';
		}else{
			echo '<script>alert("Withdrawl successful.");window.location.href="./bank.html";</script>';
		}
		$socket->disconnect();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<style type="text/css">
			body {
				text-align: center;
			}
			form {
				margin: 0 auto;
				width: 300px;
				margin-top: 100px;
			}
		</style>
	</head>
	<body>
		<div>
			<form method="post">
				<fieldset>
					<legend>Withdraw (from account) command</legend>
					<label for="account">Account</label>
					<input type="Number" name="accountid" />
					<label for="amount">Amount&nbsp;</label>
					<input type="Number" name="amount" />
					<br><br>
					<input type="submit" value="Withdraw">
				</fieldset>			
			</form>
		</div>
	</body>
</html>
