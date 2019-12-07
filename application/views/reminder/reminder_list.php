
        <div class="row" id="uploadExcel" style="margin-left: 5px; display: none; ">
            <form action="app/import_reminder" method="POST" enctype="multipart/form-data">
                <div class="col-md-4"><input type="file" name="uploadexcel" class="form-control"></div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                </div>
            </form>
        </div><br>

        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php if ($this->session->userdata('level')=='admin' or $this->session->userdata('level')=='psr'): ?>
                    <?php echo anchor(site_url('reminder/create'),'Create', 'class="btn btn-primary"'); ?>
                    <button id="upload" class="btn btn-info">Import Excel</button>
                <?php endif ?>
                
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php //echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 ">
                <!-- <b style="color: red">*) berdasarkan Invoice Number</b>
                <form action="<?php echo site_url('reminder/index'); ?>" class="form-inline" method="get">

                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('reminder'); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form> -->
            </div>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered" style="margin-bottom: 10px" id="example1">
            <thead>
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
        <?php if ($this->session->userdata('level')=='admin' or $this->session->userdata('level')=='psr'): ?>
        <th>Action</th>
        <?php endif ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $this->db->where('id_cabang', get_data('users','id_user',$this->session->userdata('id_user'),'id_cabang'));
            $reminder_data = $this->db->get('reminder');
            foreach ($reminder_data->result() as $reminder)
            {
                ?>
                <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo get_data('customer','customer_code',$reminder->customer_code,'nama'); ?></td>
            <td><?php echo $reminder->invoice_date ?></td>
            <td><?php echo number_format($reminder->amount_total) ?></td>
            <!-- <td><?php echo $reminder->remark ?></td>
            <td><?php echo $reminder->email1 ?></td>
            <td><?php echo $reminder->email2 ?></td>
            <td><?php echo $reminder->top ?></td> -->
            <td><?php echo $reminder->invoice_number ?></td>
            <!-- <td><?php echo $reminder->handphone ?></td> -->
            <td><?php echo $reminder->invoice_due_date ?></td>
            <td>
            <a href="app/ubah_status/<?php echo $reminder->status.'/'.$reminder->id_reminder ?>" onclick="javasciprt: return confirm('Yakin ingin mengubah status ini ?')" title="klik untuk ubah status"><?php echo status($reminder->status) ?></a>         
            </td>
            <?php if ($this->session->userdata('level')=='admin' or $this->session->userdata('level')=='psr'): ?>
            <td style="text-align:center" width="100px">
                <!-- <a href=""><span class="label label-success">Kirim Wa & Email</span></a> -->
                <?php 
                echo anchor(site_url('reminder/update/'.$reminder->id_reminder),'<span class="label label-info">Ubah</span>'); 
                echo ' | '; 
                echo anchor(site_url('reminder/delete/'.$reminder->id_reminder),'<span class="label label-danger">Hapus</span>','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
                ?>
            </td>
            <?php endif ?>
        </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        </div>
        <!-- <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div> -->

        <script type="text/javascript">
            $(document).ready(function() {
                // $('#uploadExcel').hide();

                $('#upload').click(function(event) {
                    $('#uploadExcel').show();
                });;                
            });
        </script>
    