<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reminder extends CI_Controller
{
    public $file1 = '';
    public $file2 = '';
    public $file3 = '';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Reminder_model');
        $this->load->library('form_validation');
    }

    public function custom()
    {
        // log_r($this->session->userdata());
        $sql = '';
        if ($_POST != NULL) {
            if ($this->session->userdata('level') == 'supervisor') {
                if (isset($_POST['c_customer'])) {
                    $this->db->where('id_cabang', $this->session->userdata('id_cabang'));
                    $this->db->where('customer_code', $_POST['customer']);
                    $sql = $this->db->get('reminder');
                }

                if (isset($_POST['c_tanggal'])) {
                    $tgl1 = $this->input->post('tgl1');
                    $tgl2 = $this->input->post('tgl2');
                    $id_cabang = $this->session->userdata('id_cabang');
                    $sql = $this->db->query("SELECT * FROM reminder WHERE id_cabang='$id_cabang' and invoice_due_date BETWEEN $tgl1 and $tgl2");
                }

                if (isset($_POST['c_status'])) {
                    $this->db->where('id_cabang', $this->session->userdata('id_cabang'));
                    $this->db->where('to_send', $_POST['status_proses']);
                    $sql = $this->db->get('reminder');
                }

                if (isset($_POST['c_status_reminder'])) {
                    $this->db->where('id_cabang', $this->session->userdata('id_cabang'));
                    $this->db->where('status', $_POST['status_reminder']);
                    $sql = $this->db->get('reminder');
                }

                
            } elseif ($this->session->userdata('level') == 'psr') {
                if (isset($_POST['c_customer'])) {
                    $this->db->where('user', $this->session->userdata('id_user'));
                    $this->db->where('customer_code', $_POST['customer']);
                    $sql = $this->db->get('reminder');
                }

                if (isset($_POST['c_tanggal'])) {
                    $tgl1 = $this->input->post('tgl1');
                    $tgl2 = $this->input->post('tgl2');
                    $id_user = $this->session->userdata('id_user');
                    $sql = $this->db->query("SELECT * FROM reminder WHERE user='$id_user' and invoice_due_date BETWEEN $tgl1 and $tgl2");
                }

                if (isset($_POST['c_status'])) {
                    $this->db->where('user', $this->session->userdata('id_user'));
                    $this->db->where('to_send', $_POST['status_proses']);
                    $sql = $this->db->get('reminder');
                }

                if (isset($_POST['c_status_reminder'])) {
                    $this->db->where('user', $this->session->userdata('id_user'));
                    $this->db->where('status', $_POST['status_reminder']);
                    $sql = $this->db->get('reminder');
                }
            }

            $data = array(
                    'judul_page' => 'reminder/reminder_list',
                    'konten' => 'reminder/reminder_list_custom',
                    'data' => $sql
                );
            $this->load->view('v_index', $data);
        } else {
            if ($this->session->userdata('level') == 'supervisor') {
                $this->db->where('id_cabang', $this->session->userdata('id_cabang'));
            } elseif ($this->session->userdata('level') == 'psr') {
                $this->db->where('user', $this->session->userdata('id_user'));
            }
            
            $data = array(
                'judul_page' => 'reminder/reminder_list',
                'konten' => 'reminder/reminder_list_custom',
                'data' => $this->db->get('reminder')
            );
            $this->load->view('v_index', $data);
        }

        
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'reminder/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'reminder/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'reminder/index.html';
            $config['first_url'] = base_url() . 'reminder/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Reminder_model->total_rows($q);
        $reminder = $this->Reminder_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'reminder_data' => $reminder,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'judul_page' => 'reminder/reminder_list',
            'konten' => 'reminder/reminder_list',
        );
        $this->load->view('v_index', $data);
        // echo $this->db->last_query();
    }

    public function read($id) 
    {
        $row = $this->Reminder_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_reminder' => $row->id_reminder,
		'customer_code' => $row->customer_code,
		'invoice_date' => $row->invoice_date,
		'amount_total' => $row->amount_total,
		'remark' => $row->remark,
		'email1' => $row->email1,
		'email2' => $row->email2,
		'top' => $row->top,
		'invoice_number' => $row->invoice_number,
		'handphone' => $row->handphone,
		'invoice_due_date' => $row->invoice_due_date,
		'status' => $row->status,
        'judul_page' => 'reminder/reminder_read',
            'konten' => 'reminder/reminder_read',
	    );
            $this->load->view('v_index', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('reminder'));
        }
    }

    public function create() 
    {
        $data = array(
            'judul_page' => 'reminder/reminder_form',
            'konten' => 'reminder/reminder_form',
            'button' => 'Create',
            'action' => site_url('reminder/create_action'),
	    'id_reminder' => set_value('id_reminder'),
	    'customer_code' => set_value('customer_code'),
	    'invoice_date' => set_value('invoice_date'),
	    'amount_total' => set_value('amount_total'),
	    'remark' => set_value('remark'),
	    'email1' => set_value('email1'),
	    'email2' => set_value('email2'),
	    'top' => set_value('top'),
	    'invoice_number' => set_value('invoice_number'),
	    'handphone' => set_value('handphone'),
	    'invoice_due_date' => set_value('invoice_due_date'),
        'status' => set_value('status'),
        'file1' => set_value('file1'),
        'file2' => set_value('file2'),
	    'file3' => set_value('file3'),
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
		'customer_code' => $this->input->post('customer_code',TRUE),
		'invoice_date' => $this->input->post('invoice_date',TRUE),
		'amount_total' => $this->input->post('amount_total',TRUE),
		'remark' => $this->input->post('remark',TRUE),
		'email1' => $this->input->post('email1',TRUE),
		'email2' => $this->input->post('email2',TRUE),
		'top' => $this->input->post('top',TRUE),
		'invoice_number' => $this->input->post('invoice_number',TRUE),
		'handphone' => $this->input->post('handphone',TRUE),
		'invoice_due_date' => $this->input->post('invoice_due_date',TRUE),
        'file1' => upload_gambar_biasa('file', 'upload/', 'pdf|doc|docx|xlsx', 3000, 'file1'),
        'file2' => upload_gambar_biasa('file', 'upload/', 'pdf|doc|docx|xlsx', 3000, 'file2'),
        'file3' => upload_gambar_biasa('file', 'upload/', 'pdf|doc|docx|xlsx', 3000, 'file3'),
        'user' => $this->session->userdata('id_user')
        
	    );

            $this->Reminder_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('reminder'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Reminder_model->get_by_id($id);

        if ($row) {
            $data = array(
                'judul_page' => 'reminder/reminder_form',
                'konten' => 'reminder/reminder_form',
                'button' => 'Update',
                'action' => site_url('reminder/update_action'),
		'id_reminder' => set_value('id_reminder', $row->id_reminder),
		'customer_code' => set_value('customer_code', $row->customer_code),
		'invoice_date' => set_value('invoice_date', $row->invoice_date),
		'amount_total' => set_value('amount_total', $row->amount_total),
		'remark' => set_value('remark', $row->remark),
		'email1' => set_value('email1', $row->email1),
		'email2' => set_value('email2', $row->email2),
		'top' => set_value('top', $row->top),
		'invoice_number' => set_value('invoice_number', $row->invoice_number),
		'handphone' => set_value('handphone', $row->handphone),
		'invoice_due_date' => set_value('invoice_due_date', $row->invoice_due_date),
        'status' => set_value('status', $row->status),
        'file1' => set_value('file1', $row->file1),
        'file2' => set_value('file2', $row->file2),
        'file3' => set_value('file3', $row->file3),
        'user' => $this->session->userdata('id_user')
	    );
            $this->load->view('v_index', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('reminder'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_reminder', TRUE));
        } else {

            if ($_FILES['file1']['name'] !='') {
                $this->file1 = upload_gambar_biasa('file', 'upload/', 'pdf|doc|docx|xlsx', 3000, 'file1');
            } else {
                $this->file1 = $this->input->post('old_file1');
            } 
            if ($_FILES['file2']['name'] !='') {
                $this->file2 = upload_gambar_biasa('file', 'upload/', 'pdf|doc|docx|xlsx', 3000, 'file2');
            } else  {
                $this->file2 = $this->input->post('old_file2');
            } 
            if ($_FILES['file3']['name'] !='') {
                $this->file3 = upload_gambar_biasa('file', 'upload/', 'pdf|doc|docx|xlsx', 3000, 'file3');
            } else {
                $this->file3 = $this->input->post('old_file3');
            }

            $data = array(
		'customer_code' => $this->input->post('customer_code',TRUE),
		'invoice_date' => $this->input->post('invoice_date',TRUE),
		'amount_total' => $this->input->post('amount_total',TRUE),
		'remark' => $this->input->post('remark',TRUE),
		'email1' => $this->input->post('email1',TRUE),
		'email2' => $this->input->post('email2',TRUE),
		'top' => $this->input->post('top',TRUE),
		'invoice_number' => $this->input->post('invoice_number',TRUE),
		'handphone' => $this->input->post('handphone',TRUE),
        'invoice_due_date' => $this->input->post('invoice_due_date',TRUE),
        'file1' => $this->file1,
        'file2' => $this->file2,
		'file3' => $this->file3,
        'user' => $this->session->userdata('id_user')
	    );

            $this->Reminder_model->update($this->input->post('id_reminder', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('reminder'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Reminder_model->get_by_id($id);

        if ($row) {
            $this->Reminder_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('reminder'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('reminder'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('customer_code', 'customer code', 'trim|required');
	$this->form_validation->set_rules('invoice_date', 'invoice date', 'trim|required');
	$this->form_validation->set_rules('amount_total', 'amount total', 'trim|required');
	// $this->form_validation->set_rules('remark', 'remark', 'trim|required');
	$this->form_validation->set_rules('email1', 'email1', 'trim|required');
	// $this->form_validation->set_rules('email2', 'email2', 'trim|required');
	$this->form_validation->set_rules('top', 'top', 'trim|required');
	$this->form_validation->set_rules('invoice_number', 'invoice number', 'trim|required');
	$this->form_validation->set_rules('handphone', 'handphone', 'trim|required');
	$this->form_validation->set_rules('invoice_due_date', 'invoice due date', 'trim|required');
	// $this->form_validation->set_rules('status', 'status', 'trim|required');

	$this->form_validation->set_rules('id_reminder', 'id_reminder', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Reminder.php */
/* Location: ./application/controllers/Reminder.php */
/* Please DO NOT modify this information : */
/* Generated by Boy Kurniawan 2019-12-01 05:03:54 */
/* https://jualkoding.com */