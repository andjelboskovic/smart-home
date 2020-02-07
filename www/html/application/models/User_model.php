<?php


use SmartHome\Repository\UserRepository;
use SmartHome\Service\UserService;

class User_model extends CI_Model
{

	public function insert_user($name, $email, $password)
	{
		UserService::getInstance()->addUser($name, md5($password), $email, true);
	}

	public function login_user($username, $password)
	{
		$user = UserRepository::getInstance()->findBy(
			[
				'username' => $username,
				'password' => md5($password)
			]
		);
		return count($user) === 1;
	}
}



