<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Petition</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<div class = "row">
			<div class = "col-12">				
				
					<?php 
					$id = $_GET['id'];				
				$db = new PDO ('mysql:host=localhost;dbname=petitions_db', 'root', '', array (PDO::ATTR_PERSISTENT => true));
				$sql = "select p.*, u.email, count(s.id) as qty
					from petitions as p
					left join signatures as s on p.id = s.id_petition
					left join users as u on p.id_autor = u.id
					where p.id = :id
					group by p.id";
				$query = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$query->execute(array(':id'=>$id));				
				while($petition = $query->fetch()){					
					$subject = $petition['subject'];
					$body = $petition['body'];
					$count = $petition['qty'];
					$email = $petition['email'];
					echo "<div class='card'>
					<div class='card-header'>
						<div class='row'>
						  <div class='col-auto mr-auto'>$subject</div>
							<div class='col-auto'>[$email]</div>
						  <div class='col-auto'>Count: $count</div>
						</div>
					</div>
						<div class='card-body'>
							<p class='card-text'>$body</p>							
						</div>
					</div>";					
				}
				?>
				<form action="getup.php" method="post">
					<div class="form-group">
   						<label for="email">Email address</label>
    				 	<input type="email" class="form-control" placeholder="name@example.com"  name="email" id="email" required>    				 	
    				 </div>
    				  <a class="btn btn-secondary" href="index.php">Back</a>
    				 <input class="btn btn-primary" type="submit" value="GetUp">
    				 <input type="hidden" value="<?php echo $id?>" name="id_petition" />
				</form>
			</div>
		</div>
	</div>
</body>
</html>