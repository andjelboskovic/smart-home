<?php


use SmartHome\Persistence\Device;

class Test extends CI_Controller
{
	public function index()
	{
		$device = new Device([
			'id' => 1,
			'home_id' => 1,
			'name' => 'test'
		]);

		var_dump($device->getId());
	}
}
