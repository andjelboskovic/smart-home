<?php


namespace SmartHome\Factory;


use SmartHome\Persistence\User;

class UserFactory
{
	public function create(
		string $userName,
		string $password,
		string $email,
		bool $isActive
	): User {
		$user = new User();
		$user->setUserName($userName);
		$user->setPassword($password);
		$user->setEmail($email);
		$user->setActive($isActive);
		return $user;
	}
}
