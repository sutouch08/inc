<?php
function select_order_quota($option, $code = NULL)
{
	$ds = '';

	if( ! empty($option)) //--- qurey result object
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.'</option>';
		}
	}

	return $ds;
}


function select_listed_quota($code = NULL)
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/quota_model');
	$option = $ci->quota_model->get_all_listed();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->code.'</option>';
		}
	}

	return $sc;
}


function select_cost_center($dimCode, $code = NULL)
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/cost_center_model');
	$option = $ci->cost_center_model->get_by_dim_code($dimCode);

	if(!empty($option))
	{
		foreach($option as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->name.'</option>';
		}
	}

	return $sc;
}


function action_name($action)
{
	$arr = array(
		'add' => "Create",
		'edit' => "Edit",
		'approve' => "Approved",
		'reject' => "Reject",
		'cancel' => "Canceled"
	);

	if(isset($arr[$action]))
	{
		return $arr[$action];
	}

	return NULL;
}


 ?>
