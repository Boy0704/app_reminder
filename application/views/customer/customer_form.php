        <?php   
        $uri = $this->uri->segment(2);
        // echo $uri; exit;
         ?>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Customer <b style="color: red">*</b> <?php echo form_error('customer') ?></label>
            <input type="text" class="form-control" name="customer" id="customer" placeholder="Customer" value="<?php echo $customer; ?>" <?php echo disable($uri) ?>/>
        </div>
	    <div class="form-group">
            <label for="date">Invoice Date <b style="color: red">*</b> <?php echo form_error('invoice_date') ?></label>
            <input type="date" class="form-control" name="invoice_date" id="invoice_date" placeholder="Invoice Date" value="<?php echo $invoice_date; ?>" <?php echo disable($uri) ?> />
        </div>
	    <div class="form-group">
            <label for="int">Amount Total <b style="color: red">*</b> <?php echo form_error('amount_total') ?></label>
            <input type="number" class="form-control" name="amount_total" id="amount_total" placeholder="Amount Total" value="<?php echo $amount_total; ?>" <?php echo disable($uri) ?> />
        </div>
	    <div class="form-group">
            <label for="remark">Remark <?php echo form_error('remark') ?></label>
            <textarea class="form-control" rows="3" name="remark" id="remark" placeholder="Remark" ><?php echo $remark; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Email1 <b style="color: red">*</b> <?php echo form_error('email1') ?></label>
            <input type="text" class="form-control" name="email1" id="email1" placeholder="Email1" value="<?php echo $email1; ?>" <?php echo disable($uri) ?> />
        </div>
	    <div class="form-group">
            <label for="varchar">Email2 <?php echo form_error('email2') ?></label>
            <input type="text" class="form-control" name="email2" id="email2" placeholder="Email2" value="<?php echo $email2; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Top <b style="color: red">*</b> <?php echo form_error('top') ?></label>
            <input type="text" class="form-control" name="top" id="top" placeholder="Top" value="<?php echo $top; ?>" <?php echo disable($uri) ?> />
        </div>
	    <div class="form-group">
            <label for="varchar">Invoice Number <b style="color: red">*</b> <?php echo form_error('invoice_number') ?></label>
            <input type="number" class="form-control" name="invoice_number" id="invoice_number" placeholder="Invoice Number" value="<?php echo $invoice_number; ?>" <?php echo disable($uri) ?> />
        </div>
	    <div class="form-group">
            <label for="varchar">Handphone <b style="color: red">*</b> <?php echo form_error('handphone') ?></label>
            <input type="text" class="form-control" name="handphone" id="handphone" placeholder="Handphone" value="<?php echo $handphone; ?>" <?php echo disable($uri) ?> />
        </div>
	    <div class="form-group">
            <label for="varchar">Invoice Due Date <b style="color: red">*</b> <?php echo form_error('invoice_due_date') ?></label>
            <input type="date" class="form-control" name="invoice_due_date" id="invoice_due_date" placeholder="Invoice Due Date" value="<?php echo $invoice_due_date; ?>" <?php echo disable($uri) ?> />
        </div>
        <div class="form-group">
            <label>Upload File <b style="color: red">*</b></label>
            <input type="file" name="userfile" class="form-control">
        </div>
	    <input type="hidden" name="id_customer" value="<?php echo $id_customer; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('customer') ?>" class="btn btn-default">Cancel</a>
	</form>
   