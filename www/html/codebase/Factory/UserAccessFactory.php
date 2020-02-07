<?php


namespace SmartHome\Factory;


use DateTime;
use SmartHome\Persistence\Home;
use SmartHome\Persistence\User;
use SmartHome\Persistence\UserAccess;

class UserAccessFactory
{
	public function create(User $user, Home $home): UserAccess
	{
		$userAccess = new UserAccess();
		$userAccess->setUser($user);
		$userAccess->setHome($home);
		$userAccess->setDateCreated(new DateTime());
		$userAccess->setDateUpdated(null);
		return $userAccess;
	}
}
