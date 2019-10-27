<?php
	session_start();
	
	if(!isset($_POST['registerTabLogin']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		$_SESSION['e_name'] = "";
		$_SESSION['e_email'] = "";
		$_SESSION['e_login'] = "";
		$_SESSION['e_pass'] = "";
		
		$all_ok=true;
		
		$password = $_POST['registerTabPassword'];
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		
		$login = $_POST['registerTabLogin'];
		$name =$_POST['registerTabName'];
		$email = $_POST['registerTabEmail'];
		
		//Name length validation
		if((strlen($name)<2) || (strlen($name)>20))
		{
			$all_ok=false;
			$_SESSION['e_name'] = "Imię musi posiadać od 2 do 20 znaków";
		}
		
		//Login content validation
		if(ctype_alnum($login)==false)
		{
			$all_ok=false;
			$_SESSION['e_login'] = "Login może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		
		//Login length validation
		if((strlen($login)<3) || (strlen($login)>20))
		{
			$all_ok=false;
			$_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków";
		}
		
		//Email validation
		$emailS = filter_var($email, FILTER_SANITIZE_EMAIL);
		if((filter_var($emailS, FILTER_VALIDATE_EMAIL)==false) || ($emailS != $email))
		{
			$all_ok = false;
			$_SESSION['e_email'] = "Podany adres email jest niepoprawny";
		}
		
		//Password validation
		if((strlen($password)<6) || (strlen($password)>20))
		{
			$all_ok=false;
			$_SESSION['e_pass'] = "Hasło musi posiadać od 6 do 20 znaków";
		}

		//Nick/Email taken validation
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
				$result= $connection->query("SELECT id FROM users WHERE email='$email'");
				if(!$result) throw new Exception($connection->error);
				$howManyEmails = $result->num_rows;
				if($howManyEmails>0)
				{
					$all_ok=false;
					$email_ok = false;
					$_SESSION['e_email'] = "Istnieje już konto przypisane do tego emaila";
				}
				
				$result= $connection->query("SELECT id FROM users WHERE login='$login'");
				if(!$result) throw new Exception($connection->error);
				$howManyUsers = $result->num_rows;
				if($howManyUsers>0)
				{
					$all_ok=false;
					$_SESSION['e_login'] = "Istnieje już konto o takim loginie";
				}

				//Register new user
				if($all_ok)
				{
					if($connection->query("INSERT INTO users VALUES (NULL, '$login', '$hashedPassword', '$email', '$name')"))
					{
						echo "Konto zostało utworzone, przejdź do zakładki logowania aby się zalogować";
						$result = $connection->query("SELECT id FROM users WHERE login='$login'");
						$row = $result->fetch_assoc();
						$userID = $row['id'];
						$connection->query("INSERT INTO expenses_category (user_id, category) SELECT '$userID'as user_id, category FROM expenses_category_default ");
						$connection->query("INSERT INTO incomes_category (user_id, category) SELECT '$userID'as user_id, category FROM incomes_category_default ");
						$connection->query("INSERT INTO payments_method (user_id, method) SELECT '$userID'as user_id, method FROM payments_method_default ");
					}
					else
					{
						$all_ok = false;
						throw new Exception($connection->error);
					}
				}
				
				$connection->close();
			}
		}
		catch(Exception $e)
		{
			echo "Błąd serwera! Przepraszamy. Spróbuj ponownie później";
		}
		
	}
	
?>

<script>
	var all_ok = "<?php echo $all_ok; ?>";
	
	if (all_ok)
	{
		$("#registerFormMessage").addClass("text-success");
		$("#registerFormMessage").removeClass("text-danger");
	}
	else
	{
		$("#registerFormMessage").addClass("text-danger");
		$("#registerFormMessage").removeClass("text-success");
	}
		$("#registerTabNameFeedback").html("<?php echo $_SESSION['e_name']; ?>");
		$("#registerTabEmailFeedback").html("<?php echo $_SESSION['e_email']; ?>");
		$("#registerTabLoginFeedback").html("<?php echo $_SESSION['e_login']; ?>");
		$("#registerTabPasswordFeedback").html("<?php echo $_SESSION['e_pass']; ?>");
</script>