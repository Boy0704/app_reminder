
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<!-- <base href="<?php echo base_url() ?>"> -->
	  <!-- Tell the browser to be responsive to screen width -->
	  <!-- <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> -->
	  <!-- Bootstrap 3.3.7 -->
	  <!-- <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css"> -->
      
</head>
<body>
    <h3>Data Reminder</h3>
	<table style="border: 1px solid black; width: 100%;">
            <tr>
                <th style="height: 50px; ">No</th>
        <th style="height: 50px; ">Customer</th>
        <th style="height: 50px; ">Invoice Date</th>
        <th style="height: 50px; ">Amount Total</th>
        <th style="height: 50px; ">Remark</th>
        <th style="height: 50px; ">Email1</th>
        <th style="height: 50px; ">Email2</th>
        <th style="height: 50px; ">Top</th>
        <th style="height: 50px; ">Invoice Number</th>
        <th style="height: 50px; ">Handphone</th>
        <th style="height: 50px; ">Invoice Due Date</th>
        <th style="height: 50px; ">Status</th>
       
            </tr>
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
        </table>
</body>
</html>
