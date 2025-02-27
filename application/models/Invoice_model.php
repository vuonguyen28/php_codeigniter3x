<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {
    public function add_invoice($invoice, $invoice_detail) {
        $this->db->trans_begin();

        $this->db->insert("invoices", $invoice);
        $invoice_id = $this->db->insert_id();

        if(!$invoice_id){
            $this ->db->trans_rollback();
            return false;
        }

        foreach($invoice_detail as &$detail){
            $detail['invoice_id'] = $invoice_id;
        }

         if (!empty($invoice_detail)) {
            $this->db->insert_batch("invoice_details", $invoice_detail);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            }
        }
        
        $this->db->trans_commit();
        return true;
    }
}
