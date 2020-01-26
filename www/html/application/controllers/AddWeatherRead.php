<?php

class AddWeatherRead extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		date_default_timezone_set("Europe/Belgrade");
	}

	public function index()
	{
		if (!isset($_GET['device_id']) || !isset($_GET['temperature']) || !isset($_GET['humidity'])) {
			echo 'You must set device_id, temperature and humidity!';
			die;
		}

		$timeNow = date("Y-m-d H:i:s");
		echo $timeNow;
		$data = [
			'device_id' => $_GET['device_id'],
			'temperature' => $_GET['temperature'],
			'humidity' => $_GET['humidity'],
			'date_created' => $timeNow
		];

		$this->db->insert('temperature_reads', $data);
	}
}
