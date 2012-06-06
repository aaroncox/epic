<?php
/**
 * R2Db_Mongo
 *
 * undocumented class
 * 
 * @author Aaron Cox <aaronc@fmanet.org>
 * @param undocumented class
 * @package undocumented class
 **/
class Epic_Mongo
{
	protected static $_profile = null;
	public static function db($type) {
		return Epic_Mongo_Schema::getInstance()->getCollectionForType($type);
	}
	public static function dbClass($type) {
		return Epic_Mongo_Schema::getInstance()->getClassForType($type);
	}
	public static function dataTypes($doc = false) {
		return Epic_Mongo_Schema::getInstance()->getClassesForType($doc->getType());
	}
	public static function newDoc($type) {
		$class = Epic_Mongo_Schema::getInstance()->getClassForType($type);
		return new $class;
	}
	public static function resolveReference($data) {
		return Epic_Mongo_Schema::getInstance()->resolveReference($data);
	}
	public static function setSiteProfile($profile) {
		static::$_profile = $profile;
	}
	public static function getSiteProfile() {
		return static::$_profile;
	}
} // END class R2Db_Mongo