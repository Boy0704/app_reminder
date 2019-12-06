<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        
        $this->load->library('pagination');
    }
	

	

	public function index()
	{
		if ($this->session->userdata('level') == NULL && $this->session->userdata('username') == NULL) {
			redirect('app/login','refresh');
		}
		$data = array(
			'konten' => 'v_home',
			'judul_page' => 'Dashboard',
		);
		$this->load->view('v_index',$data);
	}

	public function profil()
	{
		if ($this->session->userdata('level') == NULL && $this->session->userdata('username') == NULL) {
			redirect('app/login','refresh');
		}
		$data = array(
			'konten' => 'profil',
			'judul_page' => 'Profil',
		);
		$this->load->view('v_index',$data);
	}

	public function ubah_status($val,$id)
	{
		if ($val == 'ACTIVE') {
			$this->db->where('id_customer', $id);
			$this->db->update('customer', array('status'=>'PAID'));
		} else if ($val == 'PAID') {
			$this->db->where('id_customer', $id);
			$this->db->update('customer', array('status'=>'EXPIRED'));
		}
		$this->session->set_flashdata('message',alert_biasa('Status berhasil di ubah','success'));
		redirect('customer','refresh');
	}

	public function kirim_invoice()
	{
		foreach ($this->db->get('reminder')->result() as $key => $value) {

			$email = '';
			if ($value->email1 == null) {
				$email = $value->email2;
			} else {
				$email = $value->email1;
			}
		
			$customer = get_data('customer','customer_code',$value->customer_code,'nama');
			$invoice = $value->invoice_number;
			$phone_no = $value->handphone;
			$bank = get_data('cabang','id_cabang',get_data('users','id_user',$value->user,'id_cabang'),'bank');
			$cabang = get_data('cabang','id_cabang',get_data('users','id_user',$value->user,'id_cabang'),'cabang');
			$no_rek = get_data('cabang','id_cabang',get_data('users','id_user',$value->user,'id_cabang'),'no_rekening');
			$nama_psr = get_data('users','id_user',$value->user,'nama_user');
			$email_psr = get_data('users','id_user',$value->user,'email');
			$psr = '';
			$message = "Pelanggan yang terhormat:\n\n".$customer."\n\nKami telah mengirimkan email ke ".$email." untuk menginformasikan perihal Invoice No.".$invoice."."."\n\nUntuk informasi lebih lanjut, silahkan menghubungi Kami ".$psr."\n\nTerimakasih.\n\nHormat kami,\nPT Hexindo Adiperkasa Tbk";
			$messageEmail = '<p>Kepada:<br />'.$customer.'<br />Di Tempat<br /><br />Perihal : Konfirmasi Piutang<br /><br />Terlampir kami sampaikan bahwa saldo hutang Bapak/Ibu kepada kami atas pembelian spare part dan jasa service dengan No Invoice '.$invoice.' yang akan jatuh tempo pada tanggal '.$value->handphone.' sebesar Rp.'.number_format($value->amount_total).'.00 . Mohon dapat segera melunasi Piutang tersebut sebelum jatuh tempo yang telah ditentukan.<br /><br />Pembayaran dapat di transfer ke Rekening kami:<br /><br />PT Hexindo Adiperkasa Tbk<br />'.$bank.' cabang '.$cabang.'<br />A/C '.$no_rek.' (IDR)<br /><br />Mohon apabila sudah dilakukan pembayaran, untuk menghubungi PSR kami Sdr. '.$nama_psr.' dengan email&nbsp;<a href="mailto:'.$email_psr.'" target="_blank" rel="noopener noreferrer">'.$email_psr.'</a>&nbsp;.<br /><br />Demikian disampaikan atas perhatian dan kerjasamanya diucapkan terima kasih.<br /><br />Hormat kami,<br />PT Hexindo Adiperkasa Tbk</p>';

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

			//cek apakah sudah 7 sebelum due date
			$date = new DateTime($value->invoice_due_date);
			$date_plus = $date->modify("-7 days");
			$tgl = $date_plus->format("Y-m-d");
			// print_r($tgl);
			if ($tgl == date('Y-m-d') ) {
				//kirim WA
				$result = curl_exec($ch);

				//kirim email
				$config = [
		            'mailtype'  => 'html',
		            'charset'   => 'utf-8',
		            'protocol'  => 'smtp',
		            'smtp_host' => 'smtp.gmail.com',
		            'smtp_user' => 'ucikurniasih123@gmail.com',  // Email gmail
		            'smtp_pass'   => 'nyexngvwogkjcsbr',  // Password gmail
		            'smtp_crypto' => 'ssl',
		            'smtp_port'   => 465,
		            'crlf'    => "\r\n",
		            'newline' => "\r\n"
		        ];

		        // Load library email dan konfigurasinya
		        $this->load->library('email', $config);

		        // Email dan nama pengirim
		        $this->email->from('no-reply@admin.com', 'Tagihan Invoice');

		        // Email penerima
		        $this->email->to($email); // Ganti dengan email tujuan

		        // Lampiran email, isi dengan url/path file
		        $this->email->attach(base_url().'upload/'.$value->file1);
		        $this->email->attach(base_url().'upload/'.$value->file2);
		        $this->email->attach(base_url().'upload/'.$value->file3);

		        // Subject email
		        $this->email->subject('AR Reminder - Invoice No. $invoice');

		        // Isi email
		        $this->email->message($messageEmail);

		        // Tampilkan pesan sukses atau error
		        if ($this->email->send()) {
		            echo 'Sukses! email berhasil dikirim.<br>';
		        } else {
		            echo 'Error! email tidak dapat dikirim.<br>';
		        }

				echo "Y <BR>";
			} else {
				echo "T <BR>";
			}
			
		}
	}

	public function cek_customer()
	{
		$n = $_GET['code'];
		$data = $this->db->get_where('customer', array('customer_code'=>$n))->row();
		echo json_encode(array('email1'=>$data->email1,'email2'=>$data->email2,'hp'=>$data->handphone));
	}

	public function import_reminder()
	{
		unlink('upload/import_data/import_data.xlsx');
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		// Fungsi untuk melakukan proses upload file
		$return = array();
		$this->load->library('upload'); // Load librari upload
			
		$config['upload_path'] = './upload/import_data/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '2048';
		$config['overwrite'] = true;
		$config['file_name'] = 'import_data';
	
		$this->upload->initialize($config); // Load konfigurasi uploadnya
		if($this->upload->do_upload('uploadexcel')){ // Lakukan upload dan Cek jika proses upload berhasil
			// Jika berhasil :
			$return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
			// return $return;
		}else{
			// Jika gagal :
			$return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
			// return $return;
		}
		// print_r($return);exit();
		
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load('upload/import_data/import_data.xlsx'); // Load file yang telah diupload ke folder excel
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		
		// Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
		$data = array();
		
		$numrow = 1;
		foreach($sheet as $row){
			// Cek $numrow apakah lebih dari 1
			// Artinya karena baris pertama adalah nama-nama kolom
			// Jadi dilewat saja, tidak usah diimport
			if($numrow > 1){
				// Kita push (add) array data ke variabel data
				array_push($data, array(
					'customer_code'=>$row['A'], // Insert data nis dari kolom A di excel
					'email1'=>$row['B'], // Insert data nama dari kolom B di excel
					'email2'=>$row['C'], // Insert data jenis kelamin dari kolom C di excel
					'handphone'=>$row['D'], // Insert data alamat dari kolom D di excel
					'top'=>$row['E'], // Insert data alamat dari kolom D di excel
					'invoice_number'=>$row['F'], // Insert data alamat dari kolom D di excel
					'invoice_date'=>$row['G'], // Insert data alamat dari kolom D di excel
					'invoice_due_date'=>$row['H'], // Insert data alamat dari kolom D di excel
					'amount_total'=>$row['I'], // Insert data alamat dari kolom D di excel
				));
			}
			
			$numrow++; // Tambah 1 setiap kali looping
		}
		echo "<pre>";
		print_r($data);exit;

		// Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model
		$this->db->insert_batch('reminder', $data);
		
		$this->session->set_flashdata('message',alert_biasa('Import data excel berhasil','success'));
		redirect('reminder','refresh');
	}

	public function cek_invoice_date()
	{
		$inv_date = $_GET['inv_date'];
		$n = $_GET['n'];
		//Menambah tanggal
		$date1 = $inv_date;
		$date = new DateTime($date1);
		$date_plus = $date->modify($n);
		$tgl = $date_plus->format("Y-m-d");
		echo json_encode(array('tanggal'=>$tgl));
	}


	public function cetak()
	{
		if ($_POST == NULL) {
			if ($this->session->userdata('level') == NULL && $this->session->userdata('username') == NULL) {
				redirect('app/login','refresh');
			}
			$data = array(
				'konten' => 'cetak',
				'judul_page' => 'Cetak Kartu Ujian',
			);
			$this->load->view('v_index',$data);
		} else {
			$this->load->view('cetak_kartu',array('nim'=>$_POST['nim'],'semester'=>$_POST['semester']));
		}
	}

	

	public function login()
	{
		if ($_POST == NULL) {
			$this->load->view('v_login');
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$cek_user = $this->db->query("SELECT * FROM users WHERE username='$username' and password='$password' ");
			if ($cek_user->num_rows() == 1) {
				foreach ($cek_user->result() as $row) {
					$sess_data['id_user'] = $row->id_user;
					$sess_data['nama'] = $row->nama_user;
					$sess_data['username'] = $row->username;
					$sess_data['foto'] = $row->foto_user;
					$sess_data['level'] = $row->level;
					$this->session->set_userdata($sess_data);
				}
				// print_r($this->session->userdata()); exit();
				// redirect('app/index');
				// $this->session->set_flashdata('message', alert_tunggu('Gagal Login!\n username atau password kamu salah','warning'));
				redirect('app','refresh');
			} else {
				$this->session->set_flashdata('message', alert_biasa('Gagal Login!\n username atau password kamu salah','warning'));
				// $this->session->set_flashdata('message', alert_tunggu('Gagal Login!\n username atau password kamu salah','warning'));
				redirect('app/login','refresh');
			}
		}
	}


	function logout()
	{
		$this->session->unset_userdata('id_user');
		$this->session->unset_userdata('nama');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('foto');
		$this->session->unset_userdata('level');
		session_destroy();
		redirect('app');
	}

}
