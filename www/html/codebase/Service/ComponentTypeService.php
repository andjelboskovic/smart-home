<?php


namespace Service;


use SmartHome\Factory\ComponentTypeFactory;
use SmartHome\Repository\ComponentTypeRepository;
use SmartHome\Persistence\ComponentType;

class ComponentTypeService
{
	/** @var ComponentTypeService */
	private static $instance;

	/** @var ComponentTypeRepository */
	private $componentTypeRepository;

	public static function getInstance(): ComponentTypeService
	{
		if (!isset(self::$instance)) {
			self::$instance = new ComponentTypeService();
		}
		return self::$instance;
	}

	public function addComponentType(string $name): ComponentType
	{
		$componentType = (new ComponentTypeFactory())->create($name);
		return $this->validateAndSave($componentType);
	}

	private function validateAndSave(ComponentType $componentType): ComponentType
	{
		$this->componentTypeRepository->save($componentType, true);
		return $componentType;
	}

}
