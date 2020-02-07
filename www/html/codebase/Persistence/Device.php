<?php


namespace SmartHome\Persistence;


use DateTime;
use SmartHome\Repository\HomeRepository;

class Device extends Entity
{
//	private $id;

	/**
	 * @field(type=int)
	 * @var int
	 */
	private $home_id;

	/**
	 * @field(type=string)
	 * @var string
	 */
	private $name;

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

	/** @var Home */
	private $home;

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
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
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
	 * @param DateTime|null $date_updated
	 */
	public function setDateUpdated(?DateTime $date_updated): void
	{
		$this->date_updated = $date_updated;
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
