<?php
/**
 * 
 */
class OperatorModel extends CI_Model
{
	public function createActivity($data)
	{
		return $this->db->insert("activity_operator", $data);
	}

	public function getBy($filter)
	{
		$this->db->where($filter);
		$this->db->from('activity_operator a');
		$this->db->join('app_user b', 'a.created_by = b.UID');
		return $this->db->get();
	}

	public function create_engine_timeline($data)
	{
		$this->db->insert('engine_operator', $data);
		return $this->db->affected_rows();
	}
}