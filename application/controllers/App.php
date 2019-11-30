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
			'judul_page' => 'Profil Mahasiswa',
		);
		$this->load->view('v_index',$data);
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
				// redirect('app/index');
				$this->session->set_flashdata('message', alert_tunggu('Gagal Login!\n username atau password kamu salah','warning'));
				redirect('app/login','refresh');
			} else {
				?>
				<script type="text/javascript">
					alert('Username dan password kamu salah !');
					window.location="<?php echo base_url('app/login'); ?>";
				</script>
				<?php
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
