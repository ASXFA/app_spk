<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_bobot_saw extends CI_Model
{
    var $table = 'bobot_saw';
    var $select_column = array('id_bobot_saw', 'nilai_bobot_saw', 'keterangan_bobot_saw', 'id_kriteria', 'nama_kriteria');
    var $order_column = array(null, 'id_bobot_saw', 'nilai_bobot_saw', 'keterangan_bobot_saw', 'id_kriteria', 'nama_kriteria', null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('kriteria', 'kriteria.id_kriteria = bobot_saw.id_kriteria_bobot_saw');
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('nama_kriteria', $_POST['search']['value']);
            $this->db->or_like('nilai_bobot_saw', $_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_bobot_saw', 'ASC');
        }
    }

    public function make_datatables()
    {
        $this->make_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_all_data()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function getAll()
    {
        return $this->db->get($this->table)->result();
    }

    public function getAllKriteria()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('kriteria', 'kriteria.id_kriteria = bobot_saw.id_kriteria_bobot_saw');
        $query = $this->db->get();
        return $query->result();
    }

    public function getById($id)
    {
        $this->db->where('id_bobot_saw', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahBobot($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    public function editBobot($id, $data)
    {
        $this->db->where('id_bobot_saw', $id);
        $query = $this->db->update($this->table, $data);
        return $query;
    }

    public function deleteBobot($id)
    {
        $this->db->where('id_bobot_saw', $id);
        $query = $this->db->delete($this->table);
        return $query;
    }
}
