<?php
	require('/MySqlDBHelper.class.php');
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$accountid = $_POST['accountid'];
		$amount = $_POST['amount'];
		if(is_numeric($accountid) && is_numeric($amount) && intval($amount) > 0)
		{
			$accountid = intval($accountid);
			$amount = intval($amount);
			$db = MySqlDBHelper::getInstance();
			// check account exists or not
			$result = $db->checkAccountExists($accountid);
			if(!$result)
			{
				echo '<script>alert("Account does not exist.");window.location.href="/bank.html";</script>';
			}
			else
			{
				if($result['balance'] < $amount)
				{
					echo '<script>alert("Insufficient funds in account.");window.location.href="/bank.html";</script>';
				}
				else
				{
					$data = array(
						'balance' => $result['balance'] - $amount
					);
					$where = 'accountid = ' . $accountid;
					$result = $db->update('yangch_BANKACCOUNTS', $data, $where);
					if($result)
					{
						echo '<script>alert("Withdrawl successful.");window.location.href="/bank.html";</script>';
					}
				}
			}
		}
		else
		{
			echo '<script>alert("Bank_Server: Problem with W(ithdraw.");window.location.href="/bank.html";</script>';
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