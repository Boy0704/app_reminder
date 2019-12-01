
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Customer Code <?php echo form_error('customer_code') ?></label>
            <input type="text" class="form-control" name="customer_code" id="customer_code" placeholder="Customer Code" value="<?php echo $customer_code; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nama <?php echo form_error('nama') ?></label>
            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?php echo $nama; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Handphone <?php echo form_error('handphone') ?></label>
            <input type="text" class="form-control" name="handphone" id="handphone" placeholder="Handphone" value="<?php echo $handphone; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Email1 <?php echo form_error('email1') ?></label>
            <input type="text" class="form-control" name="email1" id="email1" placeholder="Email1" value="<?php echo $email1; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Email2 <?php echo form_error('email2') ?></label>
            <input type="text" class="form-control" name="email2" id="email2" placeholder="Email2" value="<?php echo $email2; ?>" />
        </div>
	    <input type="hidden" name="id_customer" value="<?php echo $id_customer; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('customer') ?>" class="btn btn-default">Cancel</a>
	</form>
   