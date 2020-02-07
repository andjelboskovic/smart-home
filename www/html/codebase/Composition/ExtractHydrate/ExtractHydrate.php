<?php

namespace SmartHome\Composition\ExtractHydrate;

use SmartHome\Composition\ExtractHydrate\Strategies\ConverterInterface;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeArray;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeBool;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeDate;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeDatetime;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeDefault;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeFloat;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeInt;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeObject;
use SmartHome\Composition\ExtractHydrate\Strategies\FieldTypeTimezone;
use SmartHome\Persistence\Entity;

trait ExtractHydrate
{
    /** @var ConverterInterface[] */
    private $converter = [];
    /** @var bool */
    private $strategiesInitialized = false;

    private function initializeStrategies(): void
    {
        $this->converter = [
            null => new FieldTypeDefault(),
            Entity::FIELD_TYPE_STRING => new FieldTypeDefault(),
            Entity::FIELD_TYPE_INT => new FieldTypeInt(),
            Entity::FIELD_TYPE_FLOAT => new FieldTypeFloat(),
            Entity::FIELD_TYPE_BOOL => new FieldTypeBool(),
            Entity::FIELD_TYPE_ARRAY => new FieldTypeArray(),
            Entity::FIELD_TYPE_OBJECT => new FieldTypeObject(),
            Entity::FIELD_TYPE_DATE => new FieldTypeDate(),
            Entity::FIELD_TYPE_DATETIME => new FieldTypeDatetime(),
            Entity::FIELD_TYPE_TIMEZONE => new FieldTypeTimezone()
        ];
        $this->strategiesInitialized = true;
    }

    protected function toDatabaseDto(string $entityClass, array $dto): array
    {
        $result = [];
        foreach ($dto as $field => $value) {
            $type = $this->getFieldAnnotationAttribute($entityClass, $field, 'type');
            $result[$field] = $this->resolveConverterForType($type)->convertToDatabaseDto($value);
        }
        return $result;
    }

    protected function fromDatabaseDto(string $entityClass, array $dto): array
    {
        $result = [];
        foreach ($dto as $field => $value) {
            $type = $this->getFieldAnnotationAttribute($entityClass, $field, 'type');
            $result[$field] = $this->resolveConverterForType($type)->convertFromDatabaseDto($value);
        }
        return $result;
    }

    protected function getFieldAnnotationAttribute(string $entityClass, string $field, string $attribute)
    {
        $entityFields = $entityClass::getEntityFields();
        return $entityFields[$field]['attributes'][$attribute] ?? null;
    }

    protected function buildEntityObject(string $entityClass, array $dto): Entity
    {
    	$object = new $entityClass($this->fromDatabaseDto($entityClass, $dto));
		$object->Id = 1;
        return $object;
    }

    private function resolveConverterForType($type): ConverterInterface
    {
        if (!$this->strategiesInitialized) {
            $this->initializeStrategies();
        }

        return $this->converter[$type];
    }
}
