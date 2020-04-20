<?php
/**
 * 
 */
class UnitModel extends CI_Model
{
	public function getAll()
	{
		return $this->db->get('unit');
	}

	public function getByFilter($filter)
	{
		$this->db->where($filter);
		$this->db->select('op.no_unit, a.*, sActiv.activity as activity_now_name, sStatus.activity as status_now_name, user.nama');
		$this->db->from('activity_operator op');
		$this->db->join('engine_operator a', 'a.id_activity_operator = op.id', 'LEFT');
		$this->db->join('status sActiv', 'a.activity_now = sActiv.id');
		$this->db->join('status sStatus', 'a.status_now = sStatus.id');
		$this->db->join('detail_user user', 'a.created_by = user.UID');
		$this->db->order_by('op.id', 'DESC');
		return $this->db->get();
	}
}