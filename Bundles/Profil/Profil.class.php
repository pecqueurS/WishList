<?php

namespace WishList\Bundles\Profil;

use Bundles\Parametres\Conf;

use WishList\Models\UserModel;

use Services\Encryptor\Encryptor;
use Services\Pictures\ConvertImg;
use Services\Timer\Timer;
use Services\Mails\Mails;

class Profil {


	private $post;


	public function __construct() {

	}



	// $post = $_POST
	public function inscription ($post) {
		$this->post = $post;
		// Verification de la parité des mots de passe
		if($post['pwd'] != $post['pwd2']) return false;

		$login = $this->post["login"];
		$mdp = Encryptor::code (Encryptor::crypt($this->post["pwd"]));
		$email = $this->post["email"];
		$lang = ($_SESSION["lang"] == "fr") ? 1 : 2 ;

		$code_activation = md5(microtime(TRUE)*100000);

		

		// Initialisation du profil
		$profil = ProfilModel::init();

		// Joueur existe-t-il
		if($profil->infosPlayer($post["login"])->getValues()) {
			if(isset($_SESSION["message"])) $_SESSION["message"] .= "Ce login est déja utilisé.";
			else $_SESSION["message"] = "Ce login est déja utilisé.";
			return FALSE;
		} else { // Le joueur n'existe pas
			// Enregistrement de l'avatar
			if ($_FILES["avatar"]["name"] != "") { // Si une image a été chargé
				$fichier_final = ConvertImg::init($_POST['login'], array(200,200))->convertJPG('avatar',AVATARS);
			} else { // Si aucunes images detectées
				$fichier_final = "avatarDefault.png";
			} 
			// Si l'enregistrement ne s'est pas bien passé
			if(!$fichier_final) {
				if(isset($_SESSION["message"])) $_SESSION["message"] .= "Une erreur s'est produite lors de l'enregistrement de votre image.";
				else $_SESSION["message"] = "Une erreur s'est produite lors de l'enregistrement de votre image.";
				return FALSE;
			} else { // Si tout s'est bien passé
				if($profil->addPlayer($login, $mdp, $email, $lang, $fichier_final, $code_activation)) {
					/*ENVOI D'EMAIL*/
					$response = Conf::$response;

					$response['login'] = $login;
					$response['pwd'] = $post['pwd'];
					$response['url'] = URL_CONFIRM_INS."?log=".$login."&code=".$code_activation;
					$response['activation'] = $code_activation;

					$destinataire = $email;
					$sujet = 'Inscription au jeu sur "'.Conf::$server['name'].'"';
					$message = array($response, 'inscription');
					$headers = array(Conf::$server['name'], Conf::$emails['webmaster'][0]);

					if (Mails::init('html')->sendMail($destinataire,$sujet,$message,$headers) === TRUE){
						return TRUE;
					}else {
						if(isset($_SESSION["message"])) $_SESSION["message"] .= "Une erreur s'est produite lors de l'envoi du mail d'activation.";
						else $_SESSION["message"] = "Une erreur s'est produite lors de l'envoi du mail d'activation.";
						return FALSE;
					}
				} else {
					if(isset($_SESSION["message"])) $_SESSION["message"] .= "Une erreur s'est produite lors de votre inscription.";
					else $_SESSION["message"] = "Une erreur s'est produite lors de votre inscription.";
					return FALSE;
				}
			}
		}
	}







	public function active_compte ($login, $code) {
		$profil = ProfilModel::init();
		$result = $profil->infosPlayer($login)->getValues();
		var_dump($result);
		if(!empty($result) && $result[0]['jou_activate'] == $code) {
			$result2 = $profil->setPlayer([1], ['jou_activate'], $result[0]['jou_id']);
			if($result2) return true;
		}

		return false;
	}







