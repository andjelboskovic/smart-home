<?php


namespace SmartHome\Repository;


use SmartHome\Persistence\UserAccess;

class UserAccessRepository extends AbstractRepository
{
	const TABLE_NAME = "user_access";
	const ENTITY_CLASS = UserAccess::class;
}
