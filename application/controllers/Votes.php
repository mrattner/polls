<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Votes controller: Provides API methods related to votes.
 */
class Votes extends CI_Controller {
	/**
	 * Constructor: Load the required model.
	 */
	public function __construct () {
		parent::__construct();
		$this->load->model('vote');
	}
	
	/**
	 * Retrieve JSON data for a single poll's votes. (GET votes/[:pollId])
	 * @param int $pollId ID of poll whose votes to count
	 * @return JSON containing 'votes' attribute, which is a list of integers
	 * counting the votes for each option in the poll
	 */
	public function getVotes ($pollId) {
		$voteCounts = $this->vote->count($pollId);
		
		if (is_null($voteCounts)) {
			$this->output->set_status_header(404);
		} else {			
			$this->output->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode(array('votes' => $voteCounts)));
		}
		
	}
	
	/**
	 * Reset votes on a poll. (DELETE votes/[:pollId])
	 * @param int $pollId ID of poll whose votes to reset
	 */
	public function resetVotes ($pollId) {
		$deleted = $this->vote->deleteAll($pollId);
		if (!$deleted) {
			$this->output->set_status_header(404);
		} else {
			$this->output->set_status_header(200);
		}
	}
	
	/**
	 * Vote on a poll. (POST votes/[:pollId]/[:vote])
	 * @param int $pollId ID of poll to vote on
	 * @param int $vote Which option to vote for
	 */
	public function vote ($pollId, $vote) {
		$address = $this->input->ip_address();
		$voted = $this->vote->create($address, $pollId, $vote);
		if (!$voted) {
			$this->output->set_status_header(404);
		} else {
			$this->output->set_status_header(200);
		}
	}
}
