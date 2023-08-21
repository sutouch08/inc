<?php
function select_payment_term($id = NULL)
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/payment_term_model');
	$option = $ci->payment_term_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option data-term="'.$rs->term.'" value="'.$rs->id.'" '.is_selected($rs->id, $id).'>'.$rs->name.'</opion>';
		}
	}

	return $ds;
}

function select_ship_to_code($CardCode, $code = NULL)
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/customer_address_model');
	$options = $ci->customer_address_model->get_address_ship_to_code($CardCode);

	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.' : '.$rs->name.'</option>';
		}
	}

	return $sc;
}


function select_bill_to_code($CardCode, $code = NULL)
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/customer_address_model');
	$options = $ci->customer_address_model->get_address_bill_to_code($CardCode);

	if(!empty($options))
	{
		foreach($options as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.' : '.$rs->name.'</option>';
		}
	}

	return $sc;
}
 ?>
