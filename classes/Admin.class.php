<?php

// require_once __DIR__ . '/../includes/autoload.inc.php';


class Admin {
	
	public static function is_admin() {
		return (isset($_SESSION['admin']) && $_SESSION['admin'] === TRUE);
	}
	
	public static function restrict($redirect) {
		if(!Admin::is_admin()) {
			header('Location: '.'../auth/signin.php?redirect='.$redirect);
		}
	}
}