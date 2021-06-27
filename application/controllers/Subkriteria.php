<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subkriteria extends CI_Controller
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
        $this->load->model('model_subkriteria');
    }

    public function listSubkriteria()
    {
        $this->load->model('model_kriteria');
        $this->content['kriteria'] = $this->model_kriteria->getAll();
        $this->content['page'] = 'Data Subkriteria';
        $this->twig->display('subkriteria.html', $this->content);
    }

    public function subkriteriaLists()
    {
        $subkriteria = $this->model_subkriteria->make_datatables();
        $data = array();
        if (!empty($subkriteria)) {
            $no = $_POST['start'] + 1;
            foreach ($subkriteria as $row) {
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nama_subkriteria;
                $sub_data[] = $row->nama_kriteria;
                $sub_data[] = $row->nilai_subkriteria;
                $sub_data[] = "<button class='btn btn-warning btn-sm mr-2 editSubkriteria' id='" . $row->id_subkriteria . "' title='Edit subkriteria'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteSubkriteria' id='" . $row->id_subkriteria . "' title='Delete subkriteria'><i class='fa fa-trash'></i></button>";
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_subkriteria->get_all_data(),
            'recordsFiltered' => $this->model_subkriteria->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function subkriteriaById()
    {
        $id = $this->input->post('id_subkriteria');
        $subkriteria = $this->model_subkriteria->getById($id);
        $output = array(
            'nama_subkriteria' => $subkriteria->nama_subkriteria,
            'id_kriteria_sub' => $subkriteria->id_kriteria_sub,
            'nilai_subkriteria' => $subkriteria->nilai_subkriteria
        );
        echo json_encode($output);
    }

    public function doSubkriteria()
    {
        $operation = $this->input->post('operation');
        $output = array();
        if ($operation == "Tambah") {
            $data = array(
                'nama_subkriteria' => $this->input->post('nama_subkriteria'),
                'id_kriteria_sub' => $this->input->post('id_kriteria_sub'),
                'nilai_subkriteria' => $this->input->post('nilai_subkriteria')
            );
            $process = $this->model_subkriteria->tambahsubkriteria($data);
            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Menambahkan data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Menambahkan data gagal !';
            }
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_subkriteria');
            $data = array(
                'nama_subkriteria' => $this->input->post('nama_subkriteria'),
                'id_kriteria_sub' => $this->input->post('id_kriteria_sub'),
                'nilai_subkriteria' => $this->input->post('nilai_subkriteria')
            );
            $process = $this->model_subkriteria->editsubkriteria($id, $data);
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

    public function deleteSubkriteria()
    {
        $id = $this->input->post('id_subkriteria');
        $process = $this->model_subkriteria->deletesubkriteria($id);
        echo json_encode($process);
    }
}
