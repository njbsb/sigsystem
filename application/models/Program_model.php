<?php
class Program_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_programs()
    {
        $query = $this->db->get('program');
        return $query->result_array();
    }
}
