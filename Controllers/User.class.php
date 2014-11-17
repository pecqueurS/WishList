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
		$this->redirectUser('webroot', false);
		$response = array();
		$profilForm = Forms::make('Profil'); /* Creation du formulaire d'après le json */
		$profilForm->changeOption('email', 'value', (array) $_SESSION['user']['email']);
		$newListForm = Forms::make('NewList'); /* Creation du formulaire d'après le json */

		// Formualaire profil	
		if (isset($_POST['submitProfil'])) {
			if ($profilForm->isValid()) {  
				$post = array(
					'oldPwd' => $_POST['oldPwd'], 
					'pwd' => $_POST['pwd'],
					'pwd2' => $_POST['pwd2'],
					'email' => $_POST['email']
				);

				$profil = new ProfilExtend();
				if ($profil->update($post)) {
					self::getErrorMessage('Profil mis à jour.');
				} 
				$this->redirectUser('user', true);
			}

		// Formulaire new list
		} elseif (isset($_POST['submitNewList'])) {
			if ($newListForm->isValid()) {  
				$post = array(
					'type' => $_POST['type'], 
					'interessed' => $_POST['interessed'],
					'date' => $_POST['date']
				);
			}
		}

		$response['formProfil'] = $profilForm->render(); /* Création du HTML à afficher */
		$response['formNewList'] = $newListForm->render(); /* Création du HTML à afficher */

		/*if (!$profilForm->isValid() && !$newListForm->isValid()) {  Vérification du formulaire en fonction des contraintes 
		}*/ 
		/*else {
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

			header('location:' . Conf::getLinks()->getConf()['webroot']);
			die();
		}*/

		return $response;
	}


	public function out() {
		ProfilExtend::disconnect();
		$this->redirectUser('webroot', false);
	}


	public function forgetPwd() {
		$this->redirectUser('user', true);
		$response = array();
		$forgetPwd = Forms::make('ForgetPwd'); /* Creation du formulaire d'après le json */
		if (!$forgetPwd->isValid()) { /* Vérification du formulaire en fonction des contraintes */
			$response['ForgetPwd'] = $forgetPwd->render(); /* Création du HTML à afficher */
		} else {
			$post = array(
				'email' => $_POST['email']
			);

			$profil = new ProfilExtend();
			if ($profil->forgot_pwd($post)) {
				self::getErrorMessage('Un message a été envoyé à l\'adresse indiquée.');
				$this->redirectUser('connexion', false);
			} 

		}
		
		return $response;
	}

	



	protected static function getErrorMessage($message) {
		if(isset($_SESSION['message'])) $_SESSION["message"] .= $message;
		else $_SESSION['message'] = $message;

	}

	protected function redirectUser($page, $isConnected = false) {
		if (ProfilExtend::isConnected() === $isConnected) {
			header('location:' . Conf::getLinks()->getConf()[$page]);
			die;
		}
	}




}



?>