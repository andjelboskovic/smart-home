<?php


namespace SmartHome\Repository;


use Exception;
use SmartHome\Persistence\Component;
use SmartHome\Persistence\ComponentType;
use SmartHome\Persistence\Device;

class ComponentRepository extends AbstractRepository
{
	const TABLE_NAME = "component";
	const ENTITY_CLASS = Component::class;

	/**
	 * @param Device $device
	 * @param ComponentType|null $componentType
	 * @return Component[]
	 * @throws Exception
	 */
	public function getByDeviceAndComponent(Device $device, ?ComponentType $componentType = null): array {

		$query = $this->queryBuilder->from($this->getTableName());
		$query->where('device_id', $device->getId());
		if($componentType !== null){
			$query->where('component_type_id', $componentType->getId());
		}

		return $this->buildArrayOfObjects($query->fetchAll());
	}
}