	public function forgot_pwd($post) {

		// Initialisation du profil
		$profil = ProfilModel::init();

		// Infos Joueur
		$result = $profil->infosPlayer($post["login"])->getValues();

		// Si l'email ne correspond pas a la source
	  	if(empty($result) || $result[0]["jou_email"] != $post["email"]) return false;

	  	else {
	  		// Creation d'un nouveau mot de passe
	  		$newPwd = Encryptor::newPwd();

			/*MODIFICATION MDP*/
			$result2 = $profil->setPlayer([$newPwd['encodePwd']], ['jou_mdp'], $result[0]['jou_id']);

			$response = Conf::$response;

			$response['login'] = $post["login"];
			$response['pwd'] = $newPwd['newPwd'];


			/*ENVOI D'EMAIL*/
			$destinataire = $result[0]["jou_email"];
			$sujet = 'Modification du mot de passe sur "'.Conf::$server['name'].'"';
			$message = array($response, 'forgotPwd');
			$headers = array(Conf::$server['name'], Conf::$emails['webmaster'][0]);

			if (Mails::init('html')->sendMail($destinataire,$sujet,$message,$headers) === TRUE && $result2){
				return TRUE;
			}else {
				return FALSE;
			}



	  	}

	  	


	}





	public function connexion($post) {

		// Initialisation du profil
		$profil = ProfilModel::init();

		// Infos Joueur
		$result = $profil->infosPlayer($post["login"])->getValues();

		// Le login existe ?
		if(!empty($result)) {
				// Joueur Activé ?
	  			if($result[0]['jou_activate'] != 1) {
					if(isset($_SESSION["message"])) $_SESSION["message"] .= "Veuillez tout d'abord activer votre compte.";
					else $_SESSION["message"] = "Veuillez tout d'abord activer votre compte.";
					return FALSE;
				}

				// Verification du mot de passe
				$mdp = Encryptor::crypt($post["pwd"]);
				$mdp2 = Encryptor::decode($result[0]['jou_mdp']);

				if($mdp != $mdp2) {
					$_SESSION["message"] = "Login ou mot de passe incorrect.";
					return FALSE;
				}

				/*INSERTION EN BDD DU NOUVEL INSCRIT*/
				$result2 = VerifConnectionsModel::init()->insert($result[0]["jou_id"]);
				$result4 = ConnectesModel::init()->insert($result[0]["jou_id"]);

			  	// Mise en session des informations et confirmation de la connexion si $result2 && $result4 = true
			  	if($result2 && $result4) {
			  		$_SESSION["joueur"] = $result[0];
			  		if($_SESSION["joueur"]["jou_langues_id"]==1) $_SESSION["lang"] = "fr";
			  		else $_SESSION["lang"] = "en";
			  	} else {
					if(isset($_SESSION["message"])) $_SESSION["message"] .= "Une erreur est survenue.";
					else $_SESSION["message"] = "Une erreur est survenue.";
					return FALSE;
			  	}
				return TRUE;
	  	} else {
	  		$_SESSION["message"] = "Login ou mot de passe incorrect.";
	  		return FALSE;
	  	}
	}





	public function deconnect(){

		$bdd = new BDD();
			$id_joueur = $_SESSION["joueur"]["jou_id"];

		/*MODIFICATION TABLE VERIF_CONNECTIONS*/
			$sql = "UPDATE verif_connections SET vec_deconnect = CURRENT_TIMESTAMP WHERE vec_deconnect IS NULL AND vec_joueurs_id = ? ";

		    $bind = "i";
		  	$arr = array($id_joueur);
		  
		  	$bdd->prepare($sql,$bind);
		  	$result1 = $bdd->execute($arr);
	
		/*SUPPRESSION JOUEUR dans TABLE CONNECTES*/
			$sql = "DELETE FROM connectes WHERE con_joueurs_id = ? ";

		    $bind = "i";
		  	$arr = array($id_joueur);
		  
		  	$bdd->prepare($sql,$bind);
		  	$result2 = $bdd->execute($arr);


			unset($_SESSION["joueur"]);
			unset($_SESSION["partie"]);

			$_SESSION["message"] = "Vous êtes Deconnecté.";

	}







	public function verif_connect(){
		if(isset($_SESSION["joueur"])) $id_joueur = $_SESSION["joueur"]["jou_id"];
		else header("location:".URL_ACCUEIL);

		/*CHERCHE INFO DE CONNEXION*/
		$bdd = new BDD();
			$sql = "SELECT con_id FROM connectes WHERE con_joueurs_id = ? ";

		    $bind = "i";
		  	$arr = array($id_joueur);
		  
		  	$bdd->prepare($sql,$bind);
		  	$result = $bdd->execute($arr);
	
		  	if(empty($result)) header("location:".URL_ACCUEIL);
	}







