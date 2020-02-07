<?php


namespace SmartHome\Repository;


use SmartHome\Persistence\Home;

class HomeRepository extends AbstractRepository
{
	const TABLE_NAME = "home";
	const ENTITY_CLASS = Home::class;

	public function getByName(string $name): ?Home
	{
		return $this->findOneBy(['name' => $name]);
	}
}
