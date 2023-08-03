<?php
select_payment_term($id = NULL)
{
	$ds = "";
	$ci =& get_instance();
	$ci->load->model('masters/payment_term_model');
	$option = $ci->payment_term_model->get_all();

	if( ! empty($option))
	{
		foreach($option as $rs)
		{
			if($rs->is_default == 1)
			{
				$id = $rs->id;
			}

			$ds .= '<opton value="'.$rs->id.'" '.is_selected($rs->id, $id).'>'.$rs->name.'</oprion>';
		}
	}

}
 ?>
