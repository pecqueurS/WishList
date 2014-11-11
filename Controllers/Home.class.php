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
class Home {
	
	public function show() {
		$response = array();
		
		return $response;
	}

	public function subscribe() {
		$response = array();
		$subscribeForm = Forms::make('Subscribe'); /* Creation du formulaire d'après le json */
		if (!$subscribeForm->isValid()) { /* Vérification du formulaire en fonction des contraintes */
			$response['formSubscribe'] = $subscribeForm->render(); /* Création du HTML à afficher */
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

	public function signIn() {
		$response = array();
		$signIn = Forms::make('SignIn'); /* Creation du formulaire d'après le json */
		if (!$signIn->isValid()) { /* Vérification du formulaire en fonction des contraintes */
			$response['formSignIn'] = $signIn->render(); /* Création du HTML à afficher */
		} else {
			$post = array(
				'login' => $_POST['login'], 
				'pwd' => $_POST['pwd'],
			);

			$profil = new ProfilExtend();
			if ($profil->connection($post)) {
				$this->getErrorMessage('Vous êtes maintenant connecté.');
			}

			header('location:' . Conf::getLinks()->getConf()['user']);
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