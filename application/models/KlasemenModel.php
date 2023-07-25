<?php
class KlasemenModel extends CI_Model
{
    public function add_club($club_name, $club_city)
    {
        $data = array(
            'club_name' => $club_name,
            'club_city' => $club_city,
            'played' => 0,
            'win' => 0,
            'draw' => 0,
            'lose' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'points' => 0
        );

        $this->db->insert('clubs', $data);
    }

    public function get_all_clubs()
    {
        return $this->db->get('clubs')->result_array();
    }

    public function get_clubs()
    {
        return $this->db->get('clubs')->result_array();
    }

    public function get_club_by_name($club_name)
    {
        // Mengambil data klub dari tabel 'klub' berdasarkan nama klub
        $this->db->where('club_name', $club_name);
        $query = $this->db->get('clubs');

        // Mengembalikan data klub jika ada, atau null jika tidak ditemukan
        return $query->row();
    }


    public function get_all_scores()
    {
        return $this->db->get('scores')->result_array();
    }

    public function add_score($club1_id, $club2_id, $score1, $score2, $match_date = NULL)
    {
        // Insert skor pertandingan
        $data = array(
            'club1_id' => $club1_id,
            'club2_id' => $club2_id,
            'score1' => $score1,
            'score2' => $score2,
            'match_date' => $match_date // Gunakan nilai NULL jika $match_date adalah NULL
        );

        // Cek nilai variabel sebelum insert
        echo "<pre>";
        var_dump($data);
        echo "</pre>";

        $this->db->insert('scores', $data);

        // Update data klub
        $this->update_club_data($club1_id, $club2_id, $score1, $score2);
    }

    public function add_score_multiple($club1_id, $club2_id, $score1, $score2, $match_date = NULL)
    {
        // Insert skor pertandingan
        $data = array(
            'club1_id' => $club1_id,
            'club2_id' => $club2_id,
            'score1' => $score1,
            'score2' => $score2,
            'match_date' => $match_date // Gunakan nilai NULL jika $match_date adalah NULL
        );

        // Cek nilai variabel sebelum insert
        echo "<pre>";
        var_dump($data);
        echo "</pre>";

        $this->db->insert('scores', $data);

        // Update data klub
        $this->update_club_data($club1_id, $club2_id, $score1, $score2);
    }


    public function update_club_data($club1_id, $club2_id, $score1, $score2)
    {
        $club1_data = $this->db->get_where('clubs', array('id' => $club1_id))->row_array();
        $club2_data = $this->db->get_where('clubs', array('id' => $club2_id))->row_array();

        $club1_goals_for = $club1_data['goals_for'] + $score1;
        $club1_goals_against = $club1_data['goals_against'] + $score2;
        $club2_goals_for = $club2_data['goals_for'] + $score2;
        $club2_goals_against = $club2_data['goals_against'] + $score1;

        // Hitung hasil pertandingan (menang, seri, atau kalah)
        if ($score1 > $score2) {
            $club1_win = $club1_data['win'] + 1;
            $club1_draw = $club1_data['draw'];
            $club1_lose = $club1_data['lose'];
            $club2_win = $club2_data['win'];
            $club2_draw = $club2_data['draw'];
            $club2_lose = $club2_data['lose'] + 1;
            $club1_points = $club1_data['points'] + 3;
            $club2_points = $club2_data['points'];
        } elseif ($score1 === $score2) {
            $club1_win = $club1_data['win'];
            $club1_draw = $club1_data['draw'] + 1;
            $club1_lose = $club1_data['lose'];
            $club2_win = $club2_data['win'];
            $club2_draw = $club2_data['draw'] + 1;
            $club2_lose = $club2_data['lose'];
            $club1_points = $club1_data['points'] + 1;
            $club2_points = $club2_data['points'] + 1;
        } else {
            $club1_win = $club1_data['win'];
            $club1_draw = $club1_data['draw'];
            $club1_lose = $club1_data['lose'] + 1;
            $club2_win = $club2_data['win'] + 1;
            $club2_draw = $club2_data['draw'];
            $club2_lose = $club2_data['lose'];
            $club1_points = $club1_data['points'];
            $club2_points = $club2_data['points'] + 3;
        }

        // Update data klub setelah pertandingan
        $this->db->where('id', $club1_id)->update(
            'clubs',
            array(
                'played' => $club1_data['played'] + 1,
                'win' => $club1_win,
                'draw' => $club1_draw,
                'lose' => $club1_lose,
                'goals_for' => $club1_goals_for,
                'goals_against' => $club1_goals_against,
                'points' => $club1_points
            )
        );

        $this->db->where('id', $club2_id)->update(
            'clubs',
            array(
                'played' => $club2_data['played'] + 1,
                'win' => $club2_win,
                'draw' => $club2_draw,
                'lose' => $club2_lose,
                'goals_for' => $club2_goals_for,
                'goals_against' => $club2_goals_against,
                'points' => $club2_points
            )
        );
    }



    public function get_scores()
    {
        $this->db->select('scores.*, c1.club_name as club1_name, c2.club_name as club2_name');
        $this->db->from('scores');
        $this->db->join('clubs as c1', 'scores.club1_id = c1.id');
        $this->db->join('clubs as c2', 'scores.club2_id = c2.id');
        $this->db->order_by('match_date', 'desc');
        return $this->db->get()->result_array();
    }
    public function get_klasemen()
    {
        $this->db->order_by('points', 'DESC');
        return $this->db->get('clubs')->result_array();
    }
    public function get_score_by_clubs($club1_id, $club2_id)
    {
        // Pastikan nilai $club1_id dan $club2_id adalah tipe data integer
        $club1_id = (int) $club1_id;
        $club2_id = (int) $club2_id;

        $this->db->where("(club1_id = $club1_id AND club2_id = $club2_id) OR (club1_id = $club2_id AND club2_id = $club1_id)");
        return $this->db->get('scores')->row_array();
    }
    public function check_existing_match($club1_id, $club2_id)
    {
        $this->db->where('club1_id', $club1_id);
        $this->db->where('club2_id', $club2_id);
        $query = $this->db->get('scores');
        return $query->num_rows() > 0;
    }


}