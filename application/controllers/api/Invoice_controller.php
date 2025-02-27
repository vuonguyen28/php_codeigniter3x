<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . 'libraries/RestController.php';
    require APPPATH . 'libraries/Format.php';

    use chriskacerguis\RestServer\RestController;
    /**
     * @property Invoice_model $Invoice_model
     * @property form_validation $form_validation
     * @property input $input
     * @property output $output
     */
    class invoice_controller extends RestController{

        public function __construct()
        {
            parent:: __construct();
            $this->load->model("Invoice_model");
            $this->load->library('form_validation');
        }
       
        public function add_invoice_post() {

            //log data
            $input_data = $this->input->raw_input_stream; 
            $post_data = $this->input->post();

            log_message('error', 'Received data: ' . print_r($post_data, true));
            log_message('error', 'Raw JSON data: ' . $input_data);

            // Get data from Json body
            $input_data = json_decode(file_get_contents('php://input'), true);
            $_POST = $input_data; 

            // Define validation rules for each field
            $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');
            $this->form_validation->set_rules('total_amount', 'total Amount', 'required|decimal');
            $this->form_validation->set_rules('payment_status', 'payment status', 'required|in_list[pending,paid,failed,refunded]');
            $this->form_validation->set_rules('order_status', 'order status', 'required|in_list[processing,shipped,delivered,canceled]');
        
        
            if ($this->form_validation->run() == FALSE) {
                $this->output
                     ->set_content_type('application/json')
                     ->set_output(json_encode(['error' => validation_errors()]));
                return;
            }
    
            // Kiểm tra danh sách chi tiết hóa đơn
            if (!isset($input_data['invoice_details']) || !is_array($input_data['invoice_details'])) {
                $this->output
                     ->set_content_type('application/json')
                     ->set_output(json_encode(['error' => 'Invoice details are required']));
                return;
            }
    
            // Chuẩn bị dữ liệu hóa đơn
            $invoice = [
                'user_id'        => $input_data['user_id'],
                'total_amount'   => $input_data['total_amount'],
                'payment_status' => $input_data['payment_status'],
                'order_status'   => $input_data['order_status']
            ];
    
            // Chuẩn bị dữ liệu chi tiết hóa đơn
            $invoice_details = [];
            foreach ($input_data['invoice_details'] as $detail) {
                $invoice_details[] = [
                    'book_id'  => $detail['book_id'],
                    'quantity' => $detail['quantity'],
                    'price'    => $detail['price']
                ];
            }

            $result = $this->Invoice_model->add_invoice($invoice, $invoice_details);

            // Kiểm tra kết quả
            if ($result) {
                $response = ['success' => true, 'message' => 'Invoice created successfully'];
            } else {
                $response = ['success' => false, 'error' => 'Failed to create invoice'];
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }
?>