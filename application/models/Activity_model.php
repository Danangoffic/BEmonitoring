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
		$this->db->order_by('tampil DESC, kode ASC');

		return $this->db->get();
	}

	public function getAllStatus()
	{
		$this->db->where('kategori', 'STATUS');
		$this->db->from('status');
		$this->db->order_by('aktivitas', 'ASC');
		return $this->db->get();
	}

	public function update($updated_by="", $data=array())
	{
		$this->db->where($updated_by);
		return $this->db->update("activity_operator", $data);
	}

	public function insert_data_engine()
	{
		$data_input = $this->input->post('data');
		$totalDataInput = count($data_input);
		$array_insert = [];
		for ($i=0; $i < $totalDataInput; $i++) { 
			$material = $data_input[$i]['material'];
			
			$metode = $data_input[$i]['metode'];
			$aktivitas = $data_input[$i]['aktivitas'];
			// $aktivitas = strval($aktivitas);
			$id_aktivitas = 0;
			if(!empty($aktivitas)||$aktivitas!==""||$aktivitas!==NULL||$aktivitas!==0||$aktivitas!=="null"||$aktivitas!=="NULL"){
				$selectAct = "id";
				$whereAct = "kode = '$aktivitas'";
				$data_aktivitas = $this->Status->get_where($selectAct, $whereAct, NULL, NULL, 1);
				if($data_aktivitas->num_rows() > 0){
					$data_aktivitas = $data_aktivitas->row();
					$id_aktivitas = $data_aktivitas->id;
				}
			}

			$muatan = $data_input[$i]['muatan'];
			$status =$data_input[$i]['status'];
			// $status = strval($status);
			$id_status = 0;
			if(!empty($status) || $status!=="" || $status!==NULL||$status!==0||$status!=="null"||$status!=="NULL"){
				$selectStatus = "id";
				$whereStatus = "kode = '$status'";
				$data_status = $this->Status->get_where($selectStatus, $whereStatus, NULL, NULL, 1);
				// echo $this->db->last_query();
				if($data_status->num_rows() > 0){
					$data_status = $data_status->row();
					$id_status = $data_status->id;
				}
			}
			
			$status_engine = $data_input[$i]['statusEngine'];
			$jam_engine = $data_input[$i]['runningTimer'];
			$jam_sekarang  = $data_input[$i]['jam_sekarang'];
			$activity_time = $data_input[$i]['actTimer'];
			$status_time = $data_input[$i]['statTimer'];
			$created_by = $data_input[$i]['createdBy'];
			$all_productivity_unit = $data_input[$i]['all_productivity_unit'];
			$activity_productivity_unit = $data_input[$i]['activity_productivity_unit'];
			$effectivness = $data_input[$i]['effectivness'];
			$ritase_sebelum = $data_input[$i]['ritase_sebelum'];
			$ritase_sekarang = $data_input[$i]['ritase_sekarang'];
			$id_activity_operator = $data_input[$i]['id_activity_operator'];
			$segmen = $data_input[$i]['segmen'];
			$actual_prod = $data_input[$i]['actual_prod'];
			$keterangan = $data_input[$i]['keterangan'];
			$unit = $data_input[$i]['unit'];
			$shift = $data_input[$i]['shift'];
			$ACCUMULATIVETIMERPERHOUR = $data_input[$i]['ACCUMULATIVETIMERPERHOUR'];
			$arraynya = array('id_activity_operator' => $id_activity_operator, 
								'status_engine' => $status_engine, 
								'activity_now' => $id_aktivitas, 
								'status_now' => $id_status, 
								'activity_time' => $activity_time, 
								'status_time' => $status_time, 
								'all_productivity_unit' => $all_productivity_unit,
								'activity_productivity_unit' => $activity_productivity_unit, 
								'effectivness' => $effectivness, 
								'metode' => $metode, 
								'muatan' => $muatan,
								'actual_prod' => $actual_prod,
								'ritase_sebelum' => $ritase_sebelum, 
								'ritase_sekarang' => $ritase_sekarang, 
								'jam_engine' => $jam_engine, 
								'jam_sekarang' => $jam_sekarang, 
								'created_date' => date('Y-m-d H:i:s'), 
								'created_by' => $created_by,
								'segmen' => $segmen,
								'keterangan' => $keterangan,
								'no_unit' => $unit,
								'shift' => $shift,
								'material' => $material,
								'akumulatif_loading_time_per_hour' => $ACCUMULATIVETIMERPERHOUR);
			array_push($array_insert, $arraynya);
		}
		$insert = $this->db->insert_batch("engine_operator", $array_insert);
		if($insert){
			if(count($data_input) > 1){
				Pusher($data_input[0]);
			}else{
				Pusher($data_input);
			}
			$data_response = array('request' => $array_insert, 'affected' => $insert, 'response' => 'success');
			return $data_response;
		}else{
			$data_response = array('request' => $array_insert, 'response' => 'failed');
			return $data_response;
		}
	}
}