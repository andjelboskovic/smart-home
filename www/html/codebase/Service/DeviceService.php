<?php


namespace SmartHome\Service;



use SmartHome\Factory\DeviceFactory;
use SmartHome\Repository\DeviceRepository;
use SmartHome\Persistence\Device;
use SmartHome\Persistence\Home;

class DeviceService
{
	/** @var DeviceService */
	private static $instance;

	/** @var DeviceRepository */
	private $deviceRepository;

	public static function getInstance(): DeviceService
	{
		if (!isset(self::$instance)) {
			self::$instance = new DeviceService();
		}
		return self::$instance;
	}

	public function addDevice(Home $home, string $name): Device
	{
		$device = (new DeviceFactory())->create($home, $name);
		return $this->validateAndSave($device);
	}

	private function validateAndSave(Device $device): Device
	{
		$this->deviceRepository->save($device, true);
		return $device;
	}

}
