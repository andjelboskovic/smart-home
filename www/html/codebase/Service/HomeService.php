<?php


namespace SmartHome\Service;


use SmartHome\Factory\HomeFactory;
use SmartHome\Persistence\Home;
use SmartHome\Repository\HomeRepository;

class HomeService
{
	/** @var HomeService */
	private static $instance;

	/** @var HomeRepository */
	private $homeRepository;

	public static function getInstance(): HomeService
	{
		if (!isset(self::$instance)) {
			self::$instance = new HomeService();
		}
		return self::$instance;
	}

	public function addHome(string $name): Home
	{
		$home = (new HomeFactory())->create($name);
		return $this->validateAndSave($home);
	}

	private function validateAndSave(Home $home): Home
	{
		$this->homeRepository->save($home, true);
		return $home;
	}

}
