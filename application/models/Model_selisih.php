<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_selisih extends CI_Model
{
    var $table = 'selisih';
    var $select_column = array('id_selisih', 'nilai_selisih', 'bobot_selisih', 'keterangan_selisih');
    var $order_column = array(null, 'nilai_selisih', 'bobot_jenis', 'keterangan_selisih', null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('nilai_selisih', $_POST['search']['value']);
            $this->db->or_like('bobot_selisih', $_POST['search']['value']);
            $this->db->or_like('keterangan_selisih', $_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id_selisih', 'ASC');
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
        $this->db->where('id_selisih', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function getByNilai($nilai)
    {
        $this->db->where('nilai_selisih', $nilai);
        return $this->db->get($this->table)->row();
    }

    public function tambahSelisih($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    public function editSelisih($id, $data)
    {
        $this->db->where('id_selisih', $id);
        $query = $this->db->update($this->table, $data);
        return $query;
    }

    public function deleteSelisih($id)
    {
        $this->db->where('id_selisih', $id);
        $query = $this->db->delete($this->table);
        return $query;
    }
}
