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
		echo $startDate;
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
	
	
	
