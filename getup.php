<?php
function SendMail($token, $idPetition){
	$msg = "http://localhost/confirmation.php?token=$token&id=$idPetition";
	mail("reg@petition.org","Confirmation",$msg);
	header ( "Location: http://localhost/info.php?info=We send confirmation email on your address!" );
}


$email= $_POST['email'];
$idPetition = $_POST['id_petition'];
$db = new PDO ( 'mysql:host=localhost;dbname=petitions_db', 'root', '', array (
		PDO::ATTR_PERSISTENT => true
) );
$sql = "select *
		from users		
		where email = :email";
$query = $db->prepare ( $sql, array (
		PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
) );
$query->execute ( array (
		':email' => $email		
) );
$findEmail = $query->fetch ();
if(!$findEmail){
	$sql = "insert into users(id,email)
							values (:id, :email);";
	$query = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$token = hash('sha256', $email);
	$query->execute(array(':id'=>$token, ':email'=>$email));
	SendMail($token, $idPetition);
}
else if(!$findEmail['active']){
	SendMail($findEmail['id'], $idPetition);
}else{
	$token = $findEmail['id'];
	$sql = "select *
		from signatures
		where id_user = :token and id_petition = :idPetition";
	$query = $db->prepare ( $sql, array (
			PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
	) );
	$query->execute ( array (
			':token' => $token,
			':idPetition' => $idPetition
	) );
	$findSignatures = $query->fetch ();
	if($findSignatures){
		header ( "Location: http://localhost/info.php?info=You already get up!");
	}else{
		$sql = "insert into signatures (id_user, id_petition)
				values (:id_user, :id_petition);";
		$query = $db->prepare ( $sql, array (
				PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
		) );
		$query->execute ( array (
				':id_user' => $token,
				':id_petition' => $idPetition
		) );
	}
	header ( "Location: http://localhost/petition.php?id=$idPetition");	
}
?>
