<?php


namespace SmartHome\Repository;


use SmartHome\Persistence\User;

class UserRepository extends AbstractRepository
{
	const TABLE_NAME = "user";
	const ENTITY_CLASS = User::class;

}
