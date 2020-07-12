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
		$this->db->select("`id`, `kode`, `kategori`, `aktivitas`, `tampil`");
		$where = "kategori = 'AKTIVITAS' AND kode <> '001' and kode <> '002'";
		$this->db->where($where);
		$this->db->from('status');
		$this->db->order_by('tampil ASC, kode ASC');

		return $this->db->get();
	}

	public function getAllStatus()
	{
		$this->db->where('kategori', 'STATUS');
		$this->db->from('status');
		$this->db->order_by('aktivitas', 'ASC');
		return $this->db->get();
	}

	public function update($by, $data)
	{
		$this->db->where($by);
		return $this->db->update("activity_operator", $data);
	}
}