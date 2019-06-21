<?php
	require('/MySqlDBHelper.class.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$accountid = $_POST['accountid'];
		if(is_numeric($accountid))
		{
			$accountid = intval($accountid);
			$db = MySqlDBHelper::getInstance();
			$field = array('accountid', 'balance');
			$where = 'accountid = ' . $accountid;
			$result = $db->select('yangch_BANKACCOUNTS', $field, $where);

			if($result)
			{
				echo '<script>alert("Balance is: $' . $result['balance'] . '");window.location.href="/bank.html";</script>';
			}
			else
			{
				echo '<script>alert("Account does not exist.");window.location.href="/bank.html";</script>';
			}
		}
		else
		{
			echo '<script>alert("Bank_Server: Problem with R(etrieve.");window.location.href="/bank.html";</script>';
		}
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
					<legend>Retrieve (balance) command</legend>
					<label for="account">Account</label>
					<input type="Number" name="accountid" />
					<br><br>
					<input type="submit" value="Retrieve">
				</fieldset>			
			</form>
		</div>
	</body>
</html>