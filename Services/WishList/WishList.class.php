<?php

namespace WishList\Services\WishList;

use Bundles\Parametres\Conf;

use Bundles\Bdd\Model;
use Bundles\Bdd\Db;
use Services\Timer\Timer;
use WishList\Models\WishlistModel;

class WishList {

	private $db;

	public function __construct() {
		$this->db = Db::init("wl_wishlist");
	}
	

	public function createList($post) {
		$model = WishlistModel::init($this->db);
		$newEntity = $model->getNewEntity();

		$newEntity['wis_type'] = $post['type'];
		$newEntity['wis_user_id'] = $_SESSION['user']['id'];
		$newEntity['wis_name'] = $post['interessed'];
		$newEntity['wis_date'] = Timer::formatToDateDB($post['date'], 'fr');

		$isSave = $model->setValues($newEntity)->save();
		$message = $isSave 
			? 'Liste Créée avec succès.' 
			: 'Un problème est survenue lors de la cration de la liste.' ;
		self::getErrorMessage($message);
		return $isSave;
	}


	protected static function getErrorMessage($message) {
		if(isset($_SESSION['message'])) $_SESSION["message"] .= $message;
		else $_SESSION['message'] = $message;
	}
}




?>