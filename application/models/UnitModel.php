<?php
/**
 * 
 */
class UnitModel extends CI_Model
{

	public function getAll($order=NULL)
	{
		if($order!==NULL){
			$this->db->order_by($order);
		}
		return $this->db->get('unit');
	}


	public function get_where($where, $order=NULL, $limit=NULL)
	{
		$this->db->where($where);
		if($order!==NULL){
			$this->db->order_by($order);
		}
		if($limit!==NULL){
			$this->db->limit($limit);
		}
		return $this->db->get("unit");
	}

	public function getUnitByFilter($filter)
	{
		$this->db->where($filter);
		$this->db->from('activity_operator');
		$this->db->order_by('created_date', 'DESC');
		return $this->db->get();
	}

	public function getUnitByFilterV2($filter, $group_by=null, $order=null)
	{
		$this->db->where($filter);
		$this->db->from("engine_operator a");
		$this->db->join("activity_operator b", "a.id_activity_operator = b.id");
		$this->db->select("b.no_unit, time(a.created_date) as jam, muatan");
		if($group_by!==null){
			$this->db->group_by($group_by);	
		}
		if($order!==null){
			$this->db->order_by($order);
		}else{
			$this->db->order_by("b.no_unit", "ASC");
		}
		return $this->db->get();
	}

	public function getHourByUnit($filter)
	{
		$this->db->where($filter);
		$this->db->from("engine_operator");
		$this->db->group_by("HOUR(created_date)");
		$this->db->select("HOUR(created_date) as jam, DATE(created_date) as tanggal, no_unit");
		return $this->db->get();
	}

	public function getMuatanOnlyByFilter($filter)
	{
		$this->db->select("muatan, created_date, no_unit");
		$this->db->where($filter);
		$this->db->order_by("id", "DESC");
		$this->db->from("engine_operator");
		return $this->db->get();
	}

	// public function getHourByFilter($filter)
	// {
	// 	$this->db->where($filter);
	// 	$this->db->from("engine_operator a");
	// 	$this->db->join("activity_operator b", "a.id_activity_operator = b.id");
	// 	$this->db->select("b.no_unit");
	// 	$this->db->group_by("b.no_unit");
	// 	$this->db->order_by("b.no_unit", "ASC");
	// 	return $this->db->get();
	// }

	public function getUnitByFilterV3($filter, $order=null, $group_by=null)
	{
		$this->db->from("engine_operator a");
		$this->db->join("activity_operator b", "a.id_activity_operator = b.id");
		$this->db->select("b.no_unit, a.jam_sekarang, a.activity_time");
		$this->db->join("status s", "a.activity_now = s.id", "LEFT");
		$this->db->where($filter);
		if($group_by!==null){
			$this->db->group_by($group_by);
		}
		if($order!==null){
			$this->db->order_by($order);
		}
		return $this->db->get();
	}

	public function getByFilter($filter)
	{
		$this->db->where($filter);
		$this->db->select('a.*, (select `sActiv`.`activity` as `activity_now_name` from `status` `sActiv` WHERE `a`.`activity_now` = `sActiv`.`id`) as activity_now_name, (select `sStatus`.`activity` as `status_now_name` from `status` `sStatus` where `a`.`status_now` = `sStatus`.`id`) as status_now_name, (SELECT `user`.`nama` from `detail_user` `user` WHERE `a`.`created_by` = `user`.`UID`) as nama, c.validation_status');
		$this->db->from('engine_operator a');
		$this->db->join('activity_operator b', 'a.id_activity_operator = b.id');
		$this->db->join('validation_data c', 'b.id = c.id_activity_operator', 'LEFT');
		$this->db->order_by('a.id', 'DESC');
		$this->db->limit(1);
		return $this->db->get();
	}

	public function getDateDataEngine()
	{
		return $this->db->query("SELECT cast(created_date as date) as tanggal, created_date from activity_operator WHERE created_date < CURDATE() GROUP BY tanggal ORDER BY tanggal desc");
	}

	public function getDateDataEngineV2()
	{
		return $this->db->query("SELECT date(created_date) as tanggal from engine_operator group by tanggal order by created_date DESC");
	}

	public function getTimeDataEngine($filter)
	{
		return $this->db->query("SELECT cast(created_date as time) as jam, created_date, CURDATE() from activity_operator WHERE $filter GROUP BY hour(jam) ORDER BY id desc");
	}

	public function getHourengine($filter)
	{
		return $this->db->query("SELECT hour(jam_sekarang), minute(jam_sekarang), substring(time(concat_ws(\":\", hour(jam_sekarang),\"00\")), 1,5) as timeformat from engine_operator WHERE $filter");
	}

	public function engineUnitByDate($filter)
	{
		return $this->db->query("SELECT * FROM activity_operator a WHERE $filter GROUP BY a.no_unit ORDER BY a.no_unit ASC");
	}

