<?php


namespace SmartHome\Persistence;

use DateTime;

class Home extends Entity
{
	/** @var string */
	private $name;

	/** @var DateTime */
	private $date_created;

	/** @var DateTime */
	private $date_updated;

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
	 * @return DateTime
	 */
	public function getDateUpdated(): DateTime
	{
		return $this->date_updated;
	}

	/**
	 * @param DateTime $date_updated
	 */
	public function setDateUpdated(DateTime $date_updated): void
	{
		$this->date_updated = $date_updated;
	}

}
