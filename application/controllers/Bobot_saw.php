<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bobot_saw extends CI_Controller
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
        $this->load->model('model_bobot_saw');
    }

    public function listBobot()
    {
        $this->content['page'] = 'Bobot SAW';
        $this->load->model('model_kriteria');
        $this->content['kriteria'] = $this->model_kriteria->getAll();
        $this->content['bobot'] = $this->model_bobot_saw->getAll();
        $this->twig->display('bobot_saw.html', $this->content);
    }

    public function bobotLists()
    {
        $bobot = $this->model_bobot_saw->make_datatables();
        $data = array();
        if (!empty($bobot)) {
            $no = 1;
            foreach ($bobot as $row) {
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nama_kriteria;
                $sub_data[] = $row->nilai_bobot_saw;
                $sub_data[] = $row->keterangan_bobot_saw;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editBobot' id='" . $row->id_bobot_saw . "' title='Edit Bobot Saw'><i class='fa fa-edit'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_bobot_saw->get_all_data(),
            'recordsFiltered' => $this->model_bobot_saw->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function bobotById()
    {
        $id = $this->input->post('id_bobot_saw');
        $bobot = $this->model_bobot_saw->getById($id);
        $output = array(
            'id_bobot_saw' => $bobot->id_bobot_saw,
            'nilai_bobot_saw' => $bobot->nilai_bobot_saw,
            'keterangan_bobot_saw' => $bobot->keterangan_bobot_saw
        );
        echo json_encode($output);
    }

    public function doBobot()
    {
        $operation = $this->input->post('operation');
        $output = array();
        if ($operation == "Edit") {
            $id = $this->input->post('id_bobot_saw');
            $data = array(
                'nilai_bobot_saw' => $this->input->post('nilai_bobot_saw'),
                'keterangan_bobot_saw' => $this->input->post('keterangan_bobot_saw')
            );
            $process = $this->model_bobot_saw->editBobot($id, $data);
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
}
