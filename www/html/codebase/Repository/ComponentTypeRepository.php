<?php


namespace SmartHome\Repository;


use SmartHome\Persistence\ComponentType;

class ComponentTypeRepository extends AbstractRepository
{

	const TABLE_NAME = "component_type";
	const ENTITY_CLASS = ComponentType::class;


}
