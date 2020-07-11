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

	public function getAllOperator()
	{
		$this->db->order_by("nama", "ASC");
		return $this->db->get_where("detail_user", "divisi = 5");
	}

	public function cekUnit($nounit)
	{
		return $this->db->get_where("unit", "kode = '$nounit'", 1);
	}

	public function getBy($filter)
	{
		$this->db->select('a.id, a.no_unit, a.nrp, a.shift, a.days_of, a.hm_start, a.hm_stop, a.created_by, a.created_date, a.validation');
		$this->db->where($filter);
		$this->db->from('activity_operator a');
		$this->db->join('detail_user b', 'a.created_by = b.id');
		return $this->db->get();
	}

	public function create_engine_timeline($data)
	{
		$this->db->insert('engine_operator', $data);
		return $this->db->affected_rows();
	}
}