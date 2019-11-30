<?php 
$d = $this->db->get_where('mahasiswa', array('nim'=>$this->session->userdata('username')))->row();
 ?>
<div class="row" style="margin-left: 10px;">
	<table class="table">
		<tr>
			<td>Foto</td>
			<td>:</td>
			<td><img src="image/user/<?php echo $d->foto ?>" style="width: 100px;height: 100px;"></td>
		</tr>
		<tr>
			<td>Nim</td>
			<td>:</td>
			<td><?php echo $d->nim ?></td>
		</tr>
		<tr>
			<td>Nama Lengkap</td>
			<td>:</td>
			<td><?php echo $d->nama_lengkap ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td>:</td>
			<td><?php echo $d->alamat ?></td>
		</tr>
	</table>
</div>