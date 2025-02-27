<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Book_model extends CI_Model {
    public function get_books() {
        $this->db->select('books.*, categories.name AS category_name');
        $this->db->from('books');
        $this->db->join('categories', 'books.category_id = categories.id', 'left');
        $query = $this->db->get();
        
        if($query->num_rows() >0){
            return $query->result();
        }else{
            return [];
        }
    }

    public function get_book($id) {
        $this->db->select('books.*, categories.name AS category_name');
        $this->db->from('books');
        $this->db->join('categories', 'books.category_id = categories.id', 'left');
        $this->db->where('books.id', $id);
        $query =  $this->db->get();

        if($query->num_rows() >0){
            return $query->row();
        }else{
            return [];
        }
    }

    public function add_book($data) {
        return $this->db->insert("books", $data);
    }

    public function update_book($id, $data) {
        $this->db->where("id", $id);
        return $this->db->update("books", $data);
    }

    public function delete_book($id) {
        return $this->db->delete("books", ["id" => $id]);
    }
}