	public function update_profil($type,$post=array()){


		switch ($type) {
			case 'avatar':
				$this->post["login"] = $_SESSION["joueur"]["jou_login"];
				// FICHIERS
				$fichier_final = "";
				if ($_FILES["avatar"]["name"] != "") {
					// Traiter le fichier envoyé
					
					$erreur = "";
					$taille_maxi = 8000000;
					$taille = filesize($_FILES['avatar']['tmp_name']);
					/** Poids <8Mo **/
					if($taille>$taille_maxi) {
						$erreur .= 'Le fichier est trop gros'.ini_get('post_max_size').' Maximum.<br>';
						$_SESSION['message'] = $erreur;
						$fichier_final = "avatarDefault.png";
					}

					/** Type = Image **/
					if (strpos (  $_FILES['avatar']['type'] ,  'image' )!= FALSE){
						$erreur .= 'Le type de fichier n\'est pas pris en compte ou le fichier est corrompu.<br>';
						$_SESSION['message'] = $erreur;
						$fichier_final = "avatarDefault.png";
					}
					 
					// Envoi les erreurs ou alors converti l'image envoyé par l'utilisateur
					if ($erreur !== "") {
						$_SESSION['message'] = $erreur;
						$fichier_final = "avatarDefault.png";
					} else {
					    $fichier_final = $this->convertJPG($_FILES['avatar']['tmp_name'],AVATARS);
					}
					
				} else {
					$fichier_final = "avatarDefault.png";
				} 


				$update = $fichier_final;
				$_SESSION["joueur"]["jou_avatar"] = $update;

				break;
			
			case 'mdp':


	  			$mdp = $this->algo ($post["mdpOld"]);
				$mdp2 = $this->decode($_SESSION["joueur"]["jou_mdp"]);


				if($mdp == $mdp2) {
					$mdp_final = $this->code ($this->algo ($post["mdpNew"]));
					if(isset($_SESSION["message"])) $_SESSION["message"] .= "Le mot de passe a été modifié. C'est maintenant : ".$post["mdpNew"];
					else $_SESSION["message"] = "Le mot de passe a été modifié. C'est maintenant : ".$post["mdpNew"];
				}
				
				else {
					$mdp_final = $_SESSION["joueur"]["jou_mdp"];
					if(isset($_SESSION["message"])) $_SESSION["message"] .= "Le mot de passe n'a pas été modifé";
					else $_SESSION["message"] = "Le mot de passe n'a pas été modifé";
				}




				$update = $mdp_final;
				$_SESSION["joueur"]["jou_mdp"] = $update;

				break;


			case 'email':


	  			$email = $post["emailOld"];
				$email2 = $_SESSION["joueur"]["jou_email"];

				if($email == $email2) {
					$email_final = $post["emailNew"];
					if(isset($_SESSION["message"])) $_SESSION["message"] .= "L'email a été modifié. C'est maintenant : ".$post["emailNew"];
					else $_SESSION["message"] = "L'email a été modifié. C'est maintenant : ".$post["emailNew"];
				}
				
				else {
					$email_final = $_SESSION["joueur"]["jou_email"];
					if(isset($_SESSION["message"])) $_SESSION["message"] .= "L'email n'a pas été modifé";
					else $_SESSION["message"] = "L'email n'a pas été modifé";
				}

				$update = $email_final;
				$_SESSION["joueur"]["jou_email"] = $update;

				break;


			default:
				$type = false;
				break;
		}


		if($type != false) {
			$jou_id = $_SESSION["joueur"]["jou_id"];
			$bdd = new BDD();


			$sql = "UPDATE joueurs SET jou_$type = ? WHERE jou_id = ? ";

		    $bind = "si";
		  	$arr = array($update,$jou_id);
		  
		  	$bdd->prepare($sql,$bind);
		  	$result = $bdd->execute($arr);

		}




	}






	




	/**
	 * envoi_mail($message,$objet) Envoi d'un mail de confirmation avec code d'activation
	 * $mb_mail
	 * @global $config
	 * @return BOL
	 */
	private function envoi_mail($message,$objet){
 		global $config;
		$destinataire = $this->post["email"];
 		$sujet = 'Inscription à "Warriors of the Galaxy"' ;
 		$entete = "From: stephane.pecqueur@gmail.com" ;
 		
		return mail($destinataire, $objet, $message, $entete) ;
	}
	





}




?>