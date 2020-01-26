<?php


class DisplayRelayState extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		echo "Latest data";
		$homes = $this->db->get('home')->result_arrays();
		foreach ($homes as $home) {
			$devices = $this->db->get('device')->where('home_id', $home['id'])->result_arrays();
			foreach ($devices as $device) {
				$relay = $this->db->get('component')->where('component_type_id', 2)->where('device_id', $device['id'])->result_array();
				echo "<br>Device: " . $device['name'] . " has state: " . $relay['is_active'];
			}
		}
	}
}
