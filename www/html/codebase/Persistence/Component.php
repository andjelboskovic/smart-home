<?php


namespace SmartHome\Persistence;


use DateTime;
use Repository\ComponentTypeRepository;
use Repository\DeviceRepository;

class Component extends Entity
{

	/**
	 * @field(type=int)
	 * @var int
	 */
	private $component_type_id;

	/**
	 * @field(type=int)
	 * @var int
	 */
	private $device_id;

	/**
	 * @field(type=bool)
	 * @var bool
	 */
	private $is_active;

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

	/** @var ComponentType */
	private $component_type;

	/** @var Device */
	private $device;

	/**
	 * @return int
	 */
	public function getComponentTypeId(): int
	{
		return $this->component_type_id;
	}

	/**
	 * @param int $component_type_id
	 */
	public function setComponentTypeId(int $component_type_id): void
	{
		$this->component_type_id = $component_type_id;
		$this->component_type = null;
	}

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
	 * @return bool
	 */
	public function isActive(): bool
	{
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive(bool $is_active): void
	{
		$this->is_active = $is_active;
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
	 * @return ComponentType
	 */
	public function getComponentType(): ComponentType
	{
		if (empty($this->component_type)) {
			$this->component_type = ComponentTypeRepository::getInstance()->getById($this->component_type);
		}
		return $this->component_type;
	}

	/**
	 * @param ComponentType $component_type
	 */
	public function setComponentType(ComponentType $component_type): void
	{
		$this->component_type = $component_type;
		$this->component_type_id = $component_type->getId();
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
