<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_hasil extends CI_Model
{
    var $table = 'hasil';

    public function tambahHasil($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    function deleteHasil()
    {
        $this->db->empty_table($this->table);
    }

    public function getAll()
    {
        $this->db->order_by('total', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function getAllSantri()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('santri', 'santri.id_santri = hasil.id_santri', 'left');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }
}
