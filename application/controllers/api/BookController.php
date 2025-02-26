<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . 'libraries/RestController.php';
    require APPPATH . 'libraries/Format.php';

    use chriskacerguis\RestServer\RestController;

    class BookController extends RestController{

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Book_model");
        }
        public function index_get(){
            $books = new Book_model;
            $result_book =  $books->get_books();
            $this->response($result_book,200);
        }
        public function showBookById_get($id){
            $books = new Book_model;
            $result = $books->get_book($id);
            $this->response($result , 200);
        }
        
        public function add_book_post(){
            $data = [
                "title" =>$this->post('title'),
                "description" =>$this ->post('description'),
                "image_url" =>$this ->post('image_url'),
                "price" =>$this ->post('price'),
                "published_year"=> $this ->post('published_year'),
                "page_count" =>$this ->post('page_count'),
                "category_id"=>$this ->post('category_id'),
                "publisher_id"=>$this->post('publisher_id'),
            ];
            if ($this->Book_model->add_book($data))
            {
                $this->response(["message" => "Book added successfully", 200]);
            }else{
                $this->response(["message" => "failed to add book", 500]);
            }
        }

        public function updateBook_put($id){
            if(!$id){
                $this->response(["message" => "Book id is required"], RestController::HTTP_BAD_REQUEST);
                return;
            }

            $data = [
                "title" =>$this->put('title'),
                "description" =>$this ->put('description'),
                "image_url" =>$this ->put('image_url'),
                "price" =>$this ->put('price'),
                "published_year"=> $this ->put('published_year'),
                "page_count" =>$this ->put('page_count'),
                "category_id"=>$this ->put('category_id'),
                "publisher_id"=>$this->put('publisher_id'),
            ];
            $book = $this->Book_model->get_book($id);
            if(!$book){
                $this->response(["message"=>"Book not found"], RestController::HTTP_NOT_FOUND);
                return;
            }

            if($this ->Book_model->update_book($id, $data)){
                $this->response(["message"=>"book update successfully"], 200);
            }else{
                $this->response(["message"=>"Failed to update book"], 500);
            }
        }
    }
?>