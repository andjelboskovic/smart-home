<?php


use SmartHome\Persistence\Device;
use SmartHome\Persistence\Home;
use SmartHome\Repository\ComponentRepository;
use SmartHome\Repository\DeviceRepository;
use SmartHome\Repository\HomeRepository;
use SmartHome\Persistence\Component;

class Component_view extends MY_Controller
{
	const PARAMETER_HOME_ID = 'home_id';
	const PARAMETER_DEVICE_ID = 'device_id';
	const PARAMETER_COMPONENT_ID = 'component_type_id';
	const PARAMETER_DATE_UPDATED = 'date_updated';
	const PARAMETER_IS_ACTIVE = 'is_active';
	const PARAMETER_ID = 'id';
	const IS_ACTIVE_ON = 'ON';
	const IS_ACTIVE_OFF = 'OFF';

	const ALLOWED_REQUEST_PARAMETERS = [
		self::PARAMETER_HOME_ID,
		self::PARAMETER_DEVICE_ID,
		self::PARAMETER_COMPONENT_ID
	];

	const REQUIRED_EDIT_PARAMETERS = [
		self::PARAMETER_ID,
		self::PARAMETER_IS_ACTIVE
	];

	const TABLE_NAME = 'component';

	public function index(): void
	{
		echo "Latest data<br>";
		$homes = HomeRepository::getInstance()->getAll();
		/** @var Home $home */
		foreach ($homes as $home) {
			$devices = DeviceRepository::getInstance()->findBy(
				[
					self::PARAMETER_HOME_ID => $home->getId()
				]
			);
			/** @var Device $device */
			foreach ($devices as $device) {
				/** @var Component $component */
				$component = ComponentRepository::getInstance()->findOneBy(
					[
						self::PARAMETER_COMPONENT_ID => 2,
						self::PARAMETER_DEVICE_ID => $device->getId()
					]
				);
				echo "<br>'" . $device->getName() . "' has a component turned : "
					. ($component->isActive() ? self::IS_ACTIVE_ON : self::IS_ACTIVE_OFF);
			}
		}
	}

	public function fetch(): bool
	{
		try {
			$inputParameters = $this->getInputParameters();
			$this->validateInputParameters($inputParameters);
			return $this->jsonResponse($this->getComponentData($inputParameters));
		} catch (Exception $e) {
			return $this->ajaxResponse([], $e->getMessage());
		}
	}

	private function getInputParameters(): array
	{
		$requestPayload = $this->receiveJSON(true);
		$allowedParameters = [];

		foreach (self::ALLOWED_REQUEST_PARAMETERS as $allowedParametar) {
			if (isset($requestPayload[$allowedParametar])) {
				$allowedParameters[$allowedParametar] = $requestPayload[$allowedParametar];
			}
		}

		return $allowedParameters;
	}

	private function validateInputParameters(array $parameters): void
	{
		foreach ($parameters as $parameter) {
			if (!is_numeric($parameter)) {
				throw new Exception("Parameter [{$parameter}] has to be numeric.");
			}
		}
	}

	public function getComponentData(array $criteria): array
	{
		$query = $this->db->select('*')->from('component');
		if (array_key_exists(self::PARAMETER_DEVICE_ID, $criteria)) {
			$query = $query->where(self::PARAMETER_DEVICE_ID, $criteria[self::PARAMETER_DEVICE_ID]);
		}
		if (array_key_exists(self::PARAMETER_COMPONENT_ID, $criteria)) {
			$query = $query->where(self::PARAMETER_COMPONENT_ID,
				$criteria[self::PARAMETER_COMPONENT_ID]);
		}

		return $query->get()->result_array();
	}

	public function editComponentData(): bool
	{
		try {
			$editParameters = $this->getEditParameters();
			$this->validateEditParameters($editParameters);
			return $this->jsonResponse($this->editComponent($editParameters['id'], $editParameters[self::IS_ACTIVE]));
		} catch (Exception $e) {
			return $this->ajaxResponse([], $e->getMessage(), 500);
		}
	}

	private function getEditParameters(): array
	{
		$requestPayload = $this->receiveJSON(true);
		$allowedParameters = [];

		foreach (self::REQUIRED_EDIT_PARAMETERS as $allowedParametar) {
			if (isset($requestPayload[$allowedParametar])) {
				$allowedParameters[$allowedParametar] = $requestPayload[$allowedParametar];
			}
		}

		return $allowedParameters;
	}

	private function validateEditParameters(array $editParameters): void
	{
		foreach (self::REQUIRED_EDIT_PARAMETERS as $requiredParameter) {
			if (!array_key_exists($requiredParameter, $editParameters)) {
				throw new Exception("Parameter {$requiredParameter} is required.");
			}
		}
	}

	private function editComponent(int $componentId, bool $isActive): array
	{
		$timeNow = date("Y-m-d H:i:s");

		$this->db->where('id', $componentId);
		$this->db->update('component', [
			self::PARAMETER_IS_ACTIVE => $isActive,
			self::PARAMETER_DATE_UPDATED => $timeNow
		]);

		return $this->db->where('id', $componentId)
			->get('component')
			->result_array()[0];
	}
}
