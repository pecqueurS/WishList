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
		$this->redirectUser('user', true);
		$response = array();
		
		return $response;
	}

	public function subscribe() {
		$this->redirectUser('user', true);
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
				self::getErrorMessage('Merci pour votre inscription.');
			}

			$this->redirectUser('webroot', false);
		}

		return $response;
	}

	public function signIn() {
		$this->redirectUser('user', true);
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
				self::getErrorMessage('Vous êtes maintenant connecté.');
				$this->redirectUser('user', true);
			} 

		}
		
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