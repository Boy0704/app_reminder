<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'customer/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'customer/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'customer/index.html';
            $config['first_url'] = base_url() . 'customer/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Customer_model->total_rows($q);
        $customer = $this->Customer_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'customer_data' => $customer,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'judul_page' => 'customer/customer_list',
            'konten' => 'customer/customer_list',
        );
        $this->load->view('v_index', $data);
    }

    public function read($id) 
    {
        $row = $this->Customer_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_customer' => $row->id_customer,
		'customer' => $row->customer,
		'invoice_date' => $row->invoice_date,
		'amount_total' => $row->amount_total,
		'remark' => $row->remark,
		'email1' => $row->email1,
		'email2' => $row->email2,
		'top' => $row->top,
		'invoice_number' => $row->invoice_number,
		'handphone' => $row->handphone,
		'invoice_due_date' => $row->invoice_due_date,
	    );
            $this->load->view('customer/customer_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('customer'));
        }
    }

    public function create() 
    {
        $data = array(
            'judul_page' => 'customer/customer_form',
            'konten' => 'customer/customer_form',
            'button' => 'Create',
            'action' => site_url('customer/create_action'),
	    'id_customer' => set_value('id_customer'),
	    'customer' => set_value('customer'),
	    'invoice_date' => set_value('invoice_date'),
	    'amount_total' => set_value('amount_total'),
	    'remark' => set_value('remark'),
	    'email1' => set_value('email1'),
	    'email2' => set_value('email2'),
	    'top' => set_value('top'),
	    'invoice_number' => set_value('invoice_number'),
	    'handphone' => set_value('handphone'),
	    'invoice_due_date' => set_value('invoice_due_date'),
	);
        $this->load->view('v_index', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'customer' => $this->input->post('customer',TRUE),
		'invoice_date' => $this->input->post('invoice_date',TRUE),
		'amount_total' => $this->input->post('amount_total',TRUE),
		'remark' => $this->input->post('remark',TRUE),
		'email1' => $this->input->post('email1',TRUE),
		'email2' => $this->input->post('email2',TRUE),
		'top' => $this->input->post('top',TRUE),
		'invoice_number' => $this->input->post('invoice_number',TRUE),
		'handphone' => $this->input->post('handphone',TRUE),
		'invoice_due_date' => $this->input->post('invoice_due_date',TRUE),
	    );

            $this->Customer_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('customer'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Customer_model->get_by_id($id);

        if ($row) {
            $data = array(
                'judul_page' => 'customer/customer_form',
                'konten' => 'customer/customer_form',
                'button' => 'Update',
                'action' => site_url('customer/update_action'),
		'id_customer' => set_value('id_customer', $row->id_customer),
		'customer' => set_value('customer', $row->customer),
		'invoice_date' => set_value('invoice_date', $row->invoice_date),
		'amount_total' => set_value('amount_total', $row->amount_total),
		'remark' => set_value('remark', $row->remark),
		'email1' => set_value('email1', $row->email1),
		'email2' => set_value('email2', $row->email2),
		'top' => set_value('top', $row->top),
		'invoice_number' => set_value('invoice_number', $row->invoice_number),
		'handphone' => set_value('handphone', $row->handphone),
		'invoice_due_date' => set_value('invoice_due_date', $row->invoice_due_date),
	    );
            $this->load->view('v_index', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('customer'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_customer', TRUE));
        } else {
            $data = array(
		'customer' => $this->input->post('customer',TRUE),
		'invoice_date' => $this->input->post('invoice_date',TRUE),
		'amount_total' => $this->input->post('amount_total',TRUE),
		'remark' => $this->input->post('remark',TRUE),
		'email1' => $this->input->post('email1',TRUE),
		'email2' => $this->input->post('email2',TRUE),
		'top' => $this->input->post('top',TRUE),
		'invoice_number' => $this->input->post('invoice_number',TRUE),
		'handphone' => $this->input->post('handphone',TRUE),
		'invoice_due_date' => $this->input->post('invoice_due_date',TRUE),
	    );

            $this->Customer_model->update($this->input->post('id_customer', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('customer'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Customer_model->get_by_id($id);

        if ($row) {
            $this->Customer_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('customer'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('customer'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('customer', 'customer', 'trim|required');
	$this->form_validation->set_rules('invoice_date', 'invoice date', 'trim|required');
	$this->form_validation->set_rules('amount_total', 'amount total', 'trim|required');
	$this->form_validation->set_rules('remark', 'remark', 'trim|required');
	$this->form_validation->set_rules('email1', 'email1', 'trim|required');
	$this->form_validation->set_rules('email2', 'email2', 'trim|required');
	$this->form_validation->set_rules('top', 'top', 'trim|required');
	$this->form_validation->set_rules('invoice_number', 'invoice number', 'trim|required');
	$this->form_validation->set_rules('handphone', 'handphone', 'trim|required');
	$this->form_validation->set_rules('invoice_due_date', 'invoice due date', 'trim|required');

	$this->form_validation->set_rules('id_customer', 'id_customer', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Customer.php */
/* Location: ./application/controllers/Customer.php */
/* Please DO NOT modify this information : */
/* Generated by Boy Kurniawan 2019-11-30 15:20:36 */
/* https://jualkoding.com */