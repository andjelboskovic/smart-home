<?php


use SmartHome\TestFest;

class Test extends CI_Controller
{
	public function index()
	{
		echo (new TestFest)->works();

	}
}
