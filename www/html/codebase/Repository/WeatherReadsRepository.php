<?php


namespace SmartHome\Repository;


use SmartHome\Persistence\Device;
use SmartHome\Persistence\WeatherReads;

class WeatherReadsRepository extends AbstractRepository
{
	const TABLE_NAME = "weather_reads";
	const ENTITY_CLASS = WeatherReads::class;

	public function getWeatherReadsByDevice(Device $device): array
	{
		return $this->findBy(['device_id' => $device->getId()]);
	}
}
