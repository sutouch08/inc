<?php
function _check_login()
{
  $CI =& get_instance();
  $uid = get_cookie('uid');
  if($uid === NULL OR $CI->user_model->verify_uid($uid) === FALSE)
  {
    redirect(base_url().'users/authentication');
  }
}


function get_permission($menu, $uid = NULL, $id_profile = NULL)
{
  $CI =& get_instance();

  $uid = $uid === NULL ? get_cookie('uid') : $uid;
  $user = $CI->user_model->get_user_by_uid($uid);

  if(empty($user))
  {
    return reject_permission();
  }

  //--- If super admin
  if($user->id_profile == -987654321)
  {
    $pm = new stdClass();
    $pm->can_view = 1;
    $pm->can_add = 1;
    $pm->can_edit = 1;
    $pm->can_delete = 1;
    $pm->can_approve = 1;
  }
  else
  {
    $pm = $CI->user_model->get_permission($menu, $user->id_profile);

    if(empty($pm))
    {
      return reject_permission();
    }
    else
    {
      if(getConfig('CLOSE_SYSTEM') == 2)
      {
        $pm->can_add = 0;
        $pm->can_edit = 0;
        $pm->can_delete = 0;
        $pm->can_approve = 0;
      }
    }
  }

  return $pm;
}


function reject_permission()
{
  $pm = new stdClass();
  $pm->can_view = 0;
  $pm->can_add = 0;
  $pm->can_edit = 0;
  $pm->can_delete = 0;
  $pm->can_approve = 0;

  return $pm;
}


function select_sales_team($id = NULL)
{
  $CI =& get_instance();
  $CI->load->model('masters/sales_team_model');
  $result = $CI->sales_team_model->get_all();
  $ds = '';
  if(!empty($result))
  {
    foreach($result as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $id).'>'.$rs->name.'</option>';
    }
  }

  return $ds;
}



function select_employee($empID = NULL)
{
  $ds = '';
  $CI =& get_instance();
	$CI->load->model('masters/employee_model');
  $qs = $CI->employee_model->get_all();
  if(!empty($qs))
  {
    foreach($qs as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $empID).'>'.$rs->firstName.' '.$rs->lastName.'</option>';
    }
  }

  return $ds;
}



function select_saleman($sale_id = '')
{
  $ds = '';
  $CI =& get_instance();
	$CI->load->model('masters/sales_person_model');
  $qs = $CI->sales_person_model->get_all();
  if(!empty($qs))
  {
    foreach($qs as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $sale_id).'>'.$rs->name.'</option>';
    }
  }

  return $ds;
}


function select_user($user_id = NULL)
{
	$ds = '';
	$ci =& get_instance();
	$ci->load->model('users/user_model');
	$option = $ci->user_model->get_all_active();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $user_id).'>'.$rs->uname.'</option>';
		}
	}

	return $ds;
}


function select_profile($id_profile = '')
{
  $ds = '';
  $CI =& get_instance();
	$CI->load->model('users/profile_model');
  $qs = $CI->profile_model->get_all();
  if(!empty($qs))
  {
    foreach($qs as $rs)
    {
      $ds .= '<option value="'.$rs->id.'" '.is_selected($rs->id, $id_profile).'>'.$rs->name.'</option>';
    }
  }

  return $ds;
}

function _can_view_page($can_view)
{
  if( ! $can_view)
  {
    $CI =& get_instance();
    $CI->load->view('deny_page');
    //redirect('deny_page');
  }
}


function profile_name_in($text)
{
  if($text !== '')
  {
    $CI =& get_instance();
    $CI->db->select('id');
  }
}




function user_in($txt)
{
  $sc = array('0');
  $CI =& get_instance();
  $CI->load->model('users/user_model');
  $users = $CI->user_model->search($txt);

  if(!empty($users))
  {
    foreach($users as $rs)
    {
      $sc[] = $rs->uname;
    }
  }

  return $sc;
}


function select_quota($code = NULL)
{
	$sc = '';
	$ci =& get_instance();
	$ci->load->model('masters/quota_model');

	$option = $ci->quota_model->get_all_listed();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			$sc .= '<option value="'.$rs->code.'" '.is_selected($rs->code, $code).'>'.$rs->code.'</option>';
		}
	}

	return $sc;
}


 ?>
