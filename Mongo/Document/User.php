<?php
/**
 * undocumented class
 *
 * @package default
 * @author Aaron Cox
 **/
class Epic_Mongo_Document_User extends Epic_Mongo_Document
{
	public $route = 'user';
	protected static $_baseType = 'user';
	protected static $_collectionName = 'users';
	
	
} // END class Epic_Mongo_User extends Epic_Mongo_Document