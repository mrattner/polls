<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home controller: Displays the application.
 */
class Home extends CI_Controller {
	/**
	 * Load the application page.
	 */
	public function index () {
		$this->load->helper('url');
		$this->load->view('home');
	}
}
