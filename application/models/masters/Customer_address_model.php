<?php
class Customer_address_model extends CI_Model
{
  public $tb = "CRD1";

  public function __construct()
  {
    parent::__construct();
  }



  public function get($CardCode, $AdresType, $Address)
  {
    $rs = $this->ms
		->where('CardCode', $CardCode)
		->where('AdresType', $AdresType)
		->where('Address', $Address)
		->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }



  public function is_exists($CardCode, $AdresType, $Address)
  {
    $rs = $this->ms
		->where('CardCode', $CardCode)
		->where('AdresType', $AdresType)
		->where('Address', $Address)
		->count_all_results($this->tb);

		if($rs > 0)
		{
			return TRUE;
		}

		return FALSE;
  }


	public function get_address_ship_to_code($CardCode)
	{
		$rs = $this->ms
		->select('Address AS code, Address3 AS name')
		->where('CardCode', $CardCode)
		->where('AdresType', 'S')
		->get('CRD1');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_address_bill_to_code($CardCode)
	{
		$rs = $this->ms
		->select('Address AS code, Address3 AS name')
		->where('CardCode', $CardCode)
		->where('AdresType', 'B')
		->get('CRD1');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_address_ship_to($CardCode, $Address = NULL)
	{
		if(! empty($Address))
		{
			$this->db->where('Address', $Address);
		}

		$rs = $this->ms
		->where('AdresType', 'S')
		->where('CardCode', $CardCode)
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_address_bill_to($CardCode, $Address = NULL)
	{
		if(!empty($Address))
		{
			$this->db->where('Address', $Address);
		}

		$rs = $this->ms
		->where('AdresType', 'B')
		->where('CardCode', $CardCode)
		->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->row();
		}

		return NULL;
	}


}
?>
