<?php


namespace SmartHome\Factory;


use DateTime;
use SmartHome\Persistence\Device;
use SmartHome\Persistence\WeatherReads;

class WeatherReadsFactory
{
	public function create(Device $device, float $temperature, float $humidity): WeatherReads
	{
		$weatherReads = new WeatherReads();
		$weatherReads->setDevice($device);
		$weatherReads->setHumidity($humidity);
		$weatherReads->setTemperature($temperature);
		$weatherReads->setDateCreated(new DateTime());
		$weatherReads->setDateUpdated(null);
		return $weatherReads;
	}
}
