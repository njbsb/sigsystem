<?php
    class Semester_model extends CI_Model {
        public function __construct() {
            $this->load->database();
        }

        public function get_semesters() {
            $query = $this->db->get('tbl_semester');
            return $query->result_array();
        }
    }