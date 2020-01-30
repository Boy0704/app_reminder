<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reminder_model extends CI_Model
{

    public $table = 'reminder';
    public $id = 'id_reminder';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function kirim_wa_psr($message,$phone_no)
    {
        $message = preg_replace( "/(\n)/", "<ENTER>", $message );
        $message = preg_replace( "/(\r)/", "<ENTER>", $message );

        $phone_no = preg_replace( "/(\n)/", ",", $phone_no );
        $phone_no = preg_replace( "/(\r)/", "", $phone_no );

        $data = array("phone_no" => $phone_no, "key" => "b0d8a55cdfe3e0f97426f96f5bdf02d153bad3a993c2aaf3", "message" => $message);
        $data_string = json_encode($data);

        // echo $data_string; exit();
        $ch = curl_init('http://116.203.92.59/api/send_message');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        echo $result;
    }

    function kirim_email_psr($messageEmail,$emailpenerima)
    {
        $email_saya = "noreplay@hexindo-tbk.co.id";
        $pass_saya  = "";
        //konfigurasi email
        $config = array();
        $config['charset'] = 'utf-8';
        $config['useragent'] = '10.87.200.12';
        $config['protocol']= "smtp";
        $config['mailtype']= "html";
        $config['smtp_host']= "10.87.200.12";
        $config['smtp_port']= "25";
        $config['smtp_timeout']= "25";
        $config['smtp_user']= "$email_saya";
        $config['smtp_pass']= "$pass_saya";
        $config['crlf']="\r\n";
        $config['newline']="\r\n";
        $config['wordwrap'] = TRUE;

        // Load library email dan konfigurasinya
        $this->load->library('email', $config);

        // Email dan nama pengirim
        $this->email->from('noreplay@hexindo-tbk.co.id', 'Notifikasi reminder customer sudah dikirimkan');

        // Email penerima
        $this->email->to($emailpenerima); // Ganti dengan email tujuan


        // Subject email
        $this->email->subject('Notifikasi reminder customer sudah dikirimkan ');

        // Isi email
        $this->email->message($messageEmail);

        // Tampilkan pesan sukses atau error
        if ($this->email->send()) {
            echo 'Sukses! email untuk PSR berhasil dikirim.<br>';
        } else {
            echo 'Error! email untuk PSR tidak dapat dikirim.<br>';
            echo $this->email->print_debugger();
        }
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id_reminder', $q);
	$this->db->or_like('customer_code', $q);
	$this->db->or_like('invoice_date', $q);
	$this->db->or_like('amount_total', $q);
	$this->db->or_like('remark', $q);
	$this->db->or_like('email1', $q);
	$this->db->or_like('email2', $q);
	$this->db->or_like('top', $q);
	$this->db->or_like('invoice_number', $q);
	$this->db->or_like('handphone', $q);
	$this->db->or_like('invoice_due_date', $q);
	$this->db->or_like('status', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        if ($this->session->userdata('level') !='admin') {
            $this->db->where('user', $this->session->userdata('id_user'));
        }
        $this->db->like('invoice_number', $q);
 //        $this->db->or_like('user', $this->session->userdata('id_user'), 'after');
	// $this->db->or_like('customer_code', $q);
	// $this->db->or_like('invoice_date', $q);
	// $this->db->or_like('amount_total', $q);
	// $this->db->or_like('remark', $q);
	// $this->db->or_like('email1', $q);
	// $this->db->or_like('email2', $q);
	// $this->db->or_like('top', $q);
	// $this->db->or_like('invoice_number', $q);
	// $this->db->or_like('handphone', $q);
	// $this->db->or_like('invoice_due_date', $q);
	// $this->db->or_like('status', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Reminder_model.php */
/* Location: ./application/models/Reminder_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-12-01 05:03:54 */
/* http://harviacode.com */