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
class User {
	
	public function show() {
		$response = array();
		$profilForm = Forms::make('Profil'); /* Creation du formulaire d'après le json */
		$profilForm->changeOption('email', 'value', (array) $_SESSION['user']['use_email']);
		$newListForm = Forms::make('NewList'); /* Creation du formulaire d'après le json */
		if (!$profilForm->isValid() && !$newListForm->isValid()) { /* Vérification du formulaire en fonction des contraintes */
			$response['formProfil'] = $profilForm->render(); /* Création du HTML à afficher */
			$response['formNewList'] = $newListForm->render(); /* Création du HTML à afficher */
		} else {
			$post = array(
				'login' => $_POST['login'], 
				'pwd' => $_POST['pwd'],
				'pwd2' => $_POST['pwd2'],
				'email' => $_POST['email'],
			);

			$profil = new ProfilExtend();
			if ($profil->subscription($post)) {
				$this->getErrorMessage('Merci pour votre inscription.');
			}

			header('location:' . Conf::getLinks()->getConf()['webroot']);
			die();
		}

		return $response;
}






	protected function getErrorMessage($message) {
		if(isset($_SESSION['message'])) $_SESSION["message"] .= $message;
		else $_SESSION['message'] = $message;

	}




}



?>