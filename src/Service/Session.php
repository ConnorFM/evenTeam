<?php

namespace App\Service;

class Session
{
	public function createSession($id,int $status)
	{	
		$_SESSION['id'] = $id;
		$_SESSION['status'] =$status;
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








