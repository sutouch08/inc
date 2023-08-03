<?php
function select_team($id = NULL)
{
	$ci =& get_instance();

	$ci->load->model('masters/sales_team_model');

	$ds = "";

	$result = $ci->sales_team_model->get_all();

	if(!empty($result))
	{
		foreach($result as $rs)
		{
			$ds .= "<option value='{$rs->id}' ".is_selected($id, $rs->id).">{$rs->name}</option>";
		}
	}

	return $ds;
}


function select_brand($id = NULL)
{
	$ci =& get_instance();

	$ci->load->model('masters/product_brand_model');

	$ds = "";

	$result = $ci->product_brand_model->get_all();

	if(!empty($result))
	{
		foreach($result as $rs)
		{
			$ds .= "<option value='{$rs->id}' ".is_selected($id, $rs->id).">{$rs->name}</option>";
		}
	}

	return $ds;
}


 ?>
