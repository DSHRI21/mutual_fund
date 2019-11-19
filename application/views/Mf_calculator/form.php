<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?= base_url()?>">
	<meta charset="utf-8">
	<title>Mutual Fund Calulator</title>
	<link type="text/css" rel="stylesheet" href="assets/css/bootstrap.min.css"><!-- bootstrap css -->
	<link type="text/css" rel="stylesheet" href="assets/css/bootstrap-datepicker.css"><!-- bootstrap datepicker css -->
</head>
<body>
	<div class="container">
		<h1>Welcome to Mutual Fund Calculator!</h1>
		<div id="body">
			<?= form_open("Mf_calculator/mf_calculation", ['id' => 'mfCalculator']);?>
			  <div class="form-group">
			    <label for="fundName">Select Scheme</label>
			    <?php 
            $options = [];
            if(count($scheme_names)>0):
            		$options[''] = "-- Select Scheme --";
                foreach($scheme_names as $scheme_name): 
                   $options[$scheme_name['scheme_name']] = $scheme_name['scheme_name'];
                endforeach;
            endif;
        	?>
			    <?= form_dropdown(['name'=>'scheme_name','class'=>'form-control','id'=>'scheme_name'], $options);?>
			  </div>
			  <div class="form-group">
			    <label for="exampleFormControlInput1">Investment Amount</label>
			    <?= form_input(['name'=>'investment_amount','class'=>'form-control','placeholder'=>'Investment Amount']);?>
			  </div>
			  <div class="form-group">
			    <label for="exampleFormControlInput1">Investment Date</label>
			    <?= form_input(['name'=>'investment_date','class'=>'form-control date datepicker','placeholder'=>'Investment Date']);?>
			  </div>
			  <?= form_submit(['name'=>'submit','class'=>'btn btn-responsive layout_btn_prevent btn-success','value'=>'Submit']);?>
			<?= form_close();?>
		</div>
		<div id="message"></div>
	</div>
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-datepicker.js"></script>
     <script type="text/javascript">
        $('.datepicker').datepicker({
				    format: 'yyyy-mm-dd',
				    endDate: '1d'
				});
				$(function() {
          $("#mfCalculator").on('submit', function(e) {
              e.preventDefault();
              var mfCalculator = $(this);

              $.ajax({
                  url: mfCalculator.attr('action'),
                  type: 'post',
                  data: mfCalculator.serialize(),
                  success: function(response){
                      //console.log(response);
                      //if(response.status == 'success') {
                      	$("#message").html(response.message);
                      //}


                  }
              });
          });
      });
    </script>
</body>
</html>