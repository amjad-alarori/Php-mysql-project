<?php

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	
	die ('Please complete the registration form!');
}

if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	
	die ('Please complete the registration form');
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	
	if ($stmt->num_rows > 0) {
		
		echo 'Username exists, please choose another!';
	} else {
		
if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
	
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
	$stmt->execute();
	echo 'You have successfully registered, you can now login!';
} else {
	
	echo 'Could not prepare statement!';
}
	}
	$stmt->close();
} else {
	
	echo 'Could not prepare statement!';
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {

	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		die ('Email is not valid!');
	}
	if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
		die ('Username is not valid!');
	}
	if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
		die ('Password must be between 5 and 20 characters long!');
	}};


$con->close();
?>