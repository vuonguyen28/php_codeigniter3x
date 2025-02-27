<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . 'libraries/RestController.php';
    require APPPATH . 'libraries/Format.php';

    use chriskacerguis\RestServer\RestController;
    /**
     * @property Book_model $Book_model
     * @property form_validation $form_validation
     * @property input $input
     */
    class BookController extends RestController{

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Book_model");
            $this->load->library('form_validation');
        }
        public function show_books_all_get() {
            $result_book = $this->Book_model->get_books();
    
            if (!empty($result_book)) {
                $this->response($result_book, 200);
            } else {
                $this->response(['message' => 'No books found'], 404);
            }
        }
        public function show_book_by_id_get($id){
            $result = $this->Book_model->get_book($id);
            
            if(!empty($result)){
                $this ->response($result,200);
            }else{
                $this->response(['message'=> 'No book found'], 404);
            }
        }

        public function add_book_post() {

            //log data
            $input_data = $this->input->raw_input_stream; // if sending json
            $post_data = $this->input->post(); // if sending form-data

            // log
            log_message('error', 'Received data: ' . print_r($post_data, true));
            log_message('error', 'Raw JSON data: ' . $input_data);

            // Get data from Json body
            $json_data = json_decode(file_get_contents('php://input'), true);
            $_POST = $json_data; 

            // Define validation rules for each field
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than_equal_to[0]');
            $this->form_validation->set_rules('published_year', 'Published Year', 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[' . date('Y') . ']');
            $this->form_validation->set_rules('page_count', 'Page Count', 'required|integer|greater_than_equal_to[1]');
            $this->form_validation->set_rules('category_id', 'Category', 'required|integer');
            $this->form_validation->set_rules('publisher_id', 'Publisher', 'required|integer');
            $this->form_validation->set_rules('description', 'Description', 'trim');
            $this->form_validation->set_rules('image', 'Image');
        
            // check validation
            if ($this->form_validation->run() == FALSE) {
                $this->response([
                    "message" => validation_errors(),
                    "status" => 400
                ], 400);
                return;
            }

            // Extrating valid data
            $data = [
                "title" => $this->post('title'),
                "description" => $this->post('description'),
                "price" => $this->post('price'),
                "published_year" => $this->post('published_year'),
                "page_count" => $this->post('page_count'),
                "category_id" => $this->post('category_id'),
                "publisher_id" => $this->post('publisher_id'),
                "image_url" => $this->post('image_url'), 
            ];

            if ($this->Book_model->add_book($data)) {
                $this->response(["message" => "Book added successfully"], 200);
            } else {
                $this->response(["message" => "Failed to add book"], 500);
            }
        }
        

        public function update_book_put($id){
            if(!$id){
                $this->response(["message" => "Book id is required"], RestController::HTTP_BAD_REQUEST);
                return;
            }

            $input_data = $this->input->raw_input_stream; 
            $post_data = $this->input->post(); 

            log_message('error', 'Received data: ' . print_r($post_data, true));
            log_message('error', 'Raw JSON data: ' . $input_data);

            $json_data = json_decode(file_get_contents('php://input'), true);
            $_POST = $json_data; 

            // Define validation rules for each field
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than_equal_to[0]');
            $this->form_validation->set_rules('published_year', 'Published Year', 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[' . date('Y') . ']');
            $this->form_validation->set_rules('page_count', 'Page Count', 'required|integer|greater_than_equal_to[1]');
            $this->form_validation->set_rules('category_id', 'Category', 'required|integer');
            $this->form_validation->set_rules('publisher_id', 'Publisher', 'required|integer');
            $this->form_validation->set_rules('description', 'Description', 'trim');
            $this->form_validation->set_rules('image', 'Image');
        
            // check validation
            if ($this->form_validation->run() == FALSE) {
                $this->response([
                    "message" => validation_errors(),
                    "status" => 400
                ], 400);
                return;
            }

            // Extrating valid data
            $data = [
                "title" => $this->post('title'),
                "description" => $this->post('description'),
                "price" => $this->post('price'),
                "published_year" => $this->post('published_year'),
                "page_count" => $this->post('page_count'),
                "category_id" => $this->post('category_id'),
                "publisher_id" => $this->post('publisher_id'),
                "image_url" => $this->post('image_url'), 
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