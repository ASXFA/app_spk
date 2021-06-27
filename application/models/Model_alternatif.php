<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_alternatif extends CI_Model
{
    var $table = 'alternatif';

    public function getAll()
    {
        // $this->db->order_by('id_alternatif', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function getByKriteria($id_kriteria)
    {
        $this->db->where('id_kriteria', $id_kriteria);
        return $this->db->get($this->table)->result();
    }

    public function getBySantri($id_santri)
    {
        $this->db->where('id_santri_alternatif', $id_santri);
        return $this->db->get($this->table)->result();
    }

    public function getBySantriDetail($id_santri)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('kriteria', 'kriteria.id_kriteria = alternatif.id_kriteria_alternatif', 'left');
        $this->db->join('subkriteria', 'subkriteria.id_subkriteria = alternatif.id_subkriteria_alternatif', 'left');
        $this->db->where('id_santri_alternatif', $id_santri);
        // $this->db->order_by('id_objek','ASC');
        return $this->db->get()->result();
    }

    public function tambahAlternatif($data)
    {
        $query = $this->db->insert($this->table, $data);
        return $query;
    }

    public function deleteAlternatif($id)
    {
        $this->db->where('id_santri_alternatif', $id);
        $this->db->delete($this->table);
    }

    // FUNGSI UNTUK PENILAIAN

    public function nilai_santri_alternatif($data)
    {
        $this->db->select('nilai_subkriteria');
        $this->db->from($this->table);
        $this->db->join('kriteria', 'kriteria.id_kriteria = alternatif.id_kriteria_alternatif');
        $this->db->join('subkriteria', 'subkriteria.id_subkriteria = alternatif.id_subkriteria_alternatif');
        $this->db->where($data);
        $this->db->get()->row();
    }

    public function getAllNilaiSubkriteria()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('subkriteria', 'subkriteria.id_subkriteria = alternatif.id_subkriteria_alternatif', 'left');
        $q = $this->db->get();
        return $q;
    }
}
