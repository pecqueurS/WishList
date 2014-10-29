<?php

namespace WishList\Controllers;

use Bundles\Formulaires\Forms;
use WishList\Bundles\Profil\Profil;

use Bundles\Parametres\Conf;
use WishList\Models\UserModel;

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
			$test = UserModel::init();
			$test->loadTable();

			/*$newTest = UserModel::getNewEntity();
			$newTest['use_name'] = 'grosTest';
			$newTest['use_pwd'] = 'grosTest';
			$newTest['use_email'] = 'grosTest';
			echo $test->setValues($newTest)->save());*/

			//echo $test->setValues(array('use_id' => '6', 'use_name' => 'petit_test'))->save();

			//echo $test->setValues(array('use_id' => '6'))->delete();
			

			echo "formulaire valide !"; /* Traitement à effectuer */
		}

		return $response;
	}

	public function signIn() {
		$response = array();
		$signIn = Forms::make('SignIn'); /* Creation du formulaire d'après le json */
		if (!$signIn->isValid()) { /* Vérification du formulaire en fonction des contraintes */
			$response['formSignIn'] = $signIn->render(); /* Création du HTML à afficher */
		} else {
			echo "formulaire valide !"; /* Traitement à effectuer */
		}
		
		return $response;
	}

}



?>