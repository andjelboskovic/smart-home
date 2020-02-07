<?php


namespace SmartHome\Persistence;


use DateTime;
use SmartHome\Repository\DeviceRepository;

class WeatherReads extends Entity
{
	/**
	 * @field(type=int)
	 * @var int
	 */
	private $device_id;

	/**
	 * @field(type=float)
	 * @var float
	 */
	private $temperature;

	/**
	 * @field(type=float)
	 * @var float
	 */
	private $humidity;

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

	/** @var Device */
	private $device;

	/**
	 * @return int
	 */
	public function getDeviceId(): int
	{
		return $this->device_id;
	}

	/**
	 * @param int $device_id
	 */
	public function setDeviceId(int $device_id): void
	{
		$this->device_id = $device_id;
		$this->device = null;
	}

	/**
	 * @return float
	 */
	public function getTemperature(): float
	{
		return $this->temperature;
	}

	/**
	 * @param float $temperature
	 */
	public function setTemperature(float $temperature): void
	{
		$this->temperature = $temperature;
	}

	/**
	 * @return float
	 */
	public function getHumidity(): float
	{
		return $this->humidity;
	}

	/**
	 * @param float $humidity
	 */
	public function setHumidity(float $humidity): void
	{
		$this->humidity = $humidity;
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
	 * @return Device
	 */
	public function getDevice(): Device
	{
		if (empty($this->device)) {
			$this->device = DeviceRepository::getInstance()->getById($this->device);
		}
		return $this->device;
	}

	/**
	 * @param Device $device
	 */
	public function setDevice(Device $device): void
	{
		$this->device = $device;
		$this->device_id = $device->getId();
	}

}
