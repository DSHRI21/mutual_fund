<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mf_calculator_model extends CI_Model {

	public function store_in_database($filename)
	{
    $file = fopen($filename, "r");
    while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE):
    	if(is_numeric($emapData[0])):
	    	$array = array("scheme_code"=>$emapData[0],
				    	"scheme_name"=>$emapData[1],
				    	"isin_growth"=>$emapData[2],
				    	"isin_reinvestment"=>$emapData[3],
				    	"nav"=>$emapData[4],
				    	"repurchase_price"=>$emapData[5],
				    	"sale_price"=>$emapData[6],
				    	"purchase_date"=>date("Y-m-d", strtotime($emapData[7])));

	    	$this->db->insert('fund_details',$array);
    	endif;
    endwhile;
    fclose($file);
    if($this->uri->segment(2) == "update_data")
    	echo "CSV File has been successfully Imported.";
	}

	public function get_date($type)
	{
		$q = $this->db->select($type.'(purchase_date) as date')->get('fund_details');
		return $q->row('date');
	}

	public function get_scheme_names()
	{
		$q = $this->db->select('DISTINCT(scheme_name)')->get('fund_details');
		return $q->result_array();
	}

	public function mf_calculator($scheme_name,$investment_amount,$investment_date)
	{
		$old_val = $this->db->select('nav')->where(['scheme_name'=>$scheme_name,'purchase_date'=>$investment_date])->get('fund_details');
		$old_nav = $old_val->row('nav');
		if(!empty($old_nav)):
			$total_units = round($investment_amount/$old_nav,3);
			// Present Nav Value
			$present_val = $this->db->select('nav')->where(['scheme_name'=>$scheme_name,'purchase_date'=>date("Y-m-d")])->get('fund_details');
			$present_nav = $present_val->row('nav');
			if(empty($present_nav)):
				$get_max_date = $this->db->select('MAX(purchase_date) as date')->where(['scheme_name'=>$scheme_name])->get('fund_details');
			  $present_date = $get_max_date->row('date');
				$present_val = $this->db->select('nav')->where(['scheme_name'=>$scheme_name,'purchase_date'=>$present_date])->get('fund_details');
				$present_nav = $present_val->row('nav');
			endif;

			$current_amount = round($present_nav*$total_units,3);

			return $current_amount;
		else:
			return false;
		endif;
	}
}
