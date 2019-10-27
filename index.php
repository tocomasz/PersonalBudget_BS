<?php
	session_start();
 ?>
<!DOCTYPE HTML>
<html lang="pl">
	<head>
	  
		<meta charset="utf-8"/> 
		<title>Budżet personalny</title>
		<meta name="description"  content= "Prowadź swój budżet personalny, dodając wydatki i przychody." />
		<meta name="keywords" content="budżet personalny, budżet osobisty, budżet" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		
		<link rel="stylesheet" href ="css/style.css" type="text/css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		
		<script>

		var loggedUserId = "<?php if(isset($_SESSION['loggedUserId'])) echo $_SESSION['loggedUserId']; ?>";
		var loggedUserName = "<?php if(isset($_SESSION['loggedUserName'])) echo $_SESSION['loggedUserName']; ?>";
		

		$(document).ready(function(){
			if (loggedUserId)
			{
				$("#welcomeMessage").html("Witaj " + loggedUserName + "!");
				$("#instructionMessage").html("Wybierz opcję z menu po lewej aby dodawać/usuwać przychody i wydatki, a także żeby przeglądać bilans");
				$("#mainNavbar").html("<li><a href='logout.php' class='btn btn-secondary ml-auto mr-5' role = 'button' id='logout'> Wyloguj się</a></li>"); 

			}
			else
			{
				$("#welcomeMessage").html("Witaj!");
				$("#instructionMessage").html("Zaloguj się do swojego konta lub załóż nowe aby móc korzystać z opcji dodawania/usuwania przychodów oraz przeglądania bilansu");
				$("#mainNavbar").html("<li><button class='btn btn-secondary ml-auto mr-2' id='changeTabToLogin'> Zaloguj się</button></li><li><button class='btn btn-secondary ml-auto mr-5' id='changeTabToRegister'> Zarejestruj się</button></li>");
				$("#addIncomePill").addClass("disabled");
				$("#addExpensePill").addClass("disabled");
				$("#balancePill").addClass("disabled");
				$("#settingsPill").addClass("disabled");
			}
		});

		$(document).ready(function(){
			$("#datePeriod").change(function(){
				if(this.value=="niestandardowy"){
					$("#customPeriod").modal({backdrop: "static"});
				};
			});
		});
		
		$(document).ready(function(){
			$("#customPeriodModalClose").click(function(){
				$("#datePeriod").val("currentMonth");
			});
		});
		
		$(document).ready(function(){
			$("#changeTabToLogin").click(function(){
				$("#loginModal").modal({backdrop: "static"});
				$('.nav-pills a[href="#loginTab"]').tab('show')
			});
		});
		
		$(document).ready(function(){
			$("#changeTabToRegister").click(function(){
				$("#loginModal").modal({backdrop: "static"});
				$('.nav-pills a[href="#registerTab"]').tab('show')
			});
		});
		
		$(document).ready(function(){
			$("#registerForm").submit(function(event){
				event.preventDefault();
				var registerTabName = $("#registerTabName").val();
				var registerTabEmail = $("#registerTabEmail").val();
				var registerTabLogin = $("#registerTabLogin").val();
				var registerTabPassword = $("#registerTabPassword").val();
				$("#registerFormMessage").load("register.php", {
					registerTabName: registerTabName,
					registerTabEmail: registerTabEmail,
					registerTabLogin: registerTabLogin,
					registerTabPassword: registerTabPassword
				});
			});
		});
		
		$(document).ready(function(){
			$("#loginForm").submit(function(event){
				event.preventDefault();
				var loginTabLogin = $("#loginTabLogin").val();
				var loginTabPassword = $("#loginTabPassword").val();
				$("#loginFormMessage").load("login.php", {
					loginTabLogin: loginTabLogin,
					loginTabPassword: loginTabPassword
				});
			});
		});
		
		$(document).ready(function(){
			$("#addIncomeForm").submit(function(event){
				event.preventDefault();
				var incomeAmount = $("#incomeAmount").val();
				var incomeDate = $("#incomeDate").val();
				var incomeCategoryId = $("#incomeCategoryId").val();
				var incomeComment = $("#incomeComment").val();
				$("#addIncomeFormMessage").load("addIncome.php", {
					incomeAmount: incomeAmount,
					incomeDate: incomeDate,
					incomeCategoryId: incomeCategoryId,
					incomeComment: incomeComment
				});
				
				setTimeout(function(){
					$("#addIncomeFormMessage").empty();
					$("#incomeAmount").val('');
					$("#incomeDate").val('');
					$("#incomeComment").val('');
				}, 2000);
			});
		});
		
		$(document).ready(function(){
			$("#addExpenseForm").submit(function(event){
				event.preventDefault();
				var expenseAmount = $("#expenseAmount").val();
				var expenseDate = $("#expenseDate").val();
				var expenseCategoryId = $("#expenseCategoryId").val();
				var expensePaymentMethodId= $("#expensePaymentMethodId").val();
				var expenseComment = $("#expenseComment").val();
				$("#addExpenseFormMessage").load("addExpense.php", {
					expenseAmount: expenseAmount,
					expenseDate: expenseDate,
					expenseCategoryId: expenseCategoryId,
					expensePaymentMethodId: expensePaymentMethodId,
					expenseComment: expenseComment
				});
				
				setTimeout(function(){
					$("#addExpenseFormMessage").empty();
					$("#expenseAmount").val('');
					$("#expenseDate").val('');
					$("#expenseComment").val('');
				}, 2000);
			});
		});
		
		$(document).ready(function(){
			$("#customPeriodBalance").submit(function(event){
				event.preventDefault();
				var startDate = $("#startDate").val();
				var endDate = $("#endDate").val();
				$("#balanceResults").load("balance.php", {
					startDate: startDate,
					endDate: endDate
				});
			});
		});
		
		$(document).ready(function(){
			$("#defaultPeriodBalance").submit(function(event){
				event.preventDefault();
				var datePeriod = $("#datePeriod").val();
				$("#balanceResults").load("balance.php", {
					datePeriod: datePeriod
				});
			});
		});
		
				
		$(document).ready(function(){
			$("#cancelBalanceResults").click(function(){
				$("#balanceResults").html('');
			});
		});

		</script>
	 </head>
	  
	 <body>
		<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
			<a class="navbar-brand mr-auto ml-5" href="index.php"><i class="fas fa-piggy-bank"></i></a>
			
			<ul class="navbar-nav ml-auto" id="mainNavbar">
			</ul>
			
		</nav>
		
		 <!-- Modal for login/register -->
		<div class="modal fade" id="loginModal">
			<div class="modal-dialog">
				<div class="modal-content">

					<div class="modal-header">
					
						<ul class="nav nav-pills">
						
							<li class="nav-item">
								<a class="nav-link active" data-toggle="pill" href="#loginTab">
								<i class="fas fa-user mr-1"></i>Logowanie
								</a>
							</li>
							
							<li class="nav-item">
								<a class="nav-link" data-toggle="pill" href="#registerTab">
								<i class="fas fa-user-plus mr-1"></i>Rejestracja
								</a>
							</li>
							
						</ul>
						
						<button type="button" class="close" data-dismiss="modal" id="modalClose">&times;</button>
					</div>

					<div class="modal-body mx-3">
						<div class="tab-content">
						
							<div class="tab-pane container active" id="loginTab">
								<form id="loginForm" action="login.php" method="post" novalidate>
									<div class="form-group row">
										<label for="loginTabLogin" class="col-sm-5 col-form-label">Login</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="loginTabLogin" name="loginTabLogin">
										</div>
									</div>
									
									<div class="form-group row">
										<label for="loginTabPassword" class="col-sm-5 col-form-label">Hasło</label>
										<div class="col-sm-7">
											<input type="password" class="form-control" id="loginTabPassword"  name="loginTabPassword">
										</div>
									</div>
									<div id="loginFormMessage" class="text-danger"></div>
									<button type="submit" class="btn btn-outline-secondary float-right">Zaloguj się</button>
									
								</form>
							</div>
							
							<div class="tab-pane container fade" id="registerTab">
								<form id ="registerForm" action="register.php" method="post" novalidate>
									<div class="form-group row">
										<label for="registerTabName" class="col-sm-5 col-form-label">Imię</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="registerTabName" name="registerTabName" >
											<small class = "text-danger" id="registerTabNameFeedback"></small>
										</div>

									</div>
									
									<div class="form-group row">
										<label for="registerTabEmail" class="col-sm-5 col-form-label">Adres email</label>
										<div class="col-sm-7">
											<input type="email" class="form-control" id="registerTabEmail" name="registerTabEmail">
											<small class = "text-danger" id="registerTabEmailFeedback"></small>
										</div>
									</div>
									
									<div class="form-group row">
										<label for="registerTabLogin" class="col-sm-5 col-form-label">Login</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="registerTabLogin" name="registerTabLogin">
											<small class = "text-danger" id="registerTabLoginFeedback"></small>
										</div>
									</div>
									
									<div class="form-group row">
										<label for="registerTabPassword" class="col-sm-5 col-form-label">Hasło</label>
										<div class="col-sm-7">
											<input type="password" class="form-control" id="registerTabPassword"  name="registerTabPassword">
											<small class = "text-danger" id="registerTabPasswordFeedback"></small>
										</div>
									</div>
									<div id="registerFormMessage"></div>
									<button type="submit" class="btn btn-outline-secondary float-right">Załóż konto</button>
									
								</form>
							</div>
							
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		 <!-- Main page -->
		<div class="container mt-4">
			<div class="row">
			
				<!-- Navigation bar -->
				<nav class="col-4">
				
					<ul class="nav nav-pills flex-column">
					
						<li class="nav-item">
							<a class="nav-link" id="addIncomePill" data-toggle="pill" href="#income">
								<p><i class="fas fa-plus-square fa-3x"></i><p>
								<p>Dodaj przychód</p>
							</a>
						</li>
						
						<li class="nav-item">
							<a class="nav-link" id="addExpensePill" data-toggle="pill" href="#expense">
								<p><i class="fas fa-minus-square fa-3x"></i></p>
								<p>Dodaj wydatek</p>
							</a>
						</li>
						
						<li class="nav-item">
							<a class="nav-link"  id="balancePill" data-toggle="pill" href="#balance">
								<p><i class="fas fa-search-dollar fa-3x"></i></p>
								<p>Przeglądaj bilans</p>
							</a>
						</li>
						
						<li class="nav-item">
							<a class="nav-link" id="settingsPill" data-toggle="pill" href="#settings">
								<p><i class="fas fa-cog fa-3x"></i></p>
								<p>Ustawienia</p>
							</a>
						</li>
						
					</ul>
					
				</nav>
			
				<!--Main content -->
				<div class="col-8 bg-light rounded-lg shadow-lg py-4">
				
					<div class="tab-content">
					
						<div class="tab-pane container active" id="main" >
							<h1 class="display2 text-center">Prowadzenie budżetu osobistego</h1><hr>
							<p id = "welcomeMessage"></p>
							<p id = "instructionMessage"></p>
						</div>
					
						<div class="tab-pane container fade" id="income">
							
							<h1 class="display2 text-center">Dodawanie przychodu</h1><hr>
							
							<form id="addIncomeForm" action="addIncome.php" method="post" novalidate>
								<div class="form-group  row">
									<label for="incomeAmount" class="col-sm-4 col-form-label">Kwota przychodu</label>
									<div class="col-sm-8">
										<input type="number" class="form-control" id="incomeAmount" name = "incomeAmount" placeholder="podaj kwotę w PLN">
										<small class = "text-danger" id="addIncomeAmountFeedback"></small>
									</div>
								</div>
								
								<div class="form-group  row">
									<label for="incomeDate" class="col-sm-4 col-form-label">Data przychodu</label>
									<div class="col-sm-8">
										<input type="date" class="form-control" id="incomeDate" name="incomeDate">
										<small class = "text-danger" id="addIncomeDateFeedback"></small>
									</div>
								</div>
								
								<div class="form-group  row">
									<label for="incomeCategoryId" class="col-sm-4 col-form-label">Kategoria</label>
									<div class="col-sm-8">
										<select id="incomeCategoryId" name ="incomeCategory" class="custom-select">
										<?php
											if(isset(($_SESSION['loggedUserIncomeCategories'])))
											{
												foreach ($_SESSION['loggedUserIncomeCategories'] as $row)
												{
													echo '<option value="'.$row['id'].'">'.$row['category']."</option>";
												}
											}
										?>
										</select>
									</div>
								</div>	
								
								<div class="form-group  row">
									<label for="incomeComment" class="col-sm-4 col-form-label">Komentarz</label>
									<div class="col-sm-8">
										<textarea class="form-control" rows = "3" id="incomeComment" name="incomeComment"></textarea>
										<small class = "text-danger" id="addIncomeCommentFeedback"></small>
									</div>
								</div>								
								
								<div id="addIncomeFormMessage" class="text-danger"></div>
								<button type="submit" class="btn btn-outline-secondary float-right mr-2">Dodaj</button>
							</form>
																				
						</div>
						
						<div class="tab-pane container fade" id="expense">
							
							<h1 class="display2 text-center">Dodawanie wydatku</h1><hr>
							
							<form id="addExpenseForm" action="addExpense.php" method="post" novalidate>
								<div class="form-group  row">
									<label for="expenseAmount" class="col-sm-4 col-form-label">Kwota wydatku</label>
									<div class="col-sm-8">
										<input type="number" class="form-control" id="expenseAmount" name="expenseAmount" placeholder="podaj kwotę w PLN">
										<small class = "text-danger" id="addExpenseAmountFeedback"></small>
									</div>
								</div>
								
								<div class="form-group  row">
									<label for="expenseDate" class="col-sm-4 col-form-label">Data wydatku</label>
									<div class="col-sm-8">
										<input type="date" class="form-control" id="expenseDate" name="expenseDate">
										<small class = "text-danger" id="addExpenseDateFeedback"></small>
									</div>
								</div>
								
								<div class="form-group  row">
									<label for="expensePaymentMethodId" class="col-sm-4 col-form-label">Rodzaj płatności</label>
									<div class="col-sm-8">
										<select id="expensePaymentMethodId" name="paymentMethodId" class="custom-select">
										<?php
											if(isset(($_SESSION['loggedUserPaymentMethods'])))
											{
												foreach ($_SESSION['loggedUserPaymentMethods'] as $row)
												{
													echo '<option value="'.$row['id'].'">'.$row['method']."</option>";
												}
											}
										?>
										</select>
									</div>
								</div>		

								<div class="form-group  row">
									<label for="expenseCategoryId" class="col-sm-4 col-form-label">Kategoria</label>
									<div class="col-sm-8">
										<select id="expenseCategoryId" name="expenseCategoryId" class="custom-select">
											<?php
												if(isset(($_SESSION['loggedUserExpenseCategories'])))
												{
													foreach ($_SESSION['loggedUserExpenseCategories'] as $row)
													{
														echo '<option value="'.$row['id'].'">'.$row['category']."</option>";
													}
												}
											?>
										</select>
									</div>
								</div>		
								
								<div class="form-group  row">
									<label for="expenseComment" class="col-sm-4 col-form-label">Komentarz</label>
									<div class="col-sm-8">
										<textarea class="form-control" rows = "3" id="expenseComment" name="expenseComment"></textarea>
										<small class = "text-danger" id="addExpenseCommentFeedback"></small>
									</div>
								</div>					
								
							<div id="addExpenseFormMessage" class="text-danger"></div>
							<button type="submit" class="btn btn-outline-secondary float-right mr-2">Dodaj</button>								
							</form>
																				
						</div>
						<div class="tab-pane container fade" id="balance">
						
							<h1 class="display2 text-center">Bilans przychodów i wydatków</h1><hr>
							<h4 class="display2 d-none">Przychody</h4>
							<div id="balanceResults">
							</div>
							<form id="defaultPeriodBalance" action="balance.php" method="post" novalidate>
							
								<div class="form-group  row">
									<label for="datePeriod" class="col-sm-4 col-form-label">Przedział bilansu</label>
									<div class="col-sm-8">
										<select name="datePeriod" class="custom-select" id="datePeriod">
											<option value="currentMonth">Bieżący miesiąc</option>
											<option value="lastMonth">Poprzedni miesiąc</option>
											<option value="currentYear">Bieżący rok</option>
											<option value="niestandardowy">Niestandardowy</option>
										</select>
									</div>
								</div>
							<button type="reset" class="btn btn-outline-secondary float-right mr-2" id="cancelBalanceResults">Anuluj</button>
							<button type="submit" class="btn btn-outline-secondary float-right mr-2">Pokaż bilans</button>									
							</form>
						</div>
						
						<div class="tab-pane container fade" id="settings">
							4
						</div>
					</div>
				</div>
			</div>
			
			 <!-- Modal for custom dates -->
			<div class="modal fade" id="customPeriod">
				<div class="modal-dialog">
					<div class="modal-content">

					
						<div class="modal-header">
							<h4 class="modal-title">Wybierz zakres dat</h4>
						</div>

						<div class="modal-body">
						
							<form id="customPeriodBalance" action="balance.php" method="post" novalidate>
								<div class="form-group row">
									<label for="startDate" class="col-sm-5 col-form-label">Data początkowa</label>
									<div class="col-sm-7">
										<input type="date" class="form-control" id="startDate">
										<small class = "text-danger" id="startDateFeedback"></small>
									</div>
								</div>
								
								<div class="form-group row">
									<label for="endDate" class="col-sm-5 col-form-label">Data końcowa</label>
									<div class="col-sm-7">
										<input type="date" class="form-control" id="endDate">
										<small class = "text-danger" id="endDateFeedback"></small>
									</div>
								</div>
								<button type="submit" class="btn btn-outline-secondary float-right mr-2" data-dismiss="modal" id="customPeriodModalClose">Anuluj</button>
								<button type="submit" class="btn btn-outline-secondary float-right mr-2">Pokaż bilans</button>

							</form>
							
						</div>
					</div>
				</div>
			</div>
			
			
		</div>

	  </body>
  </html>