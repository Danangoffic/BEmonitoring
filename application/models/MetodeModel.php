<?php
/**
 * 
 */
class MetodeModel extends CI_Model
{
	public function getAll()
	{
		return $this->db->get('metode');
	}

	public function getById($id)
	{
		$this->db->get('id', $id);
		return $this->db->get('metode');
	}
}