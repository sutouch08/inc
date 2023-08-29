<?php
class Customers_model extends CI_Model
{
	private $tb = "OCRD";

  public function __construct()
  {
    parent::__construct();
  }


	public function get_customer_data($code)
	{
		$rs = $this->ms
		->select('CardCode, CardName, GroupNum, ListNum, CntctPrsn, Phone1, Phone2, SlpCode')
		->where('CardCode', $code)
		->get('OCRD');

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_contact($code)
	{
		$rs = $this->ms
		->select('CntctCode, Name as contactName')
		->where('CardCode', $code)
		->order_by('CntctCode', 'DESC')
		->get('OCPR');

		if($rs->num_rows() > 0)
		{
			return $rs->row();
		}

		return NULL;
	}

	public function get($CardCode)
	{
		$rs = $this->ms->where('CardCode', $CardCode)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_name($CardCode)
	{
		$rs = $this->ms->select('CardName')->where('CardCode', $CardCode)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->CardName;
		}

		return NULL;
	}
}
?>
