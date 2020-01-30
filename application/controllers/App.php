<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('Reminder_model');
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
	public function ganti_profil($id_user)
	{
		// print_r($_FILES);exit();
		$foto = '';
		if ($_FILES['foto_user']['name'] == '') {
			$foto = $this->input->post('old_foto');
		} else {
			$nmfile = "user_".time();
            $config['upload_path'] = './image/user';
            $config['allowed_types'] = 'jpg|png';
            $config['max_size'] = '20000';
            $config['file_name'] = $nmfile;
            // load library upload
            $this->load->library('upload', $config);
            // upload gambar 1
            $this->upload->do_upload('foto_user');
            $result1 = $this->upload->data();
            $result = array('gambar'=>$result1);
            $dfile = $result['gambar']['file_name'];
            $foto = $dfile;
			
			
		}
		$data = array(
				'username' => $_POST['username'],
				'nama_user' => $_POST['nama_user'],
				'password' => $_POST['password'],
				'email' => $_POST['email'],
				'foto_user' => $foto
			);
		$this->db->where('id_user', $id_user);
			$this->db->update('users', $data);
			$this->session->set_flashdata('message',alert_biasa('Profil berhasil di ubah','success'));
			redirect('app/profil','refresh');
	}

	public function export_reminder($id_cabang='')
	{
		$this->load->view('reminder/export_reminder');
	}

	public function export_reminder_pdf()
	{
		ob_start();

		$filename="reminder.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya
		//==========================================================================================================
		//Copy dan paste langsung script dibawah ini,untuk mengetahui lebih jelas tentang fungsinya silahkan baca-baca tutorial tentang HTML2PDF
		//==========================================================================================================
		$this->load->view('reminder/export_reminder_pdf');
		$content = ob_get_clean();
		$content = '<page style="font-family: freeserif">'.nl2br($content).'</page>';
		 require_once(APPPATH.'third_party/html2pdf/html2pdf.class.php');
		 try
		 {
		  $html2pdf = new HTML2PDF('L','A4','en', false, 'ISO-8859-15',array(10, 0, 20, 0));
		  $html2pdf->setDefaultFont('Arial');
		  $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		  $html2pdf->Output($filename);
		 }
		 catch(HTML2PDF_exception $e) { echo $e; }
	}

	public function ubah_status($val,$id)
	{
		if ($val == 'ACTIVE') {
			$this->db->where('id_reminder', $id);
			$this->db->update('reminder', array('status'=>'PAID'));
		} else if ($val == 'PAID') {
			$this->db->where('id_reminder', $id);
			$this->db->update('reminder', array('status'=>'EXPIRED'));
		}
		$this->session->set_flashdata('message',alert_biasa('Status berhasil di ubah','success'));
		redirect('reminder','refresh');
	}

	public function kirim_invoice()
	{
		$this->db->where('to_send', 0);
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
			$telp_psr = get_data('users','id_user',$value->user,'no_telp');
			$psr = '';
			$message = "Pelanggan yang terhormat:\n\n".$customer."\n\nKami telah mengirimkan email ke ".$email." untuk menginformasikan perihal Invoice No.".$invoice."."."\n\nUntuk informasi lebih lanjut, silahkan menghubungi Sdr. ".$nama_psr."\n\nTerimakasih.\n\nHormat kami,\nPT Hexindo Adiperkasa Tbk";
			$messageEmail = '
			<img src="'.base_url().'/image/logo.png">
			<p>Kepada:<br />'.$customer.'<br />Di Tempat<br /><br />Perihal : Konfirmasi Piutang<br /><br />Terlampir kami sampaikan bahwa saldo hutang Bapak/Ibu kepada kami atas pembelian spare part dan jasa service dengan No Invoice '.$invoice.' yang akan jatuh tempo pada tanggal '.$value->invoice_due_date.' sebesar Rp.'.number_format($value->amount_total).'.00 . Mohon dapat segera melunasi Piutang tersebut sebelum jatuh tempo yang telah ditentukan.<br /><br />Pembayaran dapat di transfer ke Rekening kami:<br /><br />PT Hexindo Adiperkasa Tbk<br />'.$bank.' cabang '.$cabang.'<br />A/C '.$no_rek.' (IDR)<br /><br />Mohon apabila sudah dilakukan pembayaran, untuk menghubungi PSR kami Sdr. '.$nama_psr.' dengan email&nbsp;<a href="mailto:'.$email_psr.'" target="_blank" rel="noopener noreferrer">'.$email_psr.'</a>&nbsp;.<br /><br />Demikian disampaikan atas perhatian dan kerjasamanya diucapkan terima kasih.<br /><br />Hormat kami,<br />PT Hexindo Adiperkasa Tbk</p>';

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
		        $this->email->from('noreplay@hexindo-tbk.co.id', 'AR Reminder - Invoice No. '.$invoice.'');

		        // Email penerima
		        $this->email->to($email); // Ganti dengan email tujuan

		        // Lampiran email, isi dengan url/path file
		        $this->email->attach(base_url().'upload/'.$value->file1);
		        $this->email->attach(base_url().'upload/'.$value->file2);
		        $this->email->attach(base_url().'upload/'.$value->file3);

		        // Subject email
		        $this->email->subject('AR Reminder - Invoice No. '.$invoice.'');

		        // Isi email
		        $this->email->message($messageEmail);

		        // Tampilkan pesan sukses atau error
		        if ($this->email->send()) {
		            echo 'Sukses! email berhasil dikirim.<br>';
		            $this->db->where('id_reminder', $value->id_reminder);
		            $this->db->update('reminder', array('to_send'=>1));

		            //kirim notif ke PSR
		            //WA
		            $this->Reminder_model->kirim_wa_psr(pesan_wa_balik($nama_psr, $customer, $invoice, number_format($value->amount_total), $value->invoice_due_date,$email),$telp_psr);
		            //EMAIL
		            $this->Reminder_model->kirim_email_psr(notif_email1($nama_psr, $customer, $invoice, number_format($value->amount_total), $value->invoice_due_date,$email),$email_psr);

		        } else {
		            echo 'Error! email tidak dapat dikirim.<br>';
		            echo $this->email->print_debugger();
		        }

				echo "Y <BR>";
			} else {
				echo "T <BR>";
			}
			
		}
	}

	public function notif_followup()
	{
		$this->db->where('to_send', 0);
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
			$telp_psr = get_data('users','id_user',$value->user,'no_telp');
			$psr = '';
			
			//cek apakah sudah = due date
			
			$tgl = $value->invoice_due_date;
			// print_r($tgl);
			if ($tgl == date('Y-m-d') ) {

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
		        $this->email->from('noreplay@hexindo-tbk.co.id', 'Notifikasi Follow Up Invoice');

		        // Email penerima
		        $this->email->to($email_psr); // Ganti dengan email tujuan PSR


		        // Subject email
		        $this->email->subject('Notifikasi Follow Up Invoice');

		        // Isi email
		        $this->email->message(notif_email1($nama_psr, $customer, $invoice, number_format($value->amount_total), $value->invoice_due_date,$email));

		        // Tampilkan pesan sukses atau error
		        if ($this->email->send()) {
		            echo 'Sukses! email follow up berhasil dikirim.<br>';
		            

		        } else {
		            echo 'Error! email follow up tidak dapat dikirim.<br>';
		            echo $this->email->print_debugger();
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
				$temp_di  = PHPExcel_Style_NumberFormat::toFormattedString($row['G'],'YYYY-MM-DD');
      			// $actualdate_di = date('Y-m-d',$temp_di);

				$inv_date = $temp_di;
				$n = $row['E'];
                                
if ($n == 'W1') {
	$n = '+7 days';
} elseif ($n == 'W2') {
	$n = '+14 days';
} elseif ($n == 'W3') {
	$n = '+21 days';
} elseif ($n == 'M1') {
	$n = '+30 days';
} elseif ($n == 'M2') {
	$n = '+60 days';
} elseif ($n == 'M3') {
	$n = '+90 days';
} elseif ($n == 'MH') {
	$n = '+45 days';
}

				//Menambah tanggal
				$date1 = $inv_date;
				$date = new DateTime($date1);
				$date_plus = $date->modify($n);
				$tgl = $date_plus->format("Y-m-d");

      			//$temp_du  = PHPExcel_Style_NumberFormat::toFormattedString($row['H'],'YYYY-MM-DD');
      			// $actualdate_du = date('Y-m-d',$temp_du);
				array_push($data, array(
					'customer_code'=>$row['A'], // Insert data nis dari kolom A di excel
					'email1'=>$retVal1 = ($row['B'] == '') ? get_data('customer','customer_code',$row['A'],'email1') : $row['B'], // Insert data nama dari kolom B di excel
					'email2'=>$retVal2 = ($row['C'] == '') ? get_data('customer','customer_code',$row['A'],'email2') : $row['C'], // Insert data jenis kelamin dari kolom C di excel
					'handphone'=>$retVal3 = ($row['D'] == '') ? get_data('customer','customer_code',$row['A'],'handphone') : $row['D'], // Insert data alamat dari kolom D di excel
					'top'=>$row['E'], // Insert data alamat dari kolom D di excel
					'invoice_number'=>$row['F'], // Insert data alamat dari kolom D di excel
					'invoice_date'=>$temp_di, // Insert data alamat dari kolom D di excel
					'invoice_due_date'=>$tgl, // Insert data alamat dari kolom D di excel
					'amount_total'=>$row['I'], // Insert data alamat dari kolom D di excel
					'user' => $this->session->userdata('id_user')
				));
			}
			
			$numrow++; // Tambah 1 setiap kali looping
		}
		// echo "<pre>";
		// print_r($data);exit;

		// Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model
		$this->db->insert_batch('reminder', $data);
		
		$this->session->set_flashdata('message',alert_biasa('Import data excel berhasil','success'));
		redirect('reminder','refresh');
	}

	public function import_customer()
	{
		unlink('upload/import_data/import_customer.xlsx');
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';

		// Fungsi untuk melakukan proses upload file
		$return = array();
		$this->load->library('upload'); // Load librari upload
			
		$config['upload_path'] = './upload/import_data/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '2048';
		$config['overwrite'] = true;
		$config['file_name'] = 'import_customer';
	
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
		$loadexcel = $excelreader->load('upload/import_data/import_customer.xlsx'); // Load file yang telah diupload ke folder excel
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
				
      			// $actualdate_du = date('Y-m-d',$temp_du);
				array_push($data, array(
					'customer_code'=>$row['A'], // Insert data nis dari kolom A di excel
					'nama'=>$row['B'], // Insert data nama dari kolom B di excel
					'handphone'=>$row['C'], // Insert data jenis kelamin dari kolom C di excel
					'email1'=>$row['D'], // Insert data alamat dari kolom D di excel
					'email2'=>$row['E'], // Insert data alamat dari kolom D di excel
				));
			}
			
			$numrow++; // Tambah 1 setiap kali looping
		}
		// echo "<pre>";
		// print_r($data);exit;

		// Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model
		$this->db->insert_batch('customer', $data);
		
		$this->session->set_flashdata('message',alert_biasa('Import data excel berhasil','success'));
		redirect('customer','refresh');
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
					$sess_data['id_cabang'] = $row->id_cabang;
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
