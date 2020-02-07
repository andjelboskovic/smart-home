<?php


namespace SmartHome\Persistence;


use DateTime;
use SmartHome\Repository\UserRepository;
use SmartHome\Repository\HomeRepository;

class UserAccess extends Entity
{

	/**
	 * @field(type=int)
	 * @var int
	 */
	private $user_id;

	/**
	 * @field(type=int)
	 * @var int
	 */
	private $home_id;

	/**
	 * @field(type=datetime)
	 * @var DateTime
	 */
	private $date_created;

	/**
	 * @field(type=datetime)
	 * @var DateTime|null
	 */
	private $date_updated;

	/** @var User */
	private $user;

	/** @var Home */
	private $home;

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->user_id;
	}

	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id): void
	{
		$this->user_id = $user_id;
		$this->user = null;
	}

	/**
	 * @return int
	 */
	public function getHomeId(): int
	{
		return $this->home_id;
	}

	/**
	 * @param int $home_id
	 */
	public function setHomeId(int $home_id): void
	{
		$this->home_id = $home_id;
		$this->home = null;
	}

	/**
	 * @return DateTime
	 */
	public function getDateCreated(): DateTime
	{
		return $this->date_created;
	}

	/**
	 * @param DateTime $date_created
	 */
	public function setDateCreated(DateTime $date_created): void
	{
		$this->date_created = $date_created;
	}

	/**
	 * @return DateTime|null
	 */
	public function getDateUpdated(): ?DateTime
	{
		return $this->date_updated;
	}

	/**
	 * @param DateTime|null $data_updated
	 */
	public function setDateUpdated(?DateTime $date_updated): void
	{
		$this->date_updated = $date_updated;
	}

	/**
	 * @return User
	 */
	public function getUser(): User
	{
		if (empty($this->user)) {
			$this->user = UserRepository::getInstance()->getById($this->user);
		}
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser(User $user): void
	{
		$this->user = $user;
		$this->user_id = $user->getId();
	}

	/**
	 * @return Home
	 */
	public function getHome(): Home
	{
		if (empty($this->home)) {
			$this->home = HomeRepository::getInstance()->getById($this->home);
		}
		return $this->home;
	}

	/**
	 * @param Home $home
	 */
	public function setHome(Home $home): void
	{
		$this->home = $home;
		$this->home_id = $home->getId();
	}

}
