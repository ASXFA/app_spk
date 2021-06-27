<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_subkriteria extends CI_Model
{
    var $table = 'subkriteria';
    var $select_column = array('id_subkriteria', 'id_kriteria_sub', 'nama_kriteria', 'nama_subkriteria', 'nilai_subkriteria');
    var $order_column = array(null, 'nama_subkriteria', 'nama_kriteria', 'nilai_subkriteria', null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('kriteria', 'kriteria.id_kriteria = subkriteria.id_kriteria_sub', 'left');
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('nama_subkriteria', $_POST['search']['value']);
            $this->db->or_like('nama_kriteria', $_POST['search']['value']);
            $this->db->or_like('nilai_subkriteria', $_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_kriteria_sub', 'ASC');
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

    public function getById($id)
    {
        $this->db->where('id_subkriteria', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahsubkriteria($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    public function editsubkriteria($id, $data)
    {
        $this->db->where('id_subkriteria', $id);
        $query = $this->db->update($this->table, $data);
        return $query;
    }

    public function deletesubkriteria($id)
    {
        $this->db->where('id_subkriteria', $id);
        $query = $this->db->delete($this->table);
        return $query;
    }

    public function getNilaiById($id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('id_subkriteria', $id);
        return $this->db->get()->row();
    }
}
