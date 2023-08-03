<?php

function discount_rule_in($txt)
{
  $sc = "0";
  $CI =& get_instance();
  $CI->load->model('discount/discount_rule_model');
  $rs = $CI->discount_rule_model->search($txt);

  if(!empty($rs))
  {
    foreach($rs as $cs)
    {
      $sc .= ", ".$cs->id;
    }
  }

  return $sc;
}


function discount_label($type, $price, $disc1, $disc2, $disc3, $disc4, $disc5)
{
	$disc = 0.00;
	//---	ถ้าเป็นการกำหนดราคาขาย
	//--- N = netprice , P = percent
	if($type == 'N')
	{
		$disc = $price;
	}
	else
	{

		$disc = round($disc1, 2)."%";
		$disc .= ($disc1 > 0 && $disc2 > 0) ? "+".round($disc2)."%" : "";
		$disc .= ($disc2 > 0 && $disc3 > 0) ? "+".round($disc3)."%" : "";
		$disc .= ($disc3 > 0 && $disc4 > 0) ? "+".round($disc4)."%" : "";
		$disc .= ($disc4 > 0 && $disc5 > 0) ? "+".round($disc5)."%" : "";
	}

	return $disc;
}
?>
