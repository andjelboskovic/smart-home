<?php


namespace SmartHome\Factory;


use DateTime;
use SmartHome\Persistence\ComponentType;

class ComponentTypeFactory
{
	public function create(string $name): ComponentType
	{
		$componentType = new ComponentType();
		$componentType->setName($name);
		$componentType->setDateCreated(new DateTime());
		$componentType->setDateUpdated(null);
		return $componentType;
	}
}
