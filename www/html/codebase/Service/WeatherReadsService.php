<?php


namespace SmartHome\Service;


use SmartHome\Factory\WeatherReadsFactory;
use SmartHome\Persistence\Device;
use SmartHome\Repository\WeatherReadsRepository;
use SmartHome\Persistence\WeatherReads;

class WeatherReadsService
{
	/** @var WeatherReadsService */
	private static $instance;

	/** @var WeatherReadsRepository */
	private $weatherReadsRepository;

	public function __construct(WeatherReadsRepository $weatherReadsRepository)
	{
		$this->weatherReadsRepository = $weatherReadsRepository;
	}

	public static function getInstance(): WeatherReadsService
	{
		if (!isset(self::$instance)) {
			self::$instance = new self(
				WeatherReadsRepository::getInstance()
			);
		}
		return self::$instance;
	}

	public function addWeatherRead(Device $device, float $temperature, float $humidity): WeatherReads
	{
		$weatherReads = (new WeatherReadsFactory())->create($device, $temperature, $humidity);
		return $this->validateAndSave($weatherReads);
	}

	private function validateAndSave(WeatherReads $weatherReads): WeatherReads
	{
		$this->weatherReadsRepository->save($weatherReads, true);
		return $weatherReads;
	}

}
