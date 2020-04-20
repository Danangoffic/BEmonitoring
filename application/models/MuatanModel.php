<?php
/**
 * 
 */
class MuatanModel extends CI_Model
{
	public function getAll()
	{
		return $this->db->get('muatan');
	}

	public function getByKode($kode)
	{
		$this->db->where('kode', $kode);
		return $this->db->get('muatan');
	}
}