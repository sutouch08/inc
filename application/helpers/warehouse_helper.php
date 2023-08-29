<?php
function select_warehouse($code = NULL)
{
	$ds = '';

	$ci =& get_instance();
	$ci->load->model('masters/warehouse_model');
	$option = $ci->warehouse_model->get_all();

	if(!empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.' : '.$rs->name.'</option>';
		}
	}

	return $ds;
}

function select_warehouse_code($code = NULL)
{
	$ds = '';

	$ci =& get_instance();
	$ci->load->model('masters/warehouse_model');
	$option = $ci->warehouse_model->get_all();

	if(!empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.'</option>';
		}
	}

	return $ds;
}
?>
