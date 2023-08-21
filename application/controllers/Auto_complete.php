<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_complete extends CI_Controller
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
  }


  public function get_customer_code_and_name()
  {
    $df_cust = getConfig('DEFAULT_CUSTOMER_CODE');
    $txt = trim($_REQUEST['term']);
    $sc = array();

    $qr  = "SELECT CardCode AS code, CardName AS name ";
    $qr .= "FROM OCRD ";
    $qr .= "WHERE CardType IN('C', 'L') ";

    $qr .= "AND validFor = 'Y' ";

    if($txt !== '*')
    {
      $qr .= "AND (CardCode = '{$df_cust}' OR CardCode LIKE N'%{$this->ms->escape_str($txt)}%' OR CardName LIKE N'%{$this->ms->escape_str($txt)}%') ";
    }

    $qr .= "ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 50 ROWS ONLY";

    $qs = $this->ms->query($qr);

    if($qs->num_rows() > 0)
    {
      foreach($qs->result() as $rs)
      {
        $sc[] = array('value' => $rs->code, 'name' => $rs->name, 'label' => $rs->code.' | '.$rs->name);
      }
    }


    echo json_encode($sc);
  }


  public function get_item_code_and_name()
  {

    $txt = $_REQUEST['term'];
    $arr = explode('*', $txt);

    $sc = array();

    $qr = "SELECT ItemCode AS code, ItemName AS name ";
    $qr .= "FROM OITM ";
    $qr .= "WHERE ItemType = 'I' AND validFor = 'Y' AND SellItem = 'Y' ";

    if(count($arr) > 1)
    {
      foreach($arr as $ar)
      {
        $qr .= "AND (ItemCode LIKE N'%{$this->ms->escape_str($ar)}%' OR ItemName LIKE N'%{$this->ms->escape_str($ar)}%') ";
      }
    }
    else
    {
      $qr .= "AND (ItemCode LIKE N'%{$this->ms->escape_str($txt)}%' OR ItemName LIKE N'%{$this->ms->escape_str($txt)}%') ";
    }

    $qr .= "ORDER BY ItemCode ASC ";
    $qr .= "OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY";

    $rs = $this->ms->query($qr);

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $rd)
      {
        $sc[] = array(
          'code' => $rd->code,
          'name' => $rd->name,
          'label' => $rd->code.' | '.$rd->name
        );
      }
    }

    echo json_encode($sc);
  }



	public function get_model_name()
	{
		$txt = $_REQUEST['term'];
		$sc = array();

		if($txt != "*")
		{
			$this->db->like('name', $txt);
		}

		$rs = $this->db->limit(50)->get('product_model');

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() as $rd)
			{
				$sc[] = $rd->id.' | '.$rd->name;
			}
		}
		else
		{
			$sc[] = "Not found";
		}

		echo json_encode($sc);
	}



	  public function sub_district()
	  {
	    $sc = array();
	    $adr = $this->db->like('tumbon', $_REQUEST['term'])->limit(20)->get('address_info');
	    if($adr->num_rows() > 0)
	    {
	      foreach($adr->result() as $rs)
	      {
	        $sc[] = $rs->tumbon.'>>'.$rs->amphur.'>>'.$rs->province.'>>'.$rs->zipcode;
	      }
	    }

	    echo json_encode($sc);
	  }


	  public function district()
	  {
	    $sc = array();
	    $adr = $this->db->select("amphur, province, zipcode")
	    ->like('amphur', $_REQUEST['term'])
	    ->group_by('amphur')
	    ->group_by('province')
	    ->limit(20)->get('address_info');
	    if($adr->num_rows() > 0)
	    {
	      foreach($adr->result() as $rs)
	      {
	        $sc[] = $rs->amphur.'>>'.$rs->province.'>>'.$rs->zipcode;
	      }
	    }

	    echo json_encode($sc);
	  }

} //-- end class
?>
