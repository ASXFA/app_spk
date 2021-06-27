<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil_matching extends CI_Controller
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
        $this->load->model('model_hasil');
    }

    public function listMatching()
    {
        $kriteria = $this->model_kriteria->getAll();
        $subkriteria = $this->model_subkriteria->getAll();
        $santri = $this->model_santri->getAll()->result();
        $this->content['santri'] = $santri;
        $this->content['kriteria'] = $kriteria;
        $this->content['subkriteria'] = $subkriteria;
        $this->content['page'] = 'Penilaian Profile Matching';

        if ($this->input->post('proses') == "proses") {
            $kriteria_proses = $this->input->post('id_kriteria');
            $subkriteria_proses = $this->input->post('id_subkriteria');
            $kualifikasi = array();
            for ($i = 0; $i < count($kriteria_proses); $i++) {
                $data = array(
                    'id_kriteria_kual' => $kriteria_proses[$i],
                    'id_subkriteria_kual' => $subkriteria_proses[$i]
                );
                array_push($kualifikasi, $data);
            }
            $this->content['kualifikasi'] = $kualifikasi;

            $nilaiStandard = $this->nilai_standard($kualifikasi);
            $nilaiAlternatif = $this->nilai_alternatif($santri);
            $nilaiGap = $this->nilaiGap($santri, $nilaiAlternatif, $nilaiStandard);
            $nilaiBobotGap = $this->bobotGap($nilaiGap);
            $hasil = $this->hasil($santri, $nilaiGap);

            $this->model_hasil->deleteHasil();
            for ($i = 0; $i < count($hasil); $i++) {
                $data = array(
                    'id_santri' => $hasil[$i]['id_santri'],
                    'nilaiCf' => $hasil[$i]['nilaiCf'],
                    'nilaiSf' => $hasil[$i]['nilaiSf'],
                    'total' => $hasil[$i]['total']
                );
                $proces = $this->model_hasil->tambahHasil($data);
            }

            $this->content['nilaiStandard'] = $nilaiStandard;
            $this->content['nilaiAlternatif'] = $nilaiAlternatif;
            $this->content['nilaiGap'] = $nilaiGap;
            $this->content['bobotGap'] = $nilaiBobotGap;

            $allKriteria = array();
            for ($o = 0; $o < count($kriteria_proses); $o++) {
                $datt = array();
                foreach ($kriteria as $k) {
                    if ($k->id_kriteria == $kriteria_proses[$o]) {
                        $datt['nama_kriteria'] = $k->nama_kriteria;
                    }
                }
                foreach ($subkriteria as $s) {
                    if ($s->id_subkriteria == $subkriteria_proses[$o]) {
                        $datt['nama_subkriteria'] = $s->nama_subkriteria;
                    }
                }
                array_push($allKriteria, $datt);
            }

            $this->content['allKriteria'] = $allKriteria;
            $this->content['hasil'] = $this->model_hasil->getAll();
            $this->content['hasilRekomendasi'] = $this->model_hasil->getAllSantri();
            $this->content['pesan'] = "Berhasil Menghitung !";
        }

        $this->twig->display('profil_matching.html', $this->content);
    }


    // functi ambil nilai standard
    function nilai_standard($array)
    {
        $nilai = array();
        for ($i = 0; $i < count($array); $i++) {
            $get = $this->model_subkriteria->getNilaiById($array[$i]['id_subkriteria_kual']);
            $data = array('id_kriteria' => $array[$i]['id_kriteria_kual'], 'nilai' => $get->nilai_subkriteria);
            array_push($nilai, $data);
        }
        return $nilai;
    }

    function nilai_alternatif($array)
    {
        $nilai = array();
        foreach ($array as $arr) {
            $getById = $this->model_alternatif->getBySantri($arr->id_santri);
            foreach ($getById as $g) {
                $get = $this->model_subkriteria->getById($g->id_subkriteria_alternatif);
                $data = array('id_santri' => $arr->id_santri, 'id_kriteria' => $g->id_kriteria_alternatif, 'nilai' => $get->nilai_subkriteria);
                array_push($nilai, $data);
            }
        }
        return $nilai;
    }

    function nilaiGap($santri, $array1, $array2)
    {
        $nilai = array();
        foreach ($santri as $santri) {
            for ($i = 0; $i < count($array1); $i++) {
                if ($array1[$i]['id_santri'] == $santri->id_santri) {
                    for ($j = 0; $j < count($array2); $j++) {
                        if ($array1[$i]['id_kriteria'] == $array2[$j]['id_kriteria']) {
                            $hitung = $array1[$i]['nilai'] - $array2[$j]['nilai'];
                            $data = array(
                                'id_santri' => $santri->id_santri,
                                'id_kriteria' => $array1[$i]['id_kriteria'],
                                'nilai_gap' => $hitung
                            );
                            array_push($nilai, $data);
                        }
                    }
                }
            }
        }
        return $nilai;
    }

    function bobotGap($array)
    {
        $nilai = array();
        for ($i = 0; $i < count($array); $i++) {
            $bobot = $this->model_selisih->getByNilai($array[$i]['nilai_gap']);
            $data = array('id_santri' => $array[$i]['id_santri'], 'id_kriteria' => $array[$i]['id_kriteria'], 'bobot' => $bobot->bobot_selisih);
            array_push($nilai, $data);
        }
        return $nilai;
    }

    function hasil($santri, $array)
    {
        $nilai = array();
        $nilai_jenis_cf = 0;
        $nilai_jenis_sf = 0;
        $this->load->model('model_jenis_kriteria');
        $jenis = $this->model_jenis_kriteria->getAll();
        foreach ($jenis as $j) {
            if ($j->nama_jenis == "Core Factor (CF)") {
                $nilai_jenis_cf += $j->nilai_jenis;
            }
            if ($j->nama_jenis == "Secondary Factor (SF)") {
                $nilai_jenis_sf += $j->nilai_jenis;
            }
        }
        $kriteriaa = $this->model_kriteria->getKriteriaJenis();
        foreach ($santri as $santri) {
            $jmlCf = 0;
            $jmlSf = 0;
            $totCf = 0;
            $totSf = 0;
            $cnt = 1;
            foreach ($kriteriaa as $krit) {
                if ($cnt == 1) {
                    $a = $krit->id_jenis;
                    $cnt += 1;
                }
                if ($cnt == 2) {
                    $b = $krit->id_jenis;
                    $cnt += 1;
                }
            }
            foreach ($kriteriaa as $kr) {
                for ($i = 0; $i < count($array); $i++) {
                    if ($array[$i]['id_santri'] == $santri->id_santri && $array[$i]['id_kriteria'] == $kr->id_kriteria) {
                        $bobot = $this->model_selisih->getByNilai($array[$i]['nilai_gap']);
                        if ($kr->id_jenis == $a) {
                            $jmlCf += 1;
                            $totCf += $bobot->bobot_selisih;
                        }
                        if ($kr->id_jenis == $b) {
                            $jmlSf += 1;
                            $totSf += $bobot->bobot_selisih;
                        }
                    }
                }
            }
            $rataCf = $totCf / $jmlCf;
            $rataSf = $totSf / $jmlSf;

            $hasilCf = $rataCf * $nilai_jenis_cf;
            $hasilSf = $rataSf * $nilai_jenis_sf;

            $total = $hasilCf + $hasilSf;

            $data = array(
                'id_santri' => $santri->id_santri,
                'nilaiCf' => number_format(floatval($rataCf), 1),
                'nilaiSf' => number_format(floatval($rataSf), 1),
                'total' => number_format(floatval($total), 1)
            );
            array_push($nilai, $data);
        }
        return $nilai;
    }
}
