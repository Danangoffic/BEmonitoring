<?php
/**
 * 
 */
class Activity_model extends CI_Model
{
	public function getAll()
	{
		return $this->db->get('status');
	}

	public function getByKode($kode)
	{
		return $this->db->get_where('status', array('kode' => $kode));
	}

	public function getAllAktivitas()
	{
		$NoLoading = "NO LOADING";
		$this->db->where_in('status', $NoLoading);
		$this->db->from('status');
		$this->db->order_by('activity', 'ASC');
		$this->db->limit(29);
		return $this->db->get();
	}

	public function getAllStatus()
	{
		$this->db->where('status', 'STATUS');
		$this->db->from('status');
		$this->db->order_by('activity', 'ASC');
		$this->db->limit(29);
		return $this->db->get();
	}
}