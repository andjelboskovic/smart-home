<?php


namespace SmartHome\Service;

use SmartHome\Factory\UserFactory;
use SmartHome\Persistence\User;
use SmartHome\Repository\UserRepository;

class UserService
{
	/** @var UserService */
	private static $instance;

	/** @var UserRepository */
	private $userRepository;

	public static function getInstance(): UserService
	{
		if (!isset(self::$instance)) {
			self::$instance = new UserService();
		}
		return self::$instance;
	}

	public function addUser(
		string $userName,
		string $password,
		string $email,
		bool $isActive
	): User {
		$device = (new UserFactory())->create($userName, $password, $email, $isActive);
		return $this->validateAndSave($device);
	}

	private function validateAndSave(User $device): User
	{
		$this->userRepository->save($device, true);
		return $device;
	}

}
