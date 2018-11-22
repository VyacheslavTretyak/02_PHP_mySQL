<?php
echo "don't use?";
exit;

$token = $_GET ['token'];
$db = new PDO ( 'mysql:host=localhost;dbname=petitions_db', 'root', '', array (
		PDO::ATTR_PERSISTENT => true
) );
$sql = "select *
		from users
		where id=:token";
$query = $db->prepare ( $sql, array (
		PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
) );
$query->execute ( array (
		':token' => $token
) );
$findEmail = $query->fetch ();
if ($findEmail) {
	$sql = "update users
			set active = true
			where id=:token";
	$query = $db->prepare ( $sql, array (
			PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
	) );
	$query->execute ( array (
			':token' => $token
	) );
	$sql = "insert into signatures values(:id_petition, :id_user);";
	$query = $db->prepare ( $sql, array (
			PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
	) );
	$query->execute ( array (
			':id' => $token
	) );
	header ( "Location: http://localhost/index.php" );
}
else {
	header ( "Location: http://localhost/emailNotFound.php" );
}
?>
