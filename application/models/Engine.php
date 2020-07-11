<?php
/**
 * 
 */
class Engine extends CI_Model
{

	public function get_all($order=NULL)
	{
		if($order!==NULL){
			$this->db->order_by($order);
		}
		return $this->db->get("engine_operator");
	}

	public function get_select($select="*", $order=NULL, $group=NULL, $limit=NULL)
	{
		$this->db->select($select);
		$this->db->from("engine_operator");
		if($group!==NULL){
			$this->db->group_by($group);
		}
		if($order!==NULL){
			$this->db->order_by($order);
		}
		if($limit!==NULL){
			$this->db->limit($limit);
		}
		return $this->db->get();
	}

	public function get_where($select="*", $where, $order=NULL, $group=NULL, $limit=NULL)
	{
		$this->db->select($select);
		$this->db->from("engine_operator");
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

	public function getDataEngineActivityByFilter($filter)
	{
		$this->db->select("`a`.`jam_sekarang`, `a`.`no_unit`, 
							case when (a.activity_time=TIME('00:00:00')) 
							then '' 
							WHEN (a.activity_time!=TIME('00:00:00')) 
							then a.activity_time 
							end as durasi, 
							a.`keterangan` , a.activity_now, a.status_now, a.segmen");
		$this->db->from("engine_operator a");
		$this->db->join("status s", "a.activity_now = s.id", "LEFT");
		$this->db->where($filter);
		// $this->db->group_by("a.activity_now");
		$this->db->order_by("a.id", "DESC");
		return $this->db->get();
	}
}