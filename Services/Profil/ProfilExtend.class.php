<?php

namespace WishList\Services\Profil;

use Bundles\Parametres\Conf;

use Bundles\Bdd\Model;
use Bundles\Bdd\Db;

use WishList\Models\UserModel;

use Services\Encryptor\Encryptor;
use Services\Pictures\ConvertImg;
use Services\Timer\Timer;
use Services\Mails\Mails;
use Services\Profil\Profil;

class ProfilExtend Extends Profil {


	public function __construct() {
		$this->db = Db::init("wl_user");
	}
	





}




?>