<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    require APPPATH . 'libraries/RestController.php';

    use chriskacerguis\RestServer\RestController;

    class BookController extends RestController{
        public function index(){
            echo " I am vun api";
        }
    }
?>