<?php


namespace Service;


use SmartHome\Factory\UserAccessFactory;
use SmartHome\Repository\UserAccessRepository;
use SmartHome\Persistence\Home;
use SmartHome\Persistence\User;
use SmartHome\Persistence\UserAccess;

class UserAccessService
{
	/** @var UserAccessService */
	private static $instance;

	/** @var UserAccessRepository */
	private $userAccessRepository;

	public static function getInstance(): UserAccessService
	{
		if (!isset(self::$instance)) {
			self::$instance = new UserAccessService();
		}
		return self::$instance;
	}

	public function addUserAccess(User $user, Home $home): UserAccess
	{
		$userAccess = (new UserAccessFactory())->create($user, $home);
		return $this->validateAndSave($userAccess);
	}

	private function validateAndSave(UserAccess $userAccess): UserAccess
	{
		$this->userAccessRepository->save($userAccess, true);
		return $userAccess;
	}

}
