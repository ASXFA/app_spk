<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Santri extends CI_Controller
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
        $this->load->model('model_santri');
    }

    public function listSantri()
    {
        $this->content['page'] = 'Data Santri / Alternatif';
        $this->twig->display('santri.html', $this->content);
    }

    public function santriLists()
    {
        $santri = $this->model_santri->make_datatables();
        $data = array();
        if (!empty($santri)) {
            $no = 1;
            foreach ($santri as $row) {
                $sub_data = array();
                $sub_data[] = $no;
                $sub_data[] = $row->nama_santri;
                $sub_data[] = date('d F Y', strtotime($row->ttl_santri));
                $waktuawal  = new DateTime($row->ttl_santri); //waktu di setting
                $waktuakhir = new DateTime(); //2019-02-21 09:35 waktu sekarang
                $umur  = $waktuakhir->diff($waktuawal);
                $sub_data[] = $umur->y . ' Tahun';
                $sub_data[] = $row->jk_santri;
                $sub_data[] = $row->kelas_santri;
                $sub_data[] = $row->created_by;
                if ($this->content['role_user_login'] == 2) {
                    $sub_data[] = "<a href='" . base_url() . "listAlternatif/" . $row->id_santri . "' class='btn btn-secondary btn-sm mr-2' title='Alternatif Objek'><i class='fa fa-list'></i></a><button class='btn btn-warning btn-sm mr-2 editSantri' id='" . $row->id_santri . "' title='Edit Santri'><i class='fa fa-edit'></i></button><button class='btn btn-danger btn-sm mr-2 deleteSantri' id='" . $row->id_santri . "' title='Delete Santri'><i class='fa fa-trash'></i></button>";
                } else {
                    $sub_data[] = "-";
                }
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_santri->get_all_data(),
            'recordsFiltered' => $this->model_santri->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function santriById()
    {
        $id = $this->input->post('id');
        $santri = $this->model_santri->getById($id);
        $output = array(
            'nama_santri' => $santri->nama_santri,
            'ttl_santri' => $santri->ttl_santri,
            'jk_santri' => $santri->jk_santri,
            'kelas_santri' => $santri->kelas_santri
        );
        echo json_encode($output);
    }

    public function doSantri()
    {
        $operation = $this->input->post('operation');
        $output = array();
        if ($operation == "Tambah") {
            $data = array(
                'nama_santri' => $this->input->post('nama_santri'),
                'ttl_santri' => $this->input->post('ttl_santri'),
                'jk_santri' => $this->input->post('jk_santri'),
                'kelas_santri' => $this->input->post('kelas_santri'),
                'created_by' => $this->content['nama_user_login']
            );
            $process = $this->model_santri->tambahSantri($data);
            if (!empty($process)) {
                $output['cond'] = '1';
                $output['id_santri'] = $process;
                $output['ops'] = "tambah";
                $output['msg'] = 'Menambahkan data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Menambahkan data gagal !';
            }
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_santri');
            $data = array(
                'nama_santri' => $this->input->post('nama_santri'),
                'ttl_santri' => $this->input->post('ttl_santri'),
                'jk_santri' => $this->input->post('jk_santri'),
                'kelas_santri' => $this->input->post('kelas_santri'),
                'created_by' => $this->content['nama_user_login']
            );
            $process = $this->model_santri->editSantri($id, $data);
            if ($process) {
                $output['cond'] = '1';
                $output['ops'] = "edit";
                $output['msg'] = 'Edit data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Edit data gagal !';
            }
        }
        echo json_encode($output);
    }

    public function deleteSantri()
    {
        $this->load->model('model_alternatif');
        $id = $this->input->post('id');
        $santri = $this->model_santri->getById($id);
        $this->model_alternatif->deleteAlternatif($santri->id_santri);
        $process = $this->model_santri->deleteSantri($id);
        echo json_encode($process);
    }
}
