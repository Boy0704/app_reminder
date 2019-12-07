
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
	    <div class="form-group">
            <label for="varchar">Customer Code <b style="color: red">*</b> <?php echo form_error('customer_code') ?></label>
            <!-- <input type="text" class="form-control" name="customer_code" id="customer_code" placeholder="Customer Code" value="<?php echo $customer_code; ?>" /> -->
            <select id="customer_code" name="customer_code" class="form-control select2" required>
                <option value="<?php echo $customer_code ?>"><?php echo $customer_code ?></option>
                <?php 
                foreach ($this->db->get('customer')->result() as $rw) {
                 ?>
                 <option value="<?php echo $rw->customer_code ?>"><?php echo $rw->customer_code.' | '.$rw->nama ?></option>
                <?php } ?>
            </select>
        </div>
	    <div class="form-group">
            <label for="date">Invoice Date <b style="color: red">*</b> <?php echo form_error('invoice_date') ?></label>
            <input type="date" class="form-control" name="invoice_date" id="invoice_date" placeholder="Invoice Date" value="<?php echo $invoice_date; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Amount Total <b style="color: red">*</b> <?php echo form_error('amount_total') ?></label>
            <input type="text" class="form-control" name="amount_total" id="amount_total" placeholder="Amount Total" value="<?php echo $amount_total; ?>" />
        </div>
	    <div class="form-group">
            <label for="remark">Remark </label>
            <textarea class="form-control" rows="3" name="remark" id="remark" placeholder="Remark"><?php echo $remark; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Email1 <b style="color: red">*</b></label>
            <input type="text" class="form-control" name="email1" id="email1" placeholder="Email1" value="<?php echo $email1; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Email2 </label>
            <input type="text" class="form-control" name="email2" id="email2" placeholder="Email2" value="<?php echo $email2; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Top <b style="color: red">*</b> <?php echo form_error('top') ?></label>
            <!-- <input type="text" class="form-control" name="top" id="top" placeholder="Top" value="<?php echo $top; ?>" /> -->
            <select name="top" id="top" class="form-control">
                <option value="<?php echo $top ?>"><?php echo $top ?></option>
                <option value="+7 days">W1</option>
                <option value="+14 days">W2</option>
                <option value="+21 days">W3</option>
                <option value="+1 month">M1</option>
                <option value="+2 month">M2</option>
                <option value="+3 month">M3</option>
            </select>
        </div>
	    <div class="form-group">
            <label for="varchar">Invoice Number <b style="color: red">*</b><?php echo form_error('invoice_number') ?></label>
            <input type="text" class="form-control" name="invoice_number" id="invoice_number" placeholder="Invoice Number" value="<?php echo $invoice_number; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Handphone <b style="color: red">*</b></label>
            <input type="text" class="form-control" name="handphone" id="handphone" placeholder="Handphone" value="<?php echo $handphone; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Invoice Due Date <b style="color: red">*</b> <?php echo form_error('invoice_due_date') ?></label>
            <input type="date" class="form-control" name="invoice_due_date" id="invoice_due_date" placeholder="Invoice Due Date" value="<?php echo $invoice_due_date; ?>" />
        </div>
        <div class="form-group">
            <label>File Upload</label><br>
            <div id="upload1" class="col-md-3">
              <input type="file" name="file1" class="form-control">
              <?php 
              if ($file1 != '') {
               ?>
              <p><b>*) File Sebelumnya</b></p>
              <p class="label label-info"><?php echo $file1; ?></p>
              <?php } ?>
            </div>
            <button  class="btn btn-primary" id="btn2"><i class="fa fa-plus"></i></button><br><br>

            <div id="upload2" class="col-md-3" style="display: none;">
              <input type="file" name="file2" class="form-control">
              <?php 
              if ($file2 != '') {
               ?>
              <p><b>*) File Sebelumnya</b></p>
              <p class="label label-info"><?php echo $file2; ?></p>
              <?php } ?>
            </div>
            <button  class="btn btn-primary" id="btn3" style="display: none;"><i class="fa fa-plus"></i></button><br><br>

            <div id="upload3" class="col-md-3" style="display: none;">
              <input type="file" name="file3" class="form-control">
              <?php 
              if ($file3 != '') {
               ?>
              <p><b>*) File Sebelumnya</b></p>
              <p class="label label-info"><?php echo $file3; ?></p>
              <?php } ?>
            </div><br><br>
        </div>
        <input type="hidden" name="old_file1" value="<?php echo $file1 ?>">
        <input type="hidden" name="old_file2" value="<?php echo $file2 ?>">
        <input type="hidden" name="old_file3" value="<?php echo $file3 ?>">
	    
	    <input type="hidden" name="id_reminder" value="<?php echo $id_reminder; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('reminder') ?>" class="btn btn-default">Cancel</a>
	</form>
   
   <script type="text/javascript">
       $(document).ready(function() {
           $('#customer_code').change(function(event) {
               var n = $(this).val();
               // console.log(n);
               $.ajax({
                   url: 'app/cek_customer',
                   type: 'GET',
                   dataType: 'JSON',
                   data: {code: n},
               })
               .done(function(param) {
                   console.log("success");
                   $('#email1').val(param.email1);
                   $('#email2').val(param.email2);
                   $('#handphone').val(param.hp);
               })
               .fail(function() {
                   console.log("error");
               })
               .always(function() {
                   console.log("complete");
               });
               
           });

           $('#top').change(function(event) {
               var n = $(this).val();
               var inv_date = $('#invoice_date').val();
               $.ajax({
                   url: 'app/cek_invoice_date',
                   type: 'GET',
                   dataType: 'JSON',
                   data: {inv_date: inv_date, n: n },
               })
               .done(function(param) {
                   console.log("success");
                   $('#invoice_due_date').val(param.tanggal);
               })
               .fail(function() {
                   console.log("error");
               })
               .always(function() {
                   console.log("complete");
               });
           });

           $('#btn2').click(function(event) {
             $('#upload2').show();
             $('#btn3').show();
           });
           $('#btn3').click(function(event) {
             $('#upload3').show();
           });

       });
   </script>