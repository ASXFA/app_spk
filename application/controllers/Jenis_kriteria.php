<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis_kriteria extends CI_Controller
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
        $this->load->model('model_jenis_kriteria');
    }

    public function listJenis()
    {
        $this->content['page'] = 'Jenis Kriteria';
        $this->content['allData'] = $this->model_jenis_kriteria->get_all_data();
        $this->twig->display('jenis_kriteria.html', $this->content);
    }

    public function jenisLists()
    {
        $jenis = $this->model_jenis_kriteria->make_datatables();
        $data = array();
        if (!empty($jenis)) {
            $no = 1;
            foreach ($jenis as $row) {
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nama_jenis;
                $sub_data[] = $row->nilai_jenis;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editJenis' id='" . $row->id_jenis . "' title='Edit Jenis'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteJenis' id='" . $row->id_jenis . "' title='Delete Jenis'><i class='fa fa-trash'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_jenis_kriteria->get_all_data(),
            'recordsFiltered' => $this->model_jenis_kriteria->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function jenisById()
    {
        $id = $this->input->post('id_jenis');
        $jenis = $this->model_jenis_kriteria->getById($id);
        $output = array(
            'nama_jenis' => $jenis->nama_jenis,
            'nilai_jenis' => $jenis->nilai_jenis
        );
        echo json_encode($output);
    }

    public function doJenis()
    {
        $operation = $this->input->post('operation');
        $output = array();
        if ($operation == "Tambah") {
            $data = array(
                'nama_jenis' => $this->input->post('nama_jenis'),
                'nilai_jenis' => $this->input->post('nilai_jenis')
            );
            $process = $this->model_jenis_kriteria->tambahJenis($data);
            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Menambahkan data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Menambahkan data gagal !';
            }
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_jenis');
            $data = array(
                'nama_jenis' => $this->input->post('nama_jenis'),
                'nilai_jenis' => $this->input->post('nilai_jenis')
            );
            $process = $this->model_jenis_kriteria->editJenis($id, $data);
            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Edit data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Edit data gagal !';
            }
        }
        echo json_encode($output);
    }

    public function deleteJenis()
    {
        $id = $this->input->post('id_jenis');
        $process = $this->model_jenis_kriteria->deleteJenis($id);
        echo json_encode($process);
    }
}
