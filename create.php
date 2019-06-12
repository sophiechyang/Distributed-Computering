<?php
	require('./MySqlDBHelper.class.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$accountid = $_POST['accountid'];
		if(is_numeric($accountid))
		{
			$accountid = intval($accountid);
			$db = MySqlDBHelper::getInstance();
			// check account exists or not 
			if($db->checkAccountExists($accountid))
			{
				echo '<script>alert("Account already exists.");window.location.href="./bank.html";</script>';
			}
			else
			{
				$data = array(
					'accountid' => $accountid,
					'balance' => 0
				);
				$result = $db->insert('yangch_BANKACCOUNTS', $data);
				if ($result)
				{
					echo '<script>alert("Success account created.");window.location.href="./bank.html";</script>';
				}
			}
		}
		else
		{
			echo '<script>alert("Bank_Server: Problem with C(reate.");window.location.href="./bank.html";</script>';
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
					<legend>Create (account) command</legend>
					<label for="account">Account</label>
					<input type="Number" name="accountid" />
					<br><br>
					<input type="submit" value="Create">
				</fieldset>			
			</form>
		</div>
	</body>
</html>
