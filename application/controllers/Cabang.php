<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cabang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Cabang_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'cabang/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'cabang/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'cabang/index.html';
            $config['first_url'] = base_url() . 'cabang/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Cabang_model->total_rows($q);
        $cabang = $this->Cabang_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'cabang_data' => $cabang,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'judul_page' => 'cabang/cabang_list',
            'konten' => 'cabang/cabang_list',
        );
        $this->load->view('v_index', $data);
    }

    public function read($id) 
    {
        $row = $this->Cabang_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_cabang' => $row->id_cabang,
		'cabang' => $row->cabang,
		'no_rekening' => $row->no_rekening,
		'bank' => $row->bank,
	    );
            $this->load->view('cabang/cabang_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cabang'));
        }
    }

    public function create() 
    {
        $data = array(
            'judul_page' => 'cabang/cabang_form',
            'konten' => 'cabang/cabang_form',
            'button' => 'Create',
            'action' => site_url('cabang/create_action'),
	    'id_cabang' => set_value('id_cabang'),
	    'cabang' => set_value('cabang'),
	    'no_rekening' => set_value('no_rekening'),
	    'bank' => set_value('bank'),
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
		'cabang' => $this->input->post('cabang',TRUE),
		'no_rekening' => $this->input->post('no_rekening',TRUE),
		'bank' => $this->input->post('bank',TRUE),
	    );

            $this->Cabang_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('cabang'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Cabang_model->get_by_id($id);

        if ($row) {
            $data = array(
                'judul_page' => 'cabang/cabang_form',
                'konten' => 'cabang/cabang_form',
                'button' => 'Update',
                'action' => site_url('cabang/update_action'),
		'id_cabang' => set_value('id_cabang', $row->id_cabang),
		'cabang' => set_value('cabang', $row->cabang),
		'no_rekening' => set_value('no_rekening', $row->no_rekening),
		'bank' => set_value('bank', $row->bank),
	    );
            $this->load->view('v_index', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cabang'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_cabang', TRUE));
        } else {
            $data = array(
		'cabang' => $this->input->post('cabang',TRUE),
		'no_rekening' => $this->input->post('no_rekening',TRUE),
		'bank' => $this->input->post('bank',TRUE),
	    );

            $this->Cabang_model->update($this->input->post('id_cabang', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('cabang'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Cabang_model->get_by_id($id);

        if ($row) {
            $this->Cabang_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('cabang'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cabang'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('cabang', 'cabang', 'trim|required');
	$this->form_validation->set_rules('no_rekening', 'no rekening', 'trim|required');
	$this->form_validation->set_rules('bank', 'bank', 'trim|required');

	$this->form_validation->set_rules('id_cabang', 'id_cabang', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Cabang.php */
/* Location: ./application/controllers/Cabang.php */
/* Please DO NOT modify this information : */
/* Generated by Boy Kurniawan 2019-12-06 04:29:40 */
/* https://jualkoding.com */