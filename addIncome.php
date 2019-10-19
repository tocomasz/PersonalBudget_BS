<?php
	session_start();
	if(!isset($_POST['incomeAmount']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		$_SESSION['e_incomeAmount'] = "";
		$_SESSION['e_incomeDate'] = "";
		$loggedUserId = $_SESSION['loggedUserId'];
		$all_ok = true;
		
		$amount = $_POST['incomeAmount'];
		str_replace(',', '.', $amount);
		$date = $_POST['incomeDate'];
		$categoryId = $_POST['incomeCategoryId'];
		
		//Amount validation
		if($amount<0 || $amount >999999.99)
		{
			$all_ok=false;
			$_SESSION['e_incomeAmount'] = "Kwota musi znajdować się w przedziale od 0.00 do 999 999.99";
		}

		//Amount decimal point validation
		$position = strpos($amount, '.');
		if($position)
		{
			$fractionalNumbersCount = strlen($amount)-($position+1);
			if( $fractionalNumbersCount >2 )
			{
				$all_ok=false;
				$_SESSION['e_incomeAmount'] = "Kwota musi posiadać maksymalnie dwie cyfry po przecinku";
			}

		}
		else if (strlen($amount) == 0)
		{
			$all_ok=false;
			$_SESSION['e_incomeAmount'] = "Nieprawidłowy format kwoty";
		}
		
		
		
		//Date validation
		$testDate  = explode('-', $date); //rok0, miesiac1, dzien2
		if (!(count($testDate) == 3))
		{
			$all_ok=false;
			$_SESSION['e_incomeDate'] = "Podano nieprawidłową datę";
		}
		else
		{
			if(!(checkdate($testDate[1], $testDate[2], $testDate[0])))
			{
				$all_ok=false;
				$_SESSION['e_incomeDate'] = "Podano nieprawidłową datę";
			}
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
					if($connection->query("INSERT INTO incomes VALUES (NULL, '$loggedUserId', '$date', '$categoryId', '$amount', ' ')"))
					{
						echo "Dodano przychód";
					}
					else
					{
						$all_ok = false;
						throw new Exception($connection->error);
					}
				}
				
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
		$("#addIncomeFormMessage").addClass("text-success");
		$("#addIncomeFormMessage").removeClass("text-danger");
	}
	else
	{
		$("#addIncomeFormMessage").addClass("text-danger");
		$("#addIncomeFormMessage").removeClass("text-success");
	}
		$("#addIncomeAmountFeedback").html("<?php echo $_SESSION['e_incomeAmount']; ?>");
		$("#addIncomeDateFeedback").html("<?php echo $_SESSION['e_incomeDate']; ?>");

</script>