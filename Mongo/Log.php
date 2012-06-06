<?php
/**
 * Epic_Mongo_Log
 *
 * undocumented class
 * 
 * @author Aaron Cox <aaronc@fmanet.org>
 * @param undocumented class
 * @package undocumented class
 **/
class Epic_Mongo_Log extends Epic_Mongo_Document
{
	protected static $_collectionName = 'queryLog';
  
	public static function log($collection, $query, $sort = false, $limit = false, $skip = false) {
		$request = array(
			'query' => $query,
			'sort' => $sort, 
			'limit' => $limit,
			'skip' => $skip
		);
		$query = array(
			'collection' => $collection,
			'request' => $request,
		);
		$log = static::fetchOne($query);
		if(!$log) {
			$log = new static;
			$log->collection = $collection;
			$log->request = $request;
		}
		$log->requests++;
		try {
			$log->save();
		} catch (Exception $e) {
			// var_dump($log->export()); exit;
		}

		// var_dump($log->export()); exit;
	}
} // END class Epic_Mongo_Log