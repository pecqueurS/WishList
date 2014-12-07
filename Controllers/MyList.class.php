<?php

namespace WishList\Controllers;

use Bundles\Formulaires\Forms;

use Bundles\Parametres\Conf;
use WishList\Models\UserModel;
use Bundles\Bdd\Db;
use Bundles\Bdd\Model;
use Services\Profil\Profil;

use WishList\Services\Profil\ProfilExtend;

/**
* 
*/
class Mylist {
	
	public function show($listId) {
		$this->redirectUser('webroot', false);
		$response = array();
		//var_dump($listId);
		return $response;
	}

	protected static function getErrorMessage($message) {
		if(isset($_SESSION['message'])) $_SESSION["message"] .= $message;
		else $_SESSION['message'] = $message;

	}

	protected function redirectUser($page, $isConnect = false) {
		if (ProfilExtend::isConnected() === $isConnect) {
			header('location:' . Conf::getLinks()->getConf()[$page]);
			die;
		}
	}




}



?>