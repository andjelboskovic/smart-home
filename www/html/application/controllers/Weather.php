<?php


class Weather extends MY_Controller
{
	const PARAMETER_COMPONENT_ID = 'component_id';
	const PARAMETER_TEMPERATURE = 'temperature';
	const PARAMETER_HUMIDITY = 'humidity';
	const PARAMETER_DATE_CREATED = 'date_created';
	const PARAMETER_DATE_UPDATED = 'date_updated';
	const ALLOWED_REQUEST_PARAMETERS = [self::PARAMETER_COMPONENT_ID];
	const TABLE_NAME = 'weather_reads';
	const REQUIRED_ADD_PARAMETERS = [
		self::PARAMETER_COMPONENT_ID,
		self::PARAMETER_TEMPERATURE,
		self::PARAMETER_HUMIDITY
	];

	public function index(): void
	{
		echo "Latest data";
		$temperatureAndHumidityReaders = $this->db->select('*')
			->from('component')
			->where('component_type_id', 1)
			->get()
			->result_array();
		foreach ($temperatureAndHumidityReaders as $temperatureAndHumidityReader) {
//			echo "<div style='margin-top:20px;margin-left:20px'>{$temperatureAndHumidityReader['name']}";
			echo "<table style='width:40%'>";
			echo "<tr>";
			echo "<th style='width:150px'>Time</th>";
			echo "<th>Temperature</th>";
			echo "<th>Humidity</th>";
			echo " </tr>";
			$query = $this->db->select('*')
				->from(self::TABLE_NAME)
				->where('component_id', $temperatureAndHumidityReader['id'])
				->order_by('date_created', 'DESC')
				->limit(10)
				->get();
			foreach ($query->result_array() as $read) {
				echo "<tr>";
				echo "<td>{$read['date_created']}</td>";
				echo "<td>{$read['temperature']}°C</td>";
				echo "<td>{$read['humidity']}%</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
		}
	}

	public function fetch()
	{
		try {
			$inputParameters = $this->getInputParameters();
			$this->validateInputParameters($inputParameters);
			return $this->jsonResponse($this->getWeatherData($inputParameters));
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

	private function getWeatherData(array $criteria): array
	{
		$query = $this->db->select('*')->from(self::TABLE_NAME);
		if (array_key_exists(self::PARAMETER_COMPONENT_ID, $criteria)) {
			$query = $query->where(self::PARAMETER_COMPONENT_ID,
				$criteria[self::PARAMETER_COMPONENT_ID]);
		}

		return $query->get()->result_array();
	}

	public function addWeatherData(): bool
	{
		try {
			$addParameters = $this->getAddParameters();
			$this->validateAddParameters($addParameters);
			return $this->jsonResponse($this->insertWeatherData(
				$addParameters[self::PARAMETER_COMPONENT_ID],
				$addParameters[self::PARAMETER_TEMPERATURE],
				$addParameters[self::PARAMETER_HUMIDITY]));
		} catch (Exception $e) {
			return $this->ajaxResponse([], $e->getMessage(), 500);
		}
	}

	private function getAddParameters(): array
	{
		$requestPayload = $this->receiveJSON(true);
		$allowedParameters = [];

		foreach (self::REQUIRED_ADD_PARAMETERS as $allowedParametar) {
			if (isset($requestPayload[$allowedParametar])) {
				$allowedParameters[$allowedParametar] = $requestPayload[$allowedParametar];
			}
		}

		return $allowedParameters;
	}

	private function validateAddParameters(array $addParameters): void
	{
		foreach (self::REQUIRED_ADD_PARAMETERS as $requiredParameter) {
			if (!array_key_exists($requiredParameter, $addParameters)) {
				throw new Exception("Parameter {$requiredParameter} is required.");
			}
		}
	}

	private function insertWeatherData(int $componentId, float $temperature, float $humidity): array
	{
		$timeNow = date("Y-m-d H:i:s");

		$this->db->insert(self::TABLE_NAME, [
			self::PARAMETER_COMPONENT_ID => $componentId,
			self::PARAMETER_TEMPERATURE => $temperature,
			self::PARAMETER_HUMIDITY => $humidity,
			self::PARAMETER_DATE_CREATED => $timeNow,
			self::PARAMETER_DATE_UPDATED => $timeNow
		]);

		return $this->db->where('id', $componentId)
			->where(self::PARAMETER_DATE_CREATED, $timeNow)
			->where(self::PARAMETER_DATE_UPDATED, $timeNow)
			->get(self::TABLE_NAME)
			->result_array()[0];
	}
}
