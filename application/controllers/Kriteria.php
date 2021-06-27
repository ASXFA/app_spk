<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kriteria extends CI_Controller
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
        $this->load->model('model_kriteria');
    }

    public function listKriteria()
    {
        $this->load->model('model_jenis_kriteria');
        $this->content['jenis'] = $this->model_jenis_kriteria->getAll();
        $this->content['page'] = 'Data Kriteria';
        $this->twig->display('kriteria.html', $this->content);
    }

    public function kriteriaLists()
    {
        $kriteria = $this->model_kriteria->make_datatables();
        $data = array();
        if (!empty($kriteria)) {
            $no = 1;
            foreach ($kriteria as $row) {
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nama_kriteria . " (K" . $no . ")";
                $sub_data[] = $row->nama_jenis;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editKriteria' id='" . $row->id_kriteria . "' title='Edit Kriteria'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteKriteria' id='" . $row->id_kriteria . "' title='Delete Kriteria'><i class='fa fa-trash'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_kriteria->get_all_data(),
            'recordsFiltered' => $this->model_kriteria->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function kriteriaById()
    {
        $id = $this->input->post('id_kriteria');
        $kriteria = $this->model_kriteria->getById($id);
        $output = array(
            'nama_kriteria' => $kriteria->nama_kriteria,
            'id_jenis' => $kriteria->id_jenis
        );
        echo json_encode($output);
    }

    public function doKriteria()
    {
        $this->load->model('model_bobot_saw');
        $operation = $this->input->post('operation');
        $output = array();
        if ($operation == "Tambah") {
            $data = array(
                'nama_kriteria' => $this->input->post('nama_kriteria'),
                'id_jenis' => $this->input->post('id_jenis')
            );
            $insert = $this->model_kriteria->tambahKriteria($data);
            $data2 = array(
                'id_kriteria_bobot_saw' => $insert
            );
            $process = $this->model_bobot_saw->tambahBobot($data2);
            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Menambahkan data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Menambahkan data gagal !';
            }
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_kriteria');
            $data = array(
                'nama_kriteria' => $this->input->post('nama_kriteria'),
                'id_jenis' => $this->input->post('id_jenis')
            );
            $process = $this->model_kriteria->editKriteria($id, $data);
            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Menambahkan data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Menambahkan data gagal !';
            }
        }
        echo json_encode($output);
    }

    public function deleteKriteria()
    {
        $this->load->model('model_bobot_saw');
        $id = $this->input->post('id_kriteria');
        $delete = $this->model_kriteria->deleteKriteria($id);
        $process = $this->model_bobot_saw->deleteBobot($id);
        echo json_encode($process);
    }
}
