<?php


namespace SmartHome\Repository;


use Exception;
use SmartHome\Persistence\Device;
use SmartHome\Persistence\Home;

class DeviceRepository extends AbstractRepository
{

	const TABLE_NAME = "device";
	const ENTITY_CLASS = Device::class;

	/**
	 * @param Home $home
	 * @return Device[]
	 * @throws Exception
	 */
	public function getDevicesForHome(Home $home): array
	{
		return $this->findBy(['home_id' => $home->getId()]);
	}
}
