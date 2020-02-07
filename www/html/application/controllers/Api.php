<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use chriskacerguis\RestServer\Format;
use SmartHome\Exception\PlatformException;
use SmartHome\Repository\ComponentRepository;
use SmartHome\Repository\ComponentTypeRepository;
use SmartHome\Repository\DeviceRepository;
use SmartHome\Repository\HomeRepository;
use SmartHome\Repository\UserRepository;
use SmartHome\Repository\WeatherReadsRepository;
use SmartHome\Service\WeatherReadsService;

class Api extends RestController
{
	public function homes_get()
	{
		$criteria = $this->buildGetCriteria($this->get(), ['id']);

		$this->response(
			count($criteria) === 0
				? HomeRepository::getInstance()->getAll()
				: HomeRepository::getInstance()->findBy($criteria)
		);
	}

	private function buildGetCriteria(array $requestParams, array $allowedCriteriaCollection): array
	{
		$criteria = [];
		foreach ($allowedCriteriaCollection as $allowedCriteria) {
			if (array_key_exists($allowedCriteria, $requestParams)) {
				$criteria[$allowedCriteria] = $requestParams[$allowedCriteria];
			}
		}
		return $criteria;
	}

	public function components_get()
	{
		$criteria = $this->buildGetCriteria($this->get(), ['id', 'device_id']);

		$this->response(count($criteria) === 0
			? ComponentRepository::getInstance()->getAll()
			: ComponentRepository::getInstance()->findBy($criteria)
		);
	}

	public function component_types_get()
	{
		$criteria = $this->buildGetCriteria($this->get(), ['id']);

		$this->response(
			count($criteria) === 0
				? ComponentTypeRepository::getInstance()->getAll()
				: ComponentTypeRepository::getInstance()->findBy($criteria));
	}

	public function devices_get()
	{
		$criteria = $this->buildGetCriteria($this->get(), ['id', 'home_id']);

		$this->response(
			count($criteria) === 0
				? DeviceRepository::getInstance()->getAll()
				: DeviceRepository::getInstance()->findBy($criteria)
		);
	}

	public function users_get()
	{
		$criteria = $this->buildGetCriteria($this->get(), ['id']);

		$this->response(
			count($criteria) === 0
				? UserRepository::getInstance()->getAll()
				: UserRepository::getInstance()->findBy($criteria)
		);
	}

	public function weather_reads_get()
	{
		$criteria = $this->buildGetCriteria($this->get(), ['id', 'device_id']);

		$this->response(
			count($criteria) === 0
				? WeatherReadsRepository::getInstance()->getAll()
				: WeatherReadsRepository::getInstance()->findBy($criteria)
		);
	}

	public function weather_reads_post()
	{
		$params = $this->post();
		$this->validatePostParams(
			$params,
			[
				'device_id',
				'temperature',
				'humidity'
			],
			'/api/weather_reads'
		);
		$device = DeviceRepository::getInstance()->getById($params['device_id']);
		if (empty($device)) {
			$this->response(
				[
					"error" => "Device with id: {$params['device_id']} not found"
				],
				RestController::HTTP_BAD_REQUEST
			);
		}
		$this->response(WeatherReadsService::getInstance()->addWeatherRead(
			$device,
			$params['temperature'],
			$params['humidity']
		));
	}

	public function validatePostParams(array $parameters, array $requiredParams, string $endpoint): void
	{
		foreach ($requiredParams as $requiredParam) {
			if (!array_key_exists($requiredParam, $parameters)) {
				throw new PlatformException("Post {$endpoint} must have [{$requiredParam}]");
			}
		}
	}
}
