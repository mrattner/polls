<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Generic Model class based on CodeIgniter model that has a few basic functions
 * that all models require.
 */
class MY_Model extends CI_Model {	
	/**
	 * Constructor: Load the database.
	 */
	public function __construct () {
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * Given a row from the database, copy all database column values into 
	 * 'this', converting column names to field names by converting first char 
	 * to lowercase.
	 */
	public function load ($row) {
		foreach ((array) $row as $field => $value) {
			$fieldName = strtolower($field[0]) . substr($field, 1);
			$this->$fieldName = $value;
		}
	}
	
	/**
	 * Detects whether a database error has occurred on a result.
	 * @throws A generic exception if a DB error has occurred
	 */
	public static function checkResult ($result) {
		if (!$result) {
			throw new Exception('Database error');
		}
	}
}
