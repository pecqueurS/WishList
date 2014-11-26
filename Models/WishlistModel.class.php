<?php


namespace WishList\Models;

use Bundles\Bdd\Db;
use Bundles\Bdd\Model;


class WishlistModel extends Model {

	protected $tableName = "wl_wishlist";


	public function getUserWishlist($userId) {
		$this->db->addRule('wis_user_id', $userId);
		$this->loadTable();

		return $this->getValues();
	}
}


?>