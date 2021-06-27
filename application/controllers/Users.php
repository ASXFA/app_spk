<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
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
        $this->load->model('model_users');
    }

    public function listUsers()
    {
        $this->content['page'] = 'Data Pengguna';
        $this->twig->display('users.html', $this->content);
    }

    public function userLists()
    {
        $users = $this->model_users->make_datatables();
        $data = array();
        if (!empty($users)) {
            $no = 1;
            foreach ($users as $row) {
                if ($this->id != $row->id_users) {
                    $sub_data = array();
                    $sub_data[] = $no;
                    $sub_data[] = $row->uname_users;
                    $sub_data[] = $row->nama_users;
                    $sub_data[] = $row->jk_users;
                    $sub_data[] = $row->hp_users;
                    if ($row->role_users == 1) {
                        $sub_data[] = 'Bag. Keguruan';
                    } else {
                        $sub_data[] = 'Bag. Pengelola santri';
                    }
                    if ($row->status == 0) {
                        $sub_data[] = "<span class='badge badge-danger p-2'>TIDAK AKTIF</span>";
                        $sub_data[] = $row->created_by;
                        $sub_data[] = "<button class='btn btn-success btn-sm mr-2 gantiStatus' id='" . $row->id_users . "' data-status='1' title='Ganti Status'><i class='fa fa-check'></i></button><button class='btn btn-danger btn-sm mr-2 deleteUsers' id='" . $row->id_users . "' title='Delete User'><i class='fa fa-trash'></i></button>";
                    } else {
                        $sub_data[] = "<span class='badge badge-success p-2'>AKTIF</span>";
                        $sub_data[] = $row->created_by;
                        $sub_data[] = "<button class='btn btn-danger btn-sm mr-2 gantiStatus' id='" . $row->id_users . "' data-status='0' title='Ganti Status'><i class='fa fa-times'></i></button><button class='btn btn-danger btn-sm mr-2 deleteUsers' id='" . $row->id_users . "' title='Delete User'><i class='fa fa-trash'></i></button>";
                    }
                    $data[] = $sub_data;
                    $no++;
                }
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_users->get_all_data(),
            'recordsFiltered' => $this->model_users->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function usersById()
    {
        $id = $this->input->post('id');
        $user = $this->model_users->getById($id);
        $output = array(
            'nama' => $user->nama_users,
            'uname' => $user->uname_users,
            'jk' => $user->jk_users,
            'hp' => $user->hp_users,
            'role' => $user->role_users
        );
        echo json_encode($output);
    }

    public function gantiStatusUsers()
    {
        $id = $this->input->post('id');
        $data = array('status' => $this->input->post('status'));
        $process = $this->model_users->editUsers($id, $data);
        echo json_encode($process);
    }

    public function doUsers()
    {
        $operation = $this->input->post('operation');
        $output = array();
        if ($operation == "Tambah") {
            $data = array(
                'nama_users' => $this->input->post('nama'),
                'uname_users' => $this->input->post('uname'),
                'jk_users' => $this->input->post('jk'),
                'hp_users' => $this->input->post('hp'),
                'role_users' => $this->input->post('role'),
                'pass_users' => password_hash($this->input->post('uname'), PASSWORD_DEFAULT),
                'created_by' => $this->content['nama_user_login']
            );
            $process = $this->model_users->tambahUsers($data);
            if ($process) {
                $output['cond'] = '1';
                $output['msg'] = 'Menambahkan data berhasil !';
            } else {
                $output['cond'] = '0';
                $output['msg'] = 'Menambahkan data gagal !';
            }
        } else if ($operation == "Edit") {
            $id = $this->input->post('id_users');
            $data = array(
                'nama_users' => $this->input->post('nama'),
                'uname_users' => $this->input->post('uname'),
                'jk_users' => $this->input->post('jk'),
                'hp_users' => $this->input->post('hp'),
                'role_users' => $this->input->post('role'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->content['nama_user_login']
            );
            $process = $this->model_users->editUsers($id, $data);
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

    public function deleteUsers()
    {
        $id = $this->input->post('id');
        $process = $this->model_users->deleteUsers($id);
        echo json_encode($process);
    }

    public function editProfil()
    {
        $user = $this->model_users->getById($this->id);
        $this->content['user'] = $user;
        $this->content['page'] = 'Pengaturan Akun';
        $this->twig->display('editProfil.html', $this->content);
    }

    public function cekUsername()
    {
        $username = $this->input->post('uname');
        $cek = $this->model_users->getByUname($username);
        $pesan = array();
        if ($cek->num_rows() > 0 && $username != $this->uname) {
            $pesan['cond'] = 0;
        } else {
            $pesan['cond'] = 1;
        }
        echo json_encode($pesan);
    }

    public function aksi_editProfil()
    {
        $data = array(
            'nama_users' => $this->input->post('nama'),
            'uname_users' => $this->input->post('uname'),
            'jk_users' => $this->input->post('jk'),
            'hp_users' => $this->input->post('hp'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->content['nama_user_login']
        );
        $process = $this->model_users->editUsers($this->id, $data);
        echo json_encode($process);
    }

    public function editPassword()
    {
        $cek = $this->model_users->getByUname($this->content['uname_user_login']);
        $pesan = array();
        if ($cek->num_rows() > 0) {
            $user = $cek->row();
            $pass_lama = $this->input->post('pass_lama');
            if (password_verify($pass_lama, $user->pass_users)) {
                $pass_baru = $this->input->post('pass_baru');
                $data = array('pass_users' => password_hash($pass_baru, PASSWORD_DEFAULT));
                $process = $this->model_users->editUsers($this->id, $data);
                $pesan['cond'] = 1;
            } else {
                $pesan['cond'] = 0;
            }
        }
        echo json_encode($pesan);
    }
}
