<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_temp_saw extends CI_Model
{
    var $table = 'temp_saw';

    public function getAll()
    {
        $this->db->order_by('id_kriteria_temp_saw', 'ASC');
        $q = $this->db->get($this->table);
        return $q->result();
    }

    public function getById($id)
    {
        $this->db->where('id_bobot_saw', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getNilaiTertinggi($id)
    {
        // $q = $this->db->select('max(nilai_temp_saw) as tinggi')->where('id_kriteria_temp_saw', $id)->get());
        // $q = $this->db->select("SELECT MAX(nilai_temp_saw) as tinggi WHERE id_kriteria_saw = $id ");
        $this->db->select_max('nilai_temp_saw');
        $this->db->where('id_kriteria_temp_saw', $id);
        $q = $this->db->get($this->table);
        return $q->row();
    }
    public function getNilaiTerendah($id)
    {
        // $q = $this->db->select('max(nilai_temp_saw) as tinggi')->where('id_kriteria_temp_saw', $id)->get());
        // $q = $this->db->select("SELECT MAX(nilai_temp_saw) as tinggi WHERE id_kriteria_saw = $id ");
        $this->db->select_min('nilai_temp_saw');
        $this->db->where('id_kriteria_temp_saw', $id);
        $q = $this->db->get($this->table);
        return $q->row();
    }

    public function tambahTemp($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    public function deleteTemp()
    {
        $this->db->empty_table($this->table);
    }
}
