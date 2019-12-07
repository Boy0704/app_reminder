<?php 
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");

// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=reminder-export.xls");
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<base href="<?php echo base_url() ?>">
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  <!-- Bootstrap 3.3.7 -->
	  <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
	<table class="table table-bordered" style="margin-bottom: 10px" id="example1">
            <thead>
            <tr>
                <th>No</th>
        <th>Customer</th>
        <th>Invoice Date</th>
        <th>Amount Total</th>
        <th>Remark</th>
        <th>Email1</th>
        <th>Email2</th>
        <th>Top</th>
        <th>Invoice Number</th>
        <th>Handphone</th>
        <th>Invoice Due Date</th>
        <th>Status</th>
       
            </tr>
            </thead>
            <tbody>
            <?php
            if ($this->session->userdata('level')!='admin') {
                $this->db->where('id_cabang', get_data('users','id_user',$this->session->userdata('id_user'),'id_cabang'));
            }
            $start = 0;
            $reminder_data = $this->db->get('reminder');
            foreach ($reminder_data->result() as $reminder)
            {
                ?>
                <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo get_data('customer','customer_code',$reminder->customer_code,'nama'); ?></td>
            <td><?php echo $reminder->invoice_date ?></td>
            <td><?php echo number_format($reminder->amount_total) ?></td>
            <td><?php echo $reminder->remark ?></td>
            <td><?php echo $reminder->email1 ?></td>
            <td><?php echo $reminder->email2 ?></td>
            <td><?php echo $reminder->top ?></td>
            <td><?php echo $reminder->invoice_number ?></td>
            <td><?php echo $reminder->handphone ?></td>
            <td><?php echo $reminder->invoice_due_date ?></td>
            <td><?php echo status($reminder->status) ?>         
            </td>
            
        </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
</body>
</html>
