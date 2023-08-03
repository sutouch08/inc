<?php

function select_customer_group($code = NULL)
{
	$sc = '';

  $ci =& get_instance();
  $ci->load->model('masters/customer_group_model');
  $options = $ci->customer_group_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }

  return $sc;
}



function select_payment_term($id = NULL)
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/payment_term_model');
  $options = $ci->payment_term_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->id.'" '.is_selected($id, $rs->id).'>'.$rs->name.'</option>';
    }
  }

  return $sc;
}



function select_sale($id = NULL)
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/sales_person_model');
  $options = $ci->sales_person_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->id.'" '.is_selected($id, $rs->id).'>'.$rs->name.'</option>';
    }
  }

  return $sc;
}


function select_customer_region($code = NULL)
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/customer_region_model');
  $options = $ci->customer_region_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}



function select_customer_type($code = NULL)
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/customer_type_model');
  $options = $ci->customer_type_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}



function select_customer_grade($code = NULL)
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/customer_grade_model');
  $options = $ci->customer_grade_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}



function select_customer_area($code = NULL)
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/customer_area_model');
  $options = $ci->customer_area_model->get_all();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}


function select_customer_sales_team($code = NULL)
{
  $sc = '';
  $ci =& get_instance();
  $ci->load->model('masters/customers_model');
  $options = $ci->customers_model->get_customer_sales_team_list();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
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



function customer_in($txt)
{
  $sc = array('0');
  $ci =& get_instance();
  $ci->load->model('masters/customers_model');
  $rs = $ci->customers_model->search($txt);

  if(!empty($rs))
  {
    foreach($rs as $cs)
    {
      $sc[] = $cs->code;
    }
  }

  return $sc;
}



 ?>
