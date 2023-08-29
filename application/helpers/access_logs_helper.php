<?php
function select_doc_type($code = NULL)
{
  $ds = "";
  $ci =& get_instance();
  $options = $ci->user_model->docList();

  if( ! empty($options))
  {
    foreach($options as $op)
    {
      $ds .= '<option value="'.$op->code.'" '.is_selected($op->code, $code).'>'.$op->code.'</option>';
    }
  }

  return $ds;
}


function select_action($action = NULL)
{
  $ds = "";
  $acts = array(
    'login' => 'login',
    'add' => 'add',
    'edit' => 'edit',
    'delete' => 'delete',
    'approve' => 'approve',
    'reject' => 'reject',
    'review' => 'review',
    'cancel' => 'cancel',
    'reject_review' => 'reject review'
  );

  foreach($acts as $ac => $name)
  {
    $ds .= '<option value="'.$ac.'" '.is_selected($ac, $action).'>'.$name.'</option>';
  }

  return $ds;
}
 ?>
