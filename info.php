<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>You already signed!</title>
<link rel="stylesheet"
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
	integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
	crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="alert alert-warning" role="alert">
					<?php 
					if(isset($_GET['info'])){
						echo $_GET['info'];
					}else{
						echo "Error: Empty message!";
					}
					?>
				</div>
				<a class="btn btn-secondary" href="index.php">Back</a>
			</div>
		</div>
	</div>
</body>
</html>