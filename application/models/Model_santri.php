<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_santri extends CI_Model
{
    var $table = 'santri';
    var $select_column = array('id_santri', 'nama_santri', 'jk_santri', 'ttl_santri', 'kelas_santri', 'created_by');
    var $order_column = array(null, 'id_santri', 'nama_santri', 'jk_santri', 'ttl_santri', 'kelas_santri', 'created_by', null);

    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
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
            $this->db->order_by('id_santri', 'DESC');
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
        return $this->db->get($this->table);
    }

    public function getById($id)
    {
        $this->db->where('id_santri', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function tambahSantri($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function editSantri($id, $data)
    {
        $this->db->where('id_santri', $id);
        $query = $this->db->update($this->table, $data);
        return $query;
    }

    public function deleteSantri($id)
    {
        $this->db->where('id_santri', $id);
        $query = $this->db->delete($this->table);
        return $query;
    }
}
