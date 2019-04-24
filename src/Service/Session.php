<?php

namespace App\Service;

class Session
{
	public function createSession($id,int $status, $name, $firstname)
	{
		$_SESSION['id'] = $id;
		$_SESSION['status'] =$status;
		$_SESSION['lastname'] =$name;
		$_SESSION['firstname'] =$firstname;

	}

	public function isConnected()
	{
		if (empty($_SESSION)) {
			return "Not connected";
		}
		if ($_SESSION['status'] == 1) {
			return "admin";
		} else {
			return "user";
		}
	}
}








