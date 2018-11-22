<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Petition</title>
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
				<h1>Petition</h1>
				<form action="add.php">
					<input class="btn btn-primary" type="submit" value="Add petition">
				</form>
				<?php
				if (! isset ( $_GET ['page'] )) {
					$page = 1;
				}
				else {
					$page = $_GET ['page'];
				}
				$db = new PDO ( 'mysql:host=localhost;dbname=petitions_db', 'root', '', array (
						PDO::ATTR_PERSISTENT => true
				) );

				$sql = "select p.*, u.email, count(s.id) as qty
					from petitions as p
					left join signatures as s on p.id = s.id_petition
					left join users as u on p.id_autor = u.id
					where p.active = 1
					group by p.id";
				$query = $db->prepare ( $sql, array (
						PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
				) );
				$query->execute ();
				$allPetitions = $query->fetchAll ();
				$countPetition = count ( $allPetitions );

				$countOnPage = 3;
				$pages = ceil ( $countPetition / $countOnPage );

				$start = $countOnPage * ($page - 1);				
				for($i = 0; $i < 3; $i ++) {
					$petition = $allPetitions [$i + $start];
					$subject = $petition ['subject'];
					$body = $petition ['body'];
					$count = $petition ['qty'];
					$email = $petition['email'];
					if ($i + $start < $countPetition) {
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
									<a href='petition.php?id=" . $petition ['id'] . "' class='btn btn-info'>Get Up</a>
								</div>
							</div>";
					}
				}

				$prevPage = $page > 1 ? $page - 1 : 1;
				$nextPage = $page < pages ? $page + 1 : $pages;
				$prevDisabled = $page > 1 ? '' : 'disabled';
				$nextDisabled = $page < $pages ? '' : 'disabled';
				echo "<nav aria-label='Page navigation'>
					<ul class='pagination justify-content-center'>
						<li class='page-item $prevDisabled'>
						<a class='page-link' href='index.php?page=$prevPage'>Previous</a></li>";

				for($i = 1; $i <= $pages; $i ++) {
					$current =$i == $page?"current":"";					
					echo "<li class='page-item'>
					<a class='page-link $current' href='index.php?page=$i'>$i</a></li>";
				}
				echo "<li class='page-item $nextDisabled'>
						<a class='page-link' href='index.php?page=$nextPage'>Next</a></li>
					</ul>
				</nav>";
				?>	

			</div>
		</div>
	</div>
</body>
</html>