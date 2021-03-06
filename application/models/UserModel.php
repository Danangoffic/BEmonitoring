<?php
/**
 * 
 */
class UserModel extends CI_Model
{
	public function checkUserValidity($username, $password)
	{
		$this->db->select("a.*, b.*, c.nama_divisi, REPLACE(REPLACE(REPLACE(TIMESTAMP(NOW()), '-', ''), ' ', ''), ':', '') as now");
		$this->db->limit(1);
		$this->db->where('a.UNAMA', $username);
		$this->db->where('a.UPASS', $password);
		$this->db->from('app_user a');
		$this->db->join('detail_user b', 'b.nrp = a.UNAMA');
		$this->db->join('divisi c', 'c.id = a.UDISTRIK');
		return $this->db->get();
	}
}