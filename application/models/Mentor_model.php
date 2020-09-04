<?php
    class Mentor_model extends CI_Model {

        public function __construct() {
            $this->load->database();
        }
        
        public function get_summary_mentors() {
            // $this->db->select('mtr.matric, mtr.name, mtr.email, mtr.photo_path, sig.code, role.role_name');
            $this->db->select('mtr.*, sig.code as sigcode, role.role_name');
            $this->db->from('tbl_mentor as mtr');
            $this->db->join('tbl_sig as sig', 'sig.id = mtr.sig_id_fk', 'left');
            $this->db->join('tbl_role as role', 'mtr.org_role_id_fk = role.id', 'left');
            $this->db->order_by('role.role_name', 'DESC');

            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_mentor($matric = FALSE) {
            if($matric === FALSE) {
                $query = $this->db->get('tbl_mentor');
                return $query->result_array();
            }
            $query = $this->db->get_where('tbl_mentor', array('matric' => $matric));
            return $query->row_array();
        }

        public function getMyMentor($sig_id) {
            $query = $this->db->get_where('tbl_mentor', array('sig_fk_id' => $sig_id));
            return $query->result();
        }

        public function register_mentor() {
            $data = array(
                'matric' => $this->input->post('matric'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'position' => $this->input->post('position'),
                'sig_id_fk' => $this->input->post('sig_id'),
                'org_role_id_fk' => $this->input->post('role_id'),
                'photo_path' => $this->input->post('photo_path')
            );

            return $this->db->insert('tbl_mentor', $data);
        }
    }