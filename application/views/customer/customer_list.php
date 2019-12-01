
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('customer/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <script type="text/javascript">
                        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                    </script>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('customer/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('customer'); ?>" class="btn btn-default">Reset</a>
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
		<th>Customer</th>
		<th>Invoice Date</th>
		<th>Amount Total</th>
		<!-- <th>Remark</th> -->
		<!-- <th>Email1</th> -->
		<!-- <th>Email2</th> -->
		<!-- <th>Top</th> -->
		<th>Invoice Number</th>
		<!-- <th>Handphone</th> -->
        <th>Invoice Due Date</th>
		<th>Status</th>
		<th>Action</th>
            </tr><?php
            foreach ($customer_data as $customer)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $customer->customer ?></td>
			<td><?php echo $customer->invoice_date ?></td>
			<td><?php echo number_format($customer->amount_total) ?></td>
			<!-- <td><?php echo $customer->remark ?></td>
			<td><?php echo $customer->email1 ?></td>
			<td><?php echo $customer->email2 ?></td>
			<td><?php echo $customer->top ?></td> -->
			<td><?php echo $customer->invoice_number ?></td>
			<!-- <td><?php echo $customer->handphone ?></td> -->
            <td><?php echo $customer->invoice_due_date ?></td>
			<td>
            <a href="app/ubah_status/<?php echo $customer->status.'/'.$customer->id_customer ?>" onclick="javasciprt: return confirm('Yakin ingin mengubah status ini ?')" title="klik untuk ubah status"><?php echo status($customer->status) ?></a>         
            </td>
			<td style="text-align:center" width="200px">
                <a href=""><span class="label label-success">Kirim Wa & Email</span></a>
				<?php 
				echo anchor(site_url('customer/update/'.$customer->id_customer),'<span class="label label-info">Ubah</span>'); 
				echo ' | '; 
				echo anchor(site_url('customer/delete/'.$customer->id_customer),'<span class="label label-danger">Hapus</span>','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
				?>
			</td>
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
    