<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->isLogin = $this->session->userdata('isLogin');
        if ($this->isLogin == 0) {
            redirect('auth');
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
    }

    public function dashboard()
    {
        $this->load->model('model_santri');
        $this->load->model('model_users');
        $this->load->model('model_kriteria');
        $this->load->model('model_subkriteria');
        $this->load->model('model_jenis_kriteria');
        $this->load->model('model_selisih');
        $this->content['users'] = $this->model_users->get_all_data();
        $this->content['santri'] = $this->model_santri->get_all_data();
        $this->content['kriteria'] = $this->model_kriteria->get_all_data();
        $this->content['subkriteria'] = $this->model_subkriteria->get_all_data();
        $this->content['jenis_kriteria'] = $this->model_jenis_kriteria->get_all_data();
        $this->content['selisih'] = $this->model_selisih->get_all_data();
        $this->twig->display('dashboard.html', $this->content);
    }
}
