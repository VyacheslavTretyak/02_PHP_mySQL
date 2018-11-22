<?php
function SendMail($token){
	$msg = "http://localhost/confirmation.php?token=$token";
	mail ( "reg@petition.org", "Confirmation", $msg );
	header ( "Location: http://localhost/info.php?info=We send confirmation email on your address!" );
}

$email = $_POST ['email'];
$subject = $_POST ['subject'];
$body = $_POST ['body'];
$active = 0;
$db = new PDO ( 'mysql:host=localhost;dbname=petitions_db', 'root', '', array (
		PDO::ATTR_PERSISTENT => true
) );
$sql = "select *
					from users
					where email=:email;";
$query = $db->prepare ( $sql, array (
		PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
) );
$query->execute ( array (
		':email' => $email
) );
$findEmail = $query->fetch ();
if (! $findEmail) {
	$sql = "insert into users(id,email)
							values (:id, :email);";
	$query = $db->prepare ( $sql, array (
			PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
	) );
	$token = hash ( 'sha256', $email );
	$query->execute ( array (
			':id' => $token,
			':email' => $email
	) );
	SendMail ( $token );
}
else if (! $findEmail ['active']) {
	SendMail ( $findEmail ['id'] );
}
else {
	$active = 1;
}
if ($findEmail) {
	$token = $findEmail ['id'];
}

$sql = "select *
						from petitions
						where subject=:subject;";
$query = $db->prepare ( $sql, array (
		PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
) );
$query->execute ( array (
		':subject' => $subject
) );
$findPetition = $query->fetch ();
if (! $findPetition) {

	$sql = "insert into petitions(id_autor, subject, body, active)
							values (:autor, :subject, :body, :active);";
	$query = $db->prepare ( $sql, array (
			PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
	) );
	$query->execute ( array (
			':autor' => $token,
			':subject' => $subject,
			':body' => $body,
			':active' => $active
	) );
	header ( "Location: http://localhost/info.php?info=The petition is saved!" );
}
else {
	header ( "Location: http://localhost/info.php?info=The petition with this subject is exists!" );
}

?>


