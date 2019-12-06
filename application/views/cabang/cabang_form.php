
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Cabang <?php echo form_error('cabang') ?></label>
            <input type="text" class="form-control" name="cabang" id="cabang" placeholder="Cabang" value="<?php echo $cabang; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">No Rekening <?php echo form_error('no_rekening') ?></label>
            <input type="text" class="form-control" name="no_rekening" id="no_rekening" placeholder="No Rekening" value="<?php echo $no_rekening; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Bank <?php echo form_error('bank') ?></label>
            <input type="text" class="form-control" name="bank" id="bank" placeholder="Bank" value="<?php echo $bank; ?>" />
        </div>
	    <input type="hidden" name="id_cabang" value="<?php echo $id_cabang; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('cabang') ?>" class="btn btn-default">Cancel</a>
	</form>
   