<?php


namespace SmartHome\Service;


use SmartHome\Factory\ComponentFactory;
use SmartHome\Persistence\Component;
use SmartHome\Persistence\ComponentType;
use SmartHome\Persistence\Device;
use SmartHome\Repository\ComponentRepository;

class ComponentService
{
	/** @var ComponentService */
	private static $instance;

	/** @var ComponentRepository */
	private $componentRepository;

	public static function getInstance(): ComponentService
	{
		if (!isset(self::$instance)) {
			self::$instance = new ComponentService();
		}
		return self::$instance;
	}

	public function addComponent(ComponentType $componentType, Device $device, bool $isActive): Component
	{
		$component = (new ComponentFactory())->create(
			$componentType,
			$device,
			$isActive
		);
		return $this->validateAndSave($component);
	}

	private function validateAndSave(Component $component): Component
	{
		$this->componentRepository->save($component, true);
		return $component;
	}

}
