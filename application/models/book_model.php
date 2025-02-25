<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();  // connect database
    }

    public function get_all_books() {
        return $this->db->get('books')->result();
    }

    public function get_book_by_id($id) {
        return $this->db->get_where('books', ['id' => $id])->row();
    }
}
