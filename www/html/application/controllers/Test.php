<?php

use SmartHome\Repository\HomeRepository;

class Test extends CI_Controller
{
	public function index()
	{
		echo "Test";

		$homeRepository = HomeRepository::getInstance();
		$firstHome = $homeRepository->findBy(['id' => 1]);

		var_dump($firstHome);

	}
}
