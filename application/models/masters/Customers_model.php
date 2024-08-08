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

	public function getGroupCode()
  {
    $rs = $this->ms
    ->select('GroupCode AS code, GroupName AS name')
    ->where('GroupType', 'C')
    ->where('Locked', 'N')
    ->order_by('GroupCode', 'ASC')
    ->get('OCRG');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

	public function getGroupNum()
  {
    $rs = $this->ms
    ->select('GroupNum AS code, PymntGroup AS name')
    ->order_by('GroupNum', 'ASC')
    ->get('OCTG');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function getPaymentTerm($CardCode)
  {
    $rs = $this->ms
    ->select('OCTG.GroupNum AS code, OCTG.PymntGroup AS name')
    ->from('OCRD')
    ->join('OCTG', 'OCRD.GroupNum = OCTG.GroupNum')
    ->where('OCRD.CardCode', $CardCode)
    ->get();

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_industry()
  {
    $rs = $this->ms
    ->select('IndCode AS code, IndDesc AS name')
    ->order_by('IndCode', 'ASC')
    ->get('OOND');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_territory()
  {
    $rs = $this->ms
    ->select('territryID AS code, descript AS name')
    ->where('inactive', 'N')
    ->get('OTER');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

	public function get_price_list()
  {
    $rs = $this->ms
    ->select("ListNum AS code, ListName AS name")
    ->get('OPLN');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function get_customer_price_list($code)
  {
    $rs = $this->ms
    ->select('OPLN.ListNum AS code, OPLN.ListName AS name')
    ->from('OCRD')
    ->join('OPLN', 'OCRD.ListNum = OPLN.ListNum', 'left')
    ->where('OCRD.CardCode', $code)
    ->get();

    if($rs->num_rows() == 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_tax_group($code) //--- OVTG
  {
    $rs = $this->ms
    ->select('Code AS code, Name AS name')
    ->where('Code', $code)
    ->get('OVTG');

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_properties_list() //--- OCQG
  {
    $rs = $this->ms
    ->select('GroupCode AS code, GroupName AS name')
    ->order_by('GroupCode', 'ASC')
    ->get('OCQG');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


	public function get_customer_indicator() //-- OIDC
  {
    $rs = $this->ms->get('OIDC');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

	public function get_slp_code_and_name($card_code)
  {
    if(! is_null($card_code))
		{
			$rs = $this->ms
			->select('OCRD.SlpCode, OSLP.SlpName')
			->from('OCRD')
			->join('OSLP', 'OCRD.SlpCode = OSLP.SlpCode', 'left')
			->where('OCRD.CardCode', $card_code)
			->get();

			if($rs->num_rows() === 1)
			{
				return $rs->row();
			}
		}

    return NULL;
  }
}
?>
