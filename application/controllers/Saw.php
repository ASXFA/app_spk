<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saw extends CI_Controller
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
        $this->load->model('model_alternatif');
        $this->load->model('model_kriteria');
        $this->load->model('model_subkriteria');
        $this->load->model('model_selisih');
        $this->load->model('model_bobot_saw');
        $this->load->model('model_temp_saw');
        $this->load->model('model_hasil_saw');
    }

    public function listSaw()
    {
        $this->content['page'] = "Penilaian SAW";
        $this->content['santri'] = $this->model_santri->getAll()->result();
        $this->content['bobot'] = $this->model_bobot_saw->getAllKriteria();
        $this->twig->display('saw.html', $this->content);
    }

    public function hitungLists()
    {
        $hasil_perhitungan = $this->model_hasil_saw->make_datatables();

        $data = array();
        if (!empty($hasil_perhitungan)) {
            $no = 1;
            foreach ($hasil_perhitungan as $row) {
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
                $sub_data[] = $row->total_hasil_saw;
                $sub_data[] = "Ranking - " . $no;
                $data[] = $sub_data;
                $no++;
            }
        }
        $output = array(
            'draw' => intval($_POST['draw']),
            'recordsTotal' => $this->model_hasil_saw->get_all_data(),
            'recordsFiltered' => $this->model_hasil_saw->get_filtered_data(),
            'data' => $data
        );

        echo json_encode($output);
    }

    public function hitungSaw()
    {
        $alternatif = $this->model_alternatif->getAllNilaiSubkriteria()->result();
        $santri = $this->model_santri->getAll()->result();
        $kriteria = $this->model_kriteria->getAll();
        $bobot = $this->model_bobot_saw->getAll();
        $all = array();
        $nilai = array();
        $this->model_temp_saw->deleteTemp();
        foreach ($alternatif as $a) {
            $data = array(
                'id_santri_temp_saw' => $a->id_santri_alternatif,
                'id_kriteria_temp_saw' => $a->id_kriteria_alternatif,
                'nilai_temp_saw' => $a->nilai_subkriteria
            );
            $this->model_temp_saw->tambahTemp($data);
        }
        $temp = $this->model_temp_saw->getAll();
        $all = array();
        foreach ($bobot as $b) {
            if ($b->keterangan_bobot_saw == "benefit") {
                $nilaiTinggi = $this->model_temp_saw->getNilaiTertinggi($b->id_kriteria_bobot_saw);
                foreach ($temp as $t) {
                    if ($t->id_kriteria_temp_saw == $b->id_kriteria_bobot_saw) {
                        $hitung = $t->nilai_temp_saw / $nilaiTinggi->nilai_temp_saw;
                        $data = array(
                            'id_santri' => $t->id_santri_temp_saw,
                            'id_kriteria' => $t->id_kriteria_temp_saw,
                            'nilai_hitung' => $hitung
                        );
                        array_push($all, $data);
                    }
                }
            } else if ($b->keterangan_bobot_saw == "cost") {
                $nilaiRendah = $this->model_temp_saw->getNilaiTerendah($b->id_kriteria_bobot_saw);
            }
        }
        // $hasil = array();
        $this->model_hasil_saw->deleteHasil();
        foreach ($santri as $s) {
            $count = 0;
            foreach ($bobot as $b) {
                for ($i = 0; $i < count($all); $i++) {
                    if (($all[$i]['id_kriteria'] == $b->id_kriteria_bobot_saw) && ($all[$i]['id_santri'] == $s->id_santri)) {
                        $count = $count + ($all[$i]['nilai_hitung'] * $b->nilai_bobot_saw);
                    }
                }
            }
            $d = array(
                'id_santri_hasil_saw' => $s->id_santri,
                'total_hasil_saw' => $count
            );
            // array_push($hasil, $d);
            $this->model_hasil_saw->tambahHasil($d);
        }
        $out = array('msg' => 'berhasil');
        echo json_encode($out);
    }
}
