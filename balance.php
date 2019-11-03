<?php
	session_start();

	if(!isset($_POST['datePeriod']) && !isset($_POST[('startDate')]))
	{
		header('Location: index.php');
		exit();
	}
	
	$all_ok=true;
	$_SESSION['e_startDate'] = "";
	$_SESSION['e_endDate'] = "";
	$loggedUserId = $_SESSION['loggedUserId'];
	
	if(isset($_POST[('startDate')]))
	{
		//Start date validation
		$startDate = $_POST['startDate'];
		$testStartDate  = explode('-', $startDate); //rok0, miesiac1, dzien2
		if (!(count($testStartDate) == 3))
		{
			$all_ok=false;
			$_SESSION['e_startDate'] = "Podano nieprawidłową datę";
		}
		else
		{
			if(!(checkdate($testStartDate[1], $testStartDate[2], $testStartDate[0])))
			{
				$all_ok=false;
				$_SESSION['e_startDate'] = "Podano nieprawidłową datę";
			}
		}
		
		//End date validation
		$endDate = $_POST['endDate'];
		$testEndDate  = explode('-', $endDate); //rok0, miesiac1, dzien2
		if (!(count($testEndDate) == 3))
		{
			$all_ok=false;
			$_SESSION['e_endDate'] = "Podano nieprawidłową datę";
		}
		else
		{
			if(!(checkdate($testEndDate[1], $testEndDate[2], $testEndDate[0])))
			{
				$all_ok=false;
				$_SESSION['e_endDate'] = "Podano nieprawidłową datę";
			}
		}
	}
	
	else
	{
		switch ($_POST['datePeriod'])
		{
			case "currentMonth": 
				$startDate = date('Y-m-01');
				$endDate = date('Y-m-t');
				break;
			case "lastMonth":
				$startDate = date(('Y-m-01'), strtotime('-1 month'));
				$endDate = date(('Y-m-t'), strtotime('-1 month'));
				break;
			case "currentYear":
				$startDate = date('Y-01-01');
				$endDate = date('Y-12-31');
				break;
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
				if(!mysqli_set_charset($connection, "utf8"))
				{
					throw new Exception($connection->error);
				}
				$result = $connection->query("SELECT date, amount, category FROM incomes, incomes_category WHERE incomes.user_id = '$loggedUserId' AND incomes.category_id = incomes_category.id AND date BETWEEN '$startDate' AND '$endDate' ORDER BY date DESC");
				if(!$result) throw new Exception($connection->error);
				$_SESSION['loggedUserIncomes'] = [];
				while($row = $result->fetch_assoc())
				{
					array_push($_SESSION['loggedUserIncomes'],$row);
				}
				$result->close();
				
				$result = $connection->query("SELECT date, amount, category FROM expenses, expenses_category WHERE expenses.user_id = '$loggedUserId' AND expenses.category_id = expenses_category.id AND date BETWEEN '$startDate' AND '$endDate' ORDER BY date DESC");
				if(!$result) throw new Exception($connection->error);
				$_SESSION['loggedUserExpenses'] = [];
				while($row = $result->fetch_assoc())
				{
					array_push($_SESSION['loggedUserExpenses'],$row);
				}
				$result->close();
			}
			$connection->close();
			
			$incomesSum = 0;
			$expensesSum = 0;
			
			echo '<h4 class="display2">Przychody</h4>';
			echo '<table class = "table table-hover table-sm">';
			echo '<thead class ="thead-light"><tr><th>Data</th><th>Kategoria</th><th>Kwota</th></tr></thead>';
			echo '<tbody>';
			foreach ($_SESSION['loggedUserIncomes'] as $row)
			{
				$incomesSum+=$row['amount'];
				echo '<tr><td>'.$row['date'].'</td><td>'.$row['category'].'</td><td>'.$row['amount'].'</td></tr>';
			}
			echo '</tbody>';
			echo '<tfoot class="thead-light"><tr><th>SUMA</th><th></th><th>'.$incomesSum.' zł</th>';
			echo '</table>';
			
			echo '<h4 class="display2">Wydatki</h4>';
			echo '<table class = "table table-hover table-sm">';
			echo '<thead class="thead-light"><tr><th>Data</th><th>Kategoria</th><th>Kwota</th></tr></thead>';
			echo '<tbody>';
			foreach ($_SESSION['loggedUserExpenses'] as $row)
			{
				$expensesSum+=$row['amount'];
				echo '<tr><td>'.$row['date'].'</td><td>'.$row['category'].'</td><td>'.$row['amount'].'</td></tr>';
			}
			echo '</tbody>';
			echo '<tfoot class="thead-light"><tr><th>SUMA</th><th></th><th>'.$expensesSum.' zł</th>';
			echo '</table>';
			
			if($incomesSum>$expensesSum)
				echo "Nadwyżka za okres wyniosła: ".($incomesSum-$expensesSum)." zł";
			else if($incomesSum<$expensesSum)
				echo "Deficyt wyniósł: ".($expensesSum-$incomesSum)." zł";
			else
				echo "W wybranym okresie przychody wyniosły tyle samo co wydatki.";
			
			echo "<hr>";
			
		}
		catch(Exception $e)
		{
			echo "Błąd serwera! Przepraszamy. Spróbuj ponownie później";
			echo $e;
		}
	}
	
?>

<script>
	var all_ok = "<?php echo $all_ok; ?>";
	if(all_ok)
	{
		$("#customPeriod").modal('hide');
	}
	
		$("#startDateFeedback").html("<?php echo $_SESSION['e_startDate']; ?>");
		$("#endDateFeedback").html("<?php echo $_SESSION['e_endDate']; ?>");
		
</script>
	
	
	
