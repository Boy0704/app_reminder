
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php if ($this->session->userdata('level')=='admin' or $this->session->userdata('level')=='psr'): ?>
                <?php echo anchor(site_url('cabang/create'),'Create', 'class="btn btn-primary"'); ?>
            <?php endif ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('cabang/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('cabang'); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Cabang</th>
		<th>No Rekening</th>
		<th>Bank</th>
        <?php if ($this->session->userdata('level')=='admin' or $this->session->userdata('level')=='psr'): ?>
		<th>Action</th>
        <?php endif ?>
            </tr><?php
            foreach ($cabang_data as $cabang)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $cabang->cabang ?></td>
			<td><?php echo $cabang->no_rekening ?></td>
			<td><?php echo $cabang->bank ?></td>
            <?php if ($this->session->userdata('level')=='admin' or $this->session->userdata('level')=='psr'): ?>
			<td style="text-align:center" width="100px">
				<?php 
				echo anchor(site_url('cabang/update/'.$cabang->id_cabang),'<span class="label label-info">Ubah</span>'); 
				echo ' | '; 
				echo anchor(site_url('cabang/delete/'.$cabang->id_cabang),'<span class="label label-danger">Hapus</span>','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
				?>
			</td>
            <?php endif ?>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    