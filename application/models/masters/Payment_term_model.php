<?php
class Payment_term_model extends CI_Model
{
  public $tb = "OCTG";

  public function __construct()
  {
    parent::__construct();
  }


	public function get_all()
	{
		$rs = $this->ms
    ->select('GroupNum AS id, PymntGroup AS name, ExtraDays AS term')
    ->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

  public function get_name($groupNum)
  {
    $rs = $this->ms
    ->select('PymntGroup AS name')
    ->where('GroupNum', $groupNum)
    ->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row()->name;
    }

    return NULL;
  }

  public function get($id)
  {
    $rs = $this->ms
    ->select('GroupNum AS id, PymntGroup AS name, ExtraDays AS term')
    ->where('GroupNum', $id)
    ->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }

}
?>
