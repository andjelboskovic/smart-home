<?php


namespace SmartHome\Persistence;


use DateTime;

class User extends Entity
{

	/**
	 * @field(type=string)
	 * @var string
	 */
	private $userName;

	/**
	 * @field(type=string)
	 * @var string
	 */
	private $email;

	/**
	 * @field(type=string)
	 * @var string
	 */
	private $password;

	/**
	 * @field(type=bool)
	 * @var bool
	 */
	private $active;

	/**
	 * @return string
	 */
	public function getUserName(): string
	{
		return $this->userName;
	}

	/**
	 * @param string $userName
	 */
	public function setUserName(string $userName): void
	{
		$this->userName = $userName;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->active;
	}

	/**
	 * @param bool $active
	 */
	public function setActive(bool $active): void
	{
		$this->active = $active;
	}

}
