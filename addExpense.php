<?php
	session_start();
	if(!isset($_POST['expenseAmount']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		$_SESSION['e_expenseAmount'] = "";
		$_SESSION['e_expenseDate'] = "";
		$_SESSION['e_expenseComment'] = "";
		$loggedUserId = $_SESSION['loggedUserId'];
		$all_ok = true;
		
		$categoryId = $_POST['expenseCategoryId'];
		$paymentMethodId = $_POST['expensePaymentMethodId'];
		
		$amount = $_POST['expenseAmount'];
		str_replace(',', '.', $amount);

		
		//Amount validation
		if($amount<0 || $amount >999999.99)
		{
			$all_ok=false;
			$_SESSION['e_expenseAmount'] = "Kwota musi znajdować się w przedziale od 0.00 do 999 999.99";
		}

		//Amount decimal point validation
		$position = strpos($amount, '.');
		if($position)
		{
			$fractionalNumbersCount = strlen($amount)-($position+1);
			if( $fractionalNumbersCount >2 )
			{
				$all_ok=false;
				$_SESSION['e_expenseAmount'] = "Kwota musi posiadać maksymalnie dwie cyfry po przecinku";
			}

		}
		else if (strlen($amount) == 0)
		{
			$all_ok=false;
			$_SESSION['e_expenseAmount'] = "Nieprawidłowy format kwoty";
		}


		//Date validation
		$date = $_POST['expenseDate'];
		$testDate  = explode('-', $date); //rok0, miesiac1, dzien2
		if (!(count($testDate) == 3))
		{
			$all_ok=false;
			$_SESSION['e_expenseDate'] = "Podano nieprawidłową datę";
		}
		else
		{
			if(!(checkdate($testDate[1], $testDate[2], $testDate[0])))
			{
				$all_ok=false;
				$_SESSION['e_expenseDate'] = "Podano nieprawidłową datę";
			}
		}
		
		
		//Comment validation
		$comment = $_POST['expenseComment'];
		$commentClean = strip_tags($_POST['expenseComment']);
		
		if(strlen($comment)>100)
		{
			$all_ok=false;
			$_SESSION['e_expenseComment'] = "Komentarz może posiadać maksymalnie 100 znaków";
		}
		if($commentClean != $comment)
		{
			$all_ok=false;
			$_SESSION['e_expenseComment'] = "Komentarz musi składać się tylko z liter i cyfr";
		}

		
		if($all_ok)
		{
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
					if($connection->query("INSERT INTO expenses VALUES (NULL, '$loggedUserId', '$date', '$categoryId', '$amount', '$paymentMethodId', '$comment')"))
					{
						echo "Dodano wydatek";
					}
					else
					{
						$all_ok = false;
						throw new Exception($connection->error);
					}
				}
				
				$connection->close();
			}
			catch(Exception $e)
			{
				echo "Błąd serwera! Przepraszamy. Spróbuj ponownie później";
			}
		}

	}
	
?>

<script>
	var all_ok = "<?php echo $all_ok; ?>";
	
	if (all_ok)
	{
		$("#addExpenseFormMessage").addClass("text-success");
		$("#addExpenseFormMessage").removeClass("text-danger");
	}
	else
	{
		$("#addExpenseFormMessage").addClass("text-danger");
		$("#addExpenseFormMessage").removeClass("text-success");
	}
		$("#addExpenseAmountFeedback").html("<?php echo $_SESSION['e_expenseAmount']; ?>");
		$("#addExpenseDateFeedback").html("<?php echo $_SESSION['e_expenseDate']; ?>");
		$("#addExpenseCommentFeedback").html("<?php echo $_SESSION['e_expenseComment']; ?>");

</script>