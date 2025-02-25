<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

class BookController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Book_model');
    }

    // API: Lấy danh sách sách
    public function index() {
        $books = $this->Book_model->get_all_books();
        echo json_encode($books);
    }

    // API: Lấy thông tin sách theo ID
    public function view($id) {
        $book = $this->Book_model->get_book_by_id($id);
        if ($book) {
            echo json_encode($book);
        } else {
            echo json_encode(['message' => 'Book not found']);
        }
    }
}
