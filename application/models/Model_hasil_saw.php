<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_hasil_saw extends CI_Model
{
    var $table = 'hasil_saw';
    var $select_column = array('*');
    var $order_column = array(null, 'id_santri', 'nama_santri', 'jk_santri', 'ttl_santri', 'kelas_santri', 'total_hasil_saw', null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->join('santri', 'santri.id_santri = hasil_saw.id_santri_hasil_saw');
        if (isset($_POST['search']['value'])) {
            $this->db->or_like('nama_santri', $_POST['search']['value']);
            $this->db->or_like('ttl_santri', $_POST['search']['value']);
            $this->db->or_like('jk_santri', $_POST['search']['value']);
            $this->db->or_like('kelas_santri', $_POST['search']['value']);
            $this->db->or_like('created_by', $_POST['search']['value']);
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('total_hasil_saw', 'DESC');
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

    // public function getAll()
    // {
    //     $this->db->select('*');
    //     $this->db->from($this->table);
    //     $this->db->join('santri', 'santri.id_santri = hasil_saw.id_kriteria_hasil_saw');
    //     $this->db->order_by('total_hasil_saw', 'ASC');
    //     $q = $this->db->get();
    //     return $q->result();
    // }

    public function tambahHasil($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    public function deleteHasil()
    {
        $this->db->empty_table($this->table);
    }
}
