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
			$sc .= '<option value="'.$rs->code.'" data-name="'.$rs->name.'" '.is_selected($rs->code, $code).'>'.$rs->code.' : '.$rs->name.'</option>';
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
			$sc .= '<option value="'.$rs->code.'" data-name="'.$rs->name.'" '.is_selected($rs->code, $code).'>'.$rs->code.' : '.$rs->name.'</option>';
		}
	}

	return $sc;
}

function statusName($status, $review, $approve)
{
	$name = "Unknow";

	switch($status)
	{
		case -1 :
			$name = "Draft";
			break;
		case 1 :
			$name = "Success";
			break;
		case 2 :
			$name = "Canceled";
			break;
		case 3 :
			$name = "Failed";
			break;
		case 0 :
			if($review == 'R' OR $approve == 'R')
			{
				$name = "Rejected";
			}
			elseif($review == 'P' OR $approve == 'P')
			{
				$name = 'Pending';
			}
			elseif($approve == 'S')
			{
				$name = "Approved by System";
			}
			elseif($approve == 'A')
			{
				$name = "Approved";
			}

			break;
		default :
			$name = "Unknow";
			break;
	}

	return $name;
}


function select_uom($uomEntry = NULL)
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/products_model');

	$list = $ci->products_model->get_all_uom();

	if( ! empty($list))
	{
		foreach($list as $rs)
		{
			$ds .= '<option value="'.$rs->UomEntry.'" data-code="'.$rs->UomCode.'" '.is_selected($rs->UomEntry, $uomEntry).'>'.$rs->UomName.'</option>';
		}
	}

	return $ds;
}

 ?>
