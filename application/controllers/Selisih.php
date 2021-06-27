<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Selisih extends CI_Controller
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
        $this->load->model('model_selisih');
    }

    public function listSelisih()
    {
        // $this->load->model('model_jenis_selisih');
        // $this->content['jenis'] = $this->model_jenis_selisih->getAll();
        $this->content['page'] = 'Data Selisih';
        $this->twig->display('selisih.html', $this->content);
    }

    public function selisihLists()
    {
        $selisih = $this->model_selisih->make_datatables();
        $data = array();
        if (!empty($selisih)) {
            $no = $_POST['start'] + 1;
            foreach ($selisih as $row) {
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nilai_selisih;
                $sub_data[] = $row->bobot_selisih;
                $sub_data[] = $row->keterangan_selisih;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editSelisih' id='" . $row->id_selisih . "' title='Edit selisih'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteSelisih' id='" . $row->id_selisih . "' title='Delete selisih'><i class='fa fa-trash'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_selisih->get_all_data(),
            'recordsFiltered' => $this->model_selisih->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function selisihById()
    {
        $id = $this->input->post('id_selisih');
        $selisih = $this->model_selisih->getById($id);
        $output = array(
            'nilai_selisih' => $selisih->nilai_selisih,
            'bobot_selisih' => $selisih->bobot_selisih,
            'keterangan_selisih' => $selisih->keterangan_selisih
        );
        echo json_encode($output);
    }

    public function doSelisih()
    {
        $operation = $this->input->post('operation');
        if ($operation == "Tambah") {
            $data = array(
                'nilai_selisih' => $this->input->post('nilai_selisih'),
                'bobot_selisih' => $this->input->post('bobot_selisih'),
                'keterangan_selisih' => $this->input->post('keterangan_selisih')
            );
            $process = $this->model_selisih->tambahSelisih($data);
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_selisih');
            $data = array(
                'nilai_selisih' => $this->input->post('nilai_selisih'),
                'bobot_selisih' => $this->input->post('bobot_selisih'),
                'keterangan_selisih' => $this->input->post('keterangan_selisih')
            );
            $process = $this->model_selisih->editSelisih($id, $data);
        }
        echo json_encode($process);
    }

    public function deleteSelisih()
    {
        $id = $this->input->post('id_selisih');
        $process = $this->model_selisih->deleteSelisih($id);
        echo json_encode($process);
    }
}
