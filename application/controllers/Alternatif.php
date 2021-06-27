<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alternatif extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect(base_url());
        }
        $this->id = $this->session->userdata('id');
        $this->load->model('model_users');
        $user = $this->model_users->getById($this->id);
        $this->content = array(
            'base_url' => base_url(),
            'id_user_login' => $user->id_users,
            'nama_user_login' => $user->nama_users,
            'uname_user_login' => $user->uname_users,
            'role_user_login' => $user->role_users
        );
        $this->load->model('model_alternatif');
    }

    public function listAlternatif($id)
    {
        $this->load->model('model_santri');
        $this->load->model('model_kriteria');
        $this->load->model('model_subkriteria');
        $kriteria = $this->model_kriteria->getAll();
        $subkriteria = $this->model_subkriteria->getAll();
        $alternatif = $this->model_alternatif->getBySantri($id);
        $santri = $this->model_santri->getById($id);
        $this->content['alternatif'] = $alternatif;
        $this->content['kriteria'] = $kriteria;
        $this->content['subkriteria'] = $subkriteria;
        $this->content['santri'] = $santri;
        $this->content['page'] = 'Data Alternatif Pada Santri Bernama ' . $santri->nama_santri;
        $this->twig->display('alternatif.html', $this->content);
    }

    // public function alternatifById()
    // {
    //     $id = $this->input->post('id_alternatif');
    //     $alternatif = $this->model_alternatif->getById($id);
    //     $output = array(

    //     );
    //     echo json_encode($output);
    // }

    public function doAlternatif()
    {
        $operation = $this->input->post('operations');
        $id_santri = $this->input->post('id_santri_alternatif');
        $id_kriteria = $this->input->post('id_kriteria_alternatif');
        $id_subkriteria = $this->input->post('id_subkriteria_alternatif');
        $count = count($id_kriteria);
        $output = array();
        if ($operation == "Tambah") {
            for ($i = 0; $i < $count; $i++) {
                $data = array(
                    'id_santri_alternatif' => $id_santri,
                    'id_kriteria_alternatif' => $id_kriteria[$i],
                    'id_subkriteria_alternatif' => $id_subkriteria[$i]
                );
                $process = $this->model_alternatif->tambahAlternatif($data);
            }
        } else if ($operation == "Edit") {
            $this->model_alternatif->deleteAlternatif($id_santri);
            for ($i = 0; $i < $count; $i++) {
                $data = array(
                    'id_santri_alternatif' => $id_santri,
                    'id_kriteria_alternatif' => $id_kriteria[$i],
                    'id_subkriteria_alternatif' => $id_subkriteria[$i]
                );
            }
        }
        $output['cond'] = '1';
        $output['msg'] = "Berhasil";
        echo json_encode($output);
    }

    // public function deleteKriteria()
    // {
    //     $id = $this->input->post('id_kriteria');
    //     $process = $this->model_kriteria->deleteKriteria($id);
    //     echo json_encode($process);
    // }

}
