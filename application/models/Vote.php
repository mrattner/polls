<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Represents a vote record in the database.
 */
class Vote extends MY_Model {
	// These properties aren't used, but they describe the schema for a vote.
	public $id;
	public $address;
	public $pollId;
	public $answer;
	
	/**
	 * Get an array of vote counts for a particular poll in the database.
	 * @param int $pollId ID of the poll whose votes to read
	 * @return Associative array of option => voteCount, or NULL if poll ID was
	 *  not found
	 */
	public function count ($pollId) {
		/*
		 * This query was painstakingly created after much toil in SQL Fiddle...
		 * It will return all options for the specified poll and how many votes
		 * for each option. No rows are returned if the poll id is nonexistent.
		 * 
		 * SELECT `Options`.`Answer`, COUNT(`Votes`.`Answer`) AS NumVotes
		 * FROM `Options` 
		 * LEFT OUTER JOIN `Votes`
		 *	ON `Votes`.`Answer` = `Options`.`Answer`
		 *	AND `Votes`.`PollId`=`Options`.`PollId`
		 * WHERE `Options`.`PollId`=$pollId
		 * GROUP BY `Options`.`Answer`;
		 */
		$this->db
				->select('Options.Answer, COUNT(`Votes`.`Answer`) AS NumVotes')
				->from('Options')
				->join('Votes', 
					'Votes.Answer=Options.Answer AND Votes.PollId=Options.PollId',
					'left outer')
				->where('Options.PollId', $pollId)
				->group_by('Options.Answer');
		$query = $this->db->get();
		if ($query->num_rows() === 0) {
			// The specified poll ID doesn't exist
			return null;
		}
		$rows = $query->result();
		MY_Model::checkResult($rows);
		
		$counts = array();
		foreach ($rows as $row) {
			$counts[intval($row->Answer)] = intval($row->NumVotes);
		}
		return $counts;
	}
	
	/**
	 * Create a new vote and add to the database.
	 * @param string $ipAddress IP address of voter
	 * @param int $pollId ID of poll to vote on
	 * @param int $answer Which option to vote for
	 * @return true if creation of vote was successful
	 */
	public function create ($ipAddress, $pollId, $answer) {
		$this->db->insert('Votes', array(
			'Address' => $ipAddress,
			'PollId' => $pollId,
			'Answer' => $answer
		));
		return $this->db->affected_rows() === 1;
	}
	
	/**
	 * Delete all votes associated with a poll in the database.
	 * @param int $pollId ID of the poll whose votes to delete
	 * @return true if deleting all votes for the poll was successful
	 */
	public function deleteAll ($pollId) {
		$this->db->delete('Votes', array('PollId' => $pollId));
		return $this->db->affected_rows() > 0;
	}
}