	public function getDataUnitEngineByFilter($filter, $type=null)
	{
		$this->db->select("*, (SELECT aktivitas FROM status WHERE id = activity_now) AS act,
					(SELECT nama FROM detail_user WHERE id = created_by)AS nama,
					(SELECT jenis FROM material WHERE material = kode_material) AS jenis_material, 
					(SELECT metode FROM metode WHERE metode = id) AS jenis_metode");
		$this->db->from("engine_operator");
		$this->db->where($filter);
		$this->db->order_by("id", "DESC");
		$this->db->limit(1);
		return $this->db->get();
		// return $this->db->query("SELECT * FROM engine_operator a
		// 						WHERE $filter
		// 						ORDER BY a.id DESC");
	}

	public function getDataByNoUnit($filter)
	{
		$this->db->select('a.*, b.no_unit, s.aktivitas as status, a.validation');
		$this->db->from('engine_operator a');
		$this->db->join("activity_operator b", "b.id = a.id_activity_operator AND date(b.created_date) = date(a.created_date)");
		$this->db->join("status s", "a.status_now = s.id", "LEFT");
		$this->db->where($filter);
		$this->db->order_by("a.id", "DESC");
		return $this->db->get();
	}

	public function getDataByFilter($filter, $group=null)
	{
		$this->db->select('a.*, b.no_unit, s.aktivitas as status, a.validation');
		$this->db->from('engine_operator a');
		$this->db->join("activity_operator b", "b.id = a.id_activity_operator AND date(b.created_date) = date(a.created_date)");
		$this->db->join("status s", "a.status_now = s.id", "LEFT");
		$this->db->where($filter);
		if($group!==null){
			$this->db->group_by($group);	
		}
		$this->db->order_by("a.id", "DESC");
		return $this->db->get();
	}

	public function getDataByNoUnitV2($filter)
	{
		$this->db->select("`a`.`jam_sekarang`, `b`.`no_unit`, 
							case when (a.activity_time=TIME('00:00:00')) 
							then '' 
							WHEN (a.activity_time!=TIME('00:00:00')) 
							then a.activity_time 
							end as durasi, 
							a.`keterangan` , a.activity_now, a.status_now, a.segmen");
		$this->db->from("engine_operator a");
		$this->db->join("activity_operator b", "b.id = a.id_activity_operator AND date(b.created_date) = date(a.created_date)");
		$this->db->join("status s", "a.activity_now = s.id", "LEFT");
		$this->db->where($filter);
		// $this->db->group_by("a.activity_now");
		$this->db->order_by("a.id", "DESC");
		return $this->db->get();
	}

	public function getMuatanByFilter($filter, $order=null)
	{
		$this->db->select("(a.muatan) as production, b.no_unit, date(a.created_date) AS tanggal, hour(a.created_date) as jam");
		$this->db->where($filter);
		$this->db->from("engine_operator a");
		$this->db->join("activity_operator b", "a.id_activity_operator = b.id");
		if($order!==null){
			$this->db->order_by($order);
		}
		$this->db->order_by("a.created_date", "ASC");
		return $this->db->get();
	}

	public function getDataToExcelAllInput($filter)
	{
		$this->db->where($filter);
		$this->db->select('date(a.created_date) as tanggal, a.jam_sekarang as waktu, a.no_unit, (select nama from detail_user where detail_user.id = a.created_by) as operator, b.nrp as nrp, b.shift, b.days_of, a.segmen, a.muatan, b.hm_start, b.hm_stop, 
						CASE 
						WHEN (a.segmen=\'Aktifitas\') THEN (SELECT aktivitas FROM status WHERE id = a.activity_now)
						WHEN (a.segmen=\'Metode\') THEN (SELECT metode FROM metode where id = a.metode)
						ELSE a.keterangan
						END AS keterangan,
						a.jam_engine as timer_start,
						time((a.jam_engine- a.activity_time) + a.jam_engine) as timer_stop,
						time(a.jam_engine- a.activity_time) as durasi, a.ritase_sekarang, a.ritase_sebelum, a.activity_time, a.status_time, a.all_productivity_unit, a.activity_productivity_unit, a.effectivness, a.validation, a.metode, a.activity_now, a.material');
		$this->db->from('engine_operator a');
		$this->db->join('activity_operator b', 'a.id_activity_operator = b.id');
		$this->db->order_by("operator", 'ASC');
		$this->db->order_by("waktu", 'ASC');
		$this->db->order_by('a.id', 'ASC');
		return $this->db->get();
	}

	public function customeGetPerHour($select = "", $where, $group_by="", $order_by = "")
	{
		return $this->db->query("SELECT HOUR(a.jam_sekarang) as jam, DATE(a.created_date) AS tanggal " . $select . " 
								FROM engine_operator a 
								WHERE " . $where . " 
								" . $group_by . " 
								" . $order_by);
	}

	public function customeGetDataWithJoin($select = "", $join="", $where, $group_by="", $order_by = "", $limit=null)
	{
		return $this->db->query("SELECT HOUR(a.jam_sekarang) as jam, a.no_unit, DATE(a.created_date) AS tanggal " . $select . " 
								FROM engine_operator a 
								$join
								WHERE " . $where . " 
								" . $group_by . " 
								" . $order_by . $limit);
	}

	public function getDataToExcelHourly($filter)
	{
		$this->db->where($filter);
		$this->db->select('HOUR(a.jam_sekarang) as jam, a.no_unit, DATE(a.created_date) AS tanggal');
		$this->db->from('engine_operator a');
		$this->db->join('activity_operator b', 'a.id_activity_operator = b.id');
		$this->db->group_by(['a.no_unit', 'jam']);
		$this->db->order_by("jam", 'ASC');
		return $this->db->get();
	}
}