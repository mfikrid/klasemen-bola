<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Klasemen extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('KlasemenModel');
    }


    public function index()
    {
        $data['klasemen'] = $this->KlasemenModel->get_klasemen();
        $this->load->view('view_klasemen', $data);
    }

    public function input_club()
    {
        $this->load->view('input_club');
    }

    public function process_input_club()
    {
        $this->load->library('session');
        $this->load->library('form_validation'); // Memuat library Form Validation

        if ($this->input->post('club_name') && $this->input->post('club_city')) {
            $club_name = $this->input->post('club_name');
            $club_city = $this->input->post('club_city');

            // Cek apakah klub dengan nama yang sama sudah ada dalam database
            $existing_club = $this->KlasemenModel->get_club_by_name($club_name);

            if ($existing_club) {
                $this->form_validation->set_message('process_input_club', "Nama tim sudah ada");
                $this->session->set_flashdata('error', "Nama tim sudah ada");
            } else {
                // Jika klub dengan nama yang sama belum ada, lakukan validasi
                $this->form_validation->set_rules('club_name', 'Nama Klub', 'required');
                $this->form_validation->set_rules('club_city', 'Kota Klub', 'required');

                if ($this->form_validation->run() == FALSE) {
                    // Jika validasi gagal, tampilkan pesan kesalahan
                    $this->session->set_flashdata('error', validation_errors());
                } else {
                    // Jika validasi berhasil, tambahkan klub dengan nama dan kota ke database
                    $this->KlasemenModel->add_club($club_name, $club_city);
                    $this->session->set_flashdata('success', "Klub $club_name telah ditambahkan!");
                }
            }
        }

        redirect('klasemen');
    }

    public function check_existing_club()
    {
        $club_name = $this->input->post('club_name');
        $existing_club = $this->KlasemenModel->get_club_by_name($club_name);

        // Jika klub dengan nama yang sama sudah ada, kirimkan respon JSON dengan status exists true
        // Jika tidak ada, kirimkan respon JSON dengan status exists false
        if ($existing_club) {
            $response['exists'] = true;
        } else {
            $response['exists'] = false;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }


    public function input_score()
    {
        $data['clubs'] = $this->KlasemenModel->get_clubs();
        $this->load->view('input_score', $data);
    }
    public function input_score_multiple()
    {
        $data['clubs'] = $this->KlasemenModel->get_clubs();
        $this->load->view('input_score_multiple', $data);
    }
    // Klasemen.php
    public function check_existing_match()
    {
        $club1_id = $this->input->post('club1_id');
        $club2_id = $this->input->post('club2_id');
        $exists = $this->KlasemenModel->check_existing_match($club1_id, $club2_id);

        // Kirimkan respon dalam format JSON
        header('Content-Type: application/json');
        echo json_encode(['exists' => $exists]);
    }



    public function process_input_score()
    {
        $club1_id = $this->input->post('club1_id');
        $club2_id = $this->input->post('club2_id');

        // Cek apakah kedua tim memiliki nama yang sama
        if ($club1_id === $club2_id) {
            $this->session->set_flashdata('error', 'Tim tidak dapat melawan tim dengan nama yang sama!');
            redirect('klasemen');
        }

        // Cek apakah pertandingan antara kedua klub sudah ada
        if ($this->KlasemenModel->check_existing_match($club1_id, $club2_id)) {
            $this->session->set_flashdata('error', 'Pertandingan antara kedua klub sudah ada!');
            redirect('klasemen');
        } else {
            $score1 = $this->input->post('score1');
            $score2 = $this->input->post('score2');
            $match_date = date('Y-m-d H:i:s');

            $this->KlasemenModel->add_score($club1_id, $club2_id, $score1, $score2, $match_date);
            $this->session->set_flashdata('success', 'Skor pertandingan berhasil disimpan!');
            redirect('klasemen');
        }
    }



    public function process_input_score_multiple()
    {
        $club1_ids = $this->input->post('club1_id');
        $club2_ids = $this->input->post('club2_id');
        $score1_values = $this->input->post('score1');
        $score2_values = $this->input->post('score2');

        // Cek apakah jumlah data input sesuai satu sama lain
        if (
            count($club1_ids) !== count($club2_ids) ||
            count($club1_ids) !== count($score1_values) ||
            count($club1_ids) !== count($score2_values)
        ) {
            $this->session->set_flashdata('error', 'Jumlah data input tidak sesuai satu sama lain!');
            redirect('klasemen/input_score_multiple');
        }

        for ($i = 0; $i < count($club1_ids); $i++) {
            $club1_id = $club1_ids[$i];
            $club2_id = $club2_ids[$i];
            $score1 = $score1_values[$i];
            $score2 = $score2_values[$i];

            // Cek apakah kedua tim memiliki nama yang sama
            if ($club1_id === $club2_id) {
                $this->session->set_flashdata('error', 'Tim tidak dapat melawan tim dengan nama yang sama!');
                redirect('klasemen/input_score_multiple');
            }

            // Cek apakah skor pertandingan antara tim tersebut sudah ada
            $existing_score = $this->KlasemenModel->get_score_by_clubs($club1_id, $club2_id);

            if ($existing_score) {
                $this->session->set_flashdata('error', 'Skor pertandingan antara tim tersebut sudah ada!');
                redirect('klasemen/input_score_multiple');
            } else {
                $match_date = date('Y-m-d H:i:s');
                $this->KlasemenModel->add_score($club1_id, $club2_id, $score1, $score2, $match_date);
            }
        }

        $this->session->set_flashdata('success', 'Skor pertandingan berhasil disimpan!');
        redirect('klasemen');
    }



    private function update_clubs_data()
    {
        $matches = $this->KlasemenModel->get_all_scores();

        foreach ($matches as $match) {
            $this->KlasemenModel->update_club_data($match['club1_id'], $match['club2_id'], $match['score1'], $match['score2']);
        }
    }



}