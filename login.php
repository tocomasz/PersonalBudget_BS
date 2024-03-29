<?php
	session_start();
	
	if(!isset($_POST['loginTabLogin']))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	try
	{
		$connection = new mysqli($host, $db_user, $db_password, $db_name);
		if($connection->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			$login = $_POST['loginTabLogin'];
			$password = $_POST['loginTabPassword'];
			
			if(ctype_alnum($login))
			{
				if(!mysqli_set_charset($connection, "utf8"))
				{
					throw new Exception($connection->error);
				}
				$result = $connection->query("SELECT * FROM users WHERE login = '$login'");
				if(!$result) throw new Exception($connection->error);
				$howManyUsers = $result->num_rows;
				if($howManyUsers>0)
				{
					$row = $result->fetch_assoc();
					if(password_verify($password, $row['password']))
					{
						$_SESSION['loggedUserId'] = $row['id'];
						$_SESSION['loggedUserName'] = $row['name'];
						$_SESSION['loggedUserLogin'] = $row['login'];
						$result->close();
						
						$loggedUserId = $_SESSION['loggedUserId'] ;
						$result = $connection->query("SELECT category, id FROM incomes_category WHERE user_id = '$loggedUserId' ");
						$_SESSION['loggedUserIncomeCategories'] = [];
						while($row = $result->fetch_assoc())
						{
							array_push($_SESSION['loggedUserIncomeCategories'],$row);
						}
						$result->close();
						
						$result = $connection->query("SELECT category, id FROM expenses_category WHERE user_id = '$loggedUserId' ");
						$_SESSION['loggedUserExpenseCategories'] = [];
						while($row = $result->fetch_assoc())
						{
							array_push($_SESSION['loggedUserExpenseCategories'],$row);
						}
						$result->close();
						
						$result = $connection->query("SELECT method, id FROM payments_method WHERE user_id = '$loggedUserId' ");
						$_SESSION['loggedUserPaymentMethods'] = [];
						while($row = $result->fetch_assoc())
						{
							array_push($_SESSION['loggedUserPaymentMethods'],$row);
						}
						$result->close();
						
						echo "Zalogowano poprawnie";
					}
					else
					{
						echo "Niepoprawny login lub hasło";
					}
				}
				else
				{
					echo "Niepoprawny login lub hasło";
				}
			}
			else
			{
				echo "Niepoprawny login lub hasło";
			}
			$connection->close();
		}
	}
	catch(Exception $e)
	{
		echo "Błąd serwera! Przepraszamy. Spróbuj ponownie później";
	}
 ?>
 
 <script>
	var loggedUserId = "<?php echo $loggedUserId; ?>";
	if(loggedUserId)
	{
		$("#loginFormMessage").addClass("text-success");
		$("#loginFormMessage").removeClass("text-danger");
		
		$("#addIncomePill").removeClass("disabled");
		$("#addExpensePill").removeClass("disabled");
		$("#balancePill").removeClass("disabled");
		$("#settingsPill").removeClass("disabled");
		
		
		setTimeout(function(){ window.location = "index.php"; },1000);
	}

 </script>