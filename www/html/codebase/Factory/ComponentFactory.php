<?php


namespace SmartHome\Factory;


use DateTime;
use SmartHome\Persistence\Component;
use SmartHome\Persistence\ComponentType;
use SmartHome\Persistence\Device;

class ComponentFactory
{

	public function create(ComponentType $componentType, Device $device, bool $isActive): Component
	{
		$component = new Component();
		$component->setComponentType($componentType);
		$component->setDevice($device);
		$component->setIsActive($isActive);
		$component->setDateCreated(new DateTime());
		$component->setDateUpdated(null);
		return $component;
	}
}
