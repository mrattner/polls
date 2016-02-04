<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Polls controller: Provides API methods related to polls.
 */
class Polls extends CI_Controller {
	/**
	 * Constructor: Load the required model.
	 */
	public function __construct () {
		parent::__construct();
		$this->load->model('poll');
	}
	
	/**
	 * Retrieve JSON list of polls. (GET polls)
	 * @return JSON list of polls. If there are no polls, the list is empty but
	 * the request is still successful.
	 */
	public function getAllPolls () {
		$pollList = $this->poll->readAll();
		$this->output->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($pollList));
	}
	
	/**
	 * Create a new poll. (POST polls)
	 * @return 'Location' header with a link to the new poll
	 */
	public function createPoll () {
		$pollData = json_decode($this->input->raw_input_stream);
		
		$title = isset($pollData->title) ? $pollData->title : null;
		$question = isset($pollData->question) ? $pollData->question : null;
		$answers = isset($pollData->answers) ? $pollData->answers : null;
		
		// Check that all fields are present and the correct length
		$invalid = !isset($title) || !isset($question) || !isset($answers)
		|| count($answers) < 2 || strlen($title) > 50 || strlen($question) > 255
		|| strlen($title) === 0 || strlen($question) === 0;
		// Check answer field for correct length
		if (!$invalid) {
			foreach ($answers as $answer) {
				if (strlen($answer) > 255 || strlen($answer) === 0) {
					$invalid = true;
					break;
				}
			}
		}
		if ($invalid) {
			$this->output->set_status_header(400, 'Incorrect request format');
			return;
		}
		
		$newPollId = $this->poll->create($title, $question, $answers);
		if (!$newPollId) {
			$this->output->set_status_header(500, 'Database error');
		} else {
			$this->output->set_status_header(201)
						->set_header('Location: /services/polls/' . $newPollId);
		}
	}
	
	/**
	 * Retrieve JSON data for a single poll. (GET polls/[:id])
	 * @param int $id ID of the poll to get
	 * @return JSON data for a single poll
	 */
	public function getPoll ($id) {
		$poll = $this->poll->read($id);
		if (is_null($poll)) {
			$this->output->set_status_header(404);
		} else {
			$this->output->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($poll));
		}
	}
	
	/**
	 * Remove a poll and all of its associated votes. (DELETE polls/[:id])
	 * @param int $id ID of the poll to delete
	 */
	public function deletePoll ($id) {
		$deleted = $this->poll->delete($id);
		if (!$deleted) {
			$this->output->set_status_header(404);
		} else {
			$this->output->set_status_header(200);
		}
	}
}
