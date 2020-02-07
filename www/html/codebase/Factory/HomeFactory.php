<?php


namespace SmartHome\Factory;


use DateTime;
use SmartHome\Persistence\Home;

class HomeFactory
{
	public function create(string $name): Home
	{
		$home = new Home();
		$home->setName($name);
		$home->setDateCreated(new DateTime());
		$home->setDateUpdated(null);
		return $home;
	}
}
