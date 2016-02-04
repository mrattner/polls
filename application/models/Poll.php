<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Represents a poll record in the database.
 */
class Poll extends MY_Model {
	public $id;
	public $title;
	public $question;
	
	/**
	 * Get an array of Poll objects read from all polls in the database, in
	 * alphabetical order by title. These poll objects will not have an 
	 * Answers attribute.
	 * @return Array of all polls, or an empty array if there are no polls
	 */
	public function readAll () {
		$query = $this->db->from('Polls')
						->order_by('Title', 'ASC')
						->get();
		$list = array();
		if ($query->num_rows() > 0) {
			$rows = $query->result();
			MY_Model::checkResult($rows);

			foreach ($rows as $row) {
				$poll = new Poll();
				$poll->load($row);
				$list []= $poll;
			}
		}
		return $list;
	}
	
	/**
	 * Get a Poll object read from the database.
	 * @param int $id ID of the poll to read
	 * @return a Poll instance, or NULL if no poll with the specified ID is 
	 * found
	 */
	public function read ($id) {
		$poll = new Poll();
		
		// First, get the poll
		$pollQuery = $this->db->get_where('Polls', array('id' => $id));
		if ($pollQuery->num_rows() !== 1){
			return NULL;
		}
		$rows = $pollQuery->result();
		MY_Model::checkResult($rows);
		$poll->load($rows[0]);
		
		// Then, get the poll's options
		$poll->answers = array();
		$this->db->select('Answer as answer, AnswerText as answerText')
				->from('Options')
				->where('PollId', $id);
		$optionsQuery = $this->db->get();
		$options = $optionsQuery->result();
		MY_MODEL::checkResult($options);
		foreach ($options as $option) {
			$poll->answers[$option->answer] = $option->answerText;
		}
		
		return $poll;
	}
	
	/**
	 * Create a new poll and add to the database.
	 * @param string $title Short title of poll
	 * @param string $question Question asked by poll
	 * @param array[string] $answers Possible options to vote on (at least 2)
	 * @return ID of new poll, or false if creation failed
	 */
	public function create ($title, $question, $answers) {
		if (!isset($answers) || count($answers) < 2) {
			return false;
		}
		
		$this->db->trans_start();
		// First, create the poll
		$this->db->insert('Polls', array(
			'Title' => $title,
			'Question' => $question
		));
		$pollId = $this->db->insert_id();
		
		// Then, create its answers
		$options = array();
		foreach ($answers as $index => $answer) {
			$options []= array(
				'PollId' => $pollId,
				'Answer' => $index,
				'AnswerText' => $answer
			);
		}
		$this->db->insert_batch('Options', $options);
		$success = $this->db->affected_rows() > 0;
		$this->db->trans_complete();
		
		return $success ? $pollId : false;
	}
	
	/**
	 * Delete a poll from the database. Also deletes its associated options and
	 * votes.
	 * @param int $id ID of the poll to delete
	 * @return true if delete was successful
	 */
	public function delete ($id) {
		$this->db->trans_start();
		
		$this->db->delete('Votes', array('PollId' => $id));
		$this->db->delete('Options', array('PollId' => $id));
		$this->db->delete('Polls', array('id' => $id));
		
		$success = $this->db->affected_rows() > 0;
		$this->db->trans_complete();
		
		return $success;
	}
}
