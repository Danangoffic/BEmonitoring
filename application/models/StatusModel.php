<?php
/**
 * 
 */
class StatusModel extends CI_Model
{
	public function getAll()
	{
		
	}

	public function get_where($select="*", $where, $order=NULL, $group=NULL, $limit=NULL)
	{
		$this->db->select($select);
		$this->db->from("status");
		$this->db->where($where);
		if($order!==NULL){
			$this->db->order_by($order);
		}
		if($group!==NULL){
			$this->db->group_by($group);
		}
		if($limit!==NULL){
			$this->db->limit($limit);
		}
		return $this->db->get();
	}
}