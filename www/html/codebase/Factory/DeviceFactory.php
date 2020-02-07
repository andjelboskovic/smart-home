<?php


namespace SmartHome\Factory;


use DateTime;
use SmartHome\Persistence\Device;
use SmartHome\Persistence\Home;

class DeviceFactory
{
	public function create(Home $home, string $name): Device
	{
		$device = new Device();
		$device->setHome($home);
		$device->setName($name);
		$device->setDateCreated(new DateTime());
		$device->setDateUpdated(null);
		return $device;
	}
}
