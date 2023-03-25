<?php

include './config.php';
include './jwt_utils.php';
include './get_access.php';

$postedData = file_get_contents('php://input');
$postedData = json_decode($postedData, true);
$sentUsername = $postedData['username'];
$sentPassword = $postedData['password'];


function verifyUser($userLogin, $userPWD)
{
	global $linkpdo;
	$req = $linkpdo->prepare('SELECT * FROM utilisateur WHERE identifiant = :id');
	$req->bindValue(':id', $userLogin, PDO::PARAM_STR);
	$req->execute();
	$data = $req->fetch();


	if (empty($data)) {
		deliver_response(401, 'Unauthorized: invalid username', null);
		return false;
	} else {
		$id = $data['Identifiant']; //ID from the database
		$mdp = $data['MDP']; //Password from the database
		$role = $data['Role']; //User role from the database (publisher or moderator)

		if ($userPWD != $mdp) {
		//if (!password_verify($userPWD, $mdp)) {
			deliver_response(401, 'Unauthorized: invalid password', null);
			return false;
		} else {
			$headers = array('alg' => 'HS256', 'typ' => 'JWT');
			$payload = array('username' => $userLogin, 'role' => $role, 'exp' => (time() + 6660));

			$jwt = generate_jwt($headers, $payload);
			deliver_response(200, "OK", $jwt);
			return true;
		}
	}
}

verifyUser($sentUsername, $sentPassword);

?>
