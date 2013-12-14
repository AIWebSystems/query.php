<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Query Plugin
 *
 * Use tags to execute MySQL queries 
 *
 * @package		Query
 * @version		1.1
 * @author		Ryan Thompson - AI Web Systems, Inc.
 * @copyright	Copyright (c) 2008 - 2014 AI Web Systems, Inc.
 * @link		https://www.aiwebsystems.com/docs/plugins/query
 */
class Plugin_query extends Plugin
{
	/**
	 * Possible entries parameters
	 * @var array
	 */
	public $get_parameters = array(
		'select'			=> '*',
		'table'				=> null,
		'limit'				=> 1000,
		'where'				=> null,
		'order_by'			=> null,
		'sort'				=> 'DESC',
		);

	///////////////////////////////////////////////////////////////////////////////
	// --------------------------	METHODS 	  ------------------------------ //
	///////////////////////////////////////////////////////////////////////////////

	/**
	 * Get
	 *
	 * {{ query:get table="comments" select="comment" }}
	 * 		{{ field }}
	 * {{ /query:get }}
	 *
	 * @access	public
	 * @return	array
	 */
	public function get()
	{
		/**
		 * Get Plugin Attributes
		 */
		
		$parameters = array();
		
		foreach ($this->get_parameters as $parameter => $parameter_default)
		{
			$parameters[$parameter] = $this->getAttribute($parameter, $parameter_default);
		}

		
		/**
		 * Process the shit out of everything
		 */
		
		// Table
		$query = ci()->pdb->table($parameters['table']);

		// Select
		$query = $query->select(explode('|', $parameters['select']));

		// Where
		if ($parameters['where'])
			$query = $query->where($parameters['where']);

		// Order by
		if ($parameters['order_by'])
			$query = $query->orderBy($parameters['order_by'], $parameters['sort']);

		// Limit
		if ($parameters['limit'])
			$query = $query->limit($parameters['limit']);

		
		/**
		 * Donzo - build the return
		 */

		$return = array();

		// Count all results
		$return['total'] = $query->count();

		// Results
		$return['results'] = $query->get();

		return array($return);
	}
}
