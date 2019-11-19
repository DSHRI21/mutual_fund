<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mf_calculator extends CI_Controller {

	public function index()
	{
		$data['scheme_names'] = $this->mf->get_scheme_names();
		//print_r($data['scheme_names']);die;
		$this->load->view('Mf_calculator/form',$data);
		$this->update_data();
	}

	public function update_data() // prerequisite function :: Run this function to update database
	{
		$this->load->helper("Mf_calculator_helper");
		$dates = $this->get_data_dates();
    	if(!file_exists(get_mf_file_name())): // If today's date file not exists
				get_mf_details($dates[0],$dates[1]); // Get data
				$this->mf->store_in_database(get_mf_file_name()); // Store in database
			endif;
	}

	private function get_data_dates()
	{
		$from_date = "01-Apr-2015"; // Default from date value
		$to_date = date("d-M-Y"); // Default value current date value
		// Database check for records availability
		$min_date = $this->mf->get_date('min');
		$max_date = $this->mf->get_date('max');
		$max_plus_date = date('d-M-Y', strtotime("+1 day", strtotime($max_date)));
		if(!empty($min_date) 
			&& !empty($max_date) 
			&& date('d-M-Y', strtotime($min_date)) == $from_date
			&& strtotime($max_plus_date)<strtotime($to_date)):
				$from_date = $max_plus_date; // if already having past records from_date value changes to max(date) value plus 1
		endif;
		$dates[0] = $from_date;
		$dates[1] = $to_date;
		return $dates;
	}

	public function mf_calculation()
	{
		if($this->form_validation->run('mf_form')){
			$scheme_name = $this->input->post('scheme_name',true);
			$investment_amount = $this->input->post('investment_amount',true);
			$investment_date = $this->input->post('investment_date',true);
			
			$amount = $this->mf->mf_calculator($scheme_name,$investment_amount,$investment_date);
			if( $amount!= false ){
				$response = array(
					'status' => 'success',
					'message' => '<br><h1>Investment amount as per today is Rs.'.$amount.'</h1>');
			}else{
				$response = array(
					'status' => 'fail',
					'message' => '<br><p class="text-danger">Data not found!</p>');
			}
  	}else{
			$response = array(
				'status' => 'fail',
				'message' => '<br><p class="text-danger">Invalid Form Fields');
		}

		$this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
	}
}?>