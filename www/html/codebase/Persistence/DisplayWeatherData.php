<?php

class DisplayWeatherData extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "Latest data";
        $devices = $this->db->get('device')->result_array();
        foreach ($devices as $device) {
            echo "<div style='margin-top:20px;margin-left:20px'>{$device['name']}";
            echo "<table style='width:40%'>";
            echo "<tr>";
            echo "<th style='width:150px'>Time</th>";
            echo "<th>Temperature</th>";
            echo "<th>Humidity</th>";
            echo " </tr>";
            $query = $this->db->select('*')->from('temperature_reads')->where('device_id', $device['id'])->order_by('date_created', 'DESC')->limit(10)->get();
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
}
