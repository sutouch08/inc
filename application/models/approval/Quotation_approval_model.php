<?php
class Quotation_approval_model extends CI_Model
{
	private $tb;
	private $td;

  public function __construct()
  {
    parent::__construct();
		$this->tb = "quotation";
		$this->td = "quotation_details";
  }


	public function add(array $ds = array())
	{
		if( ! empty($ds))
		{
			$rs = $this->db->insert($this->tb, $ds);

			if($rs)
			{
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}


	public function add_detail(array $ds = array())
	{
		if( ! empty($ds))
		{
			$rs = $this->db->insert($this->td, $ds);

			if($rs)
			{
				return $this->db->insert_id();
			}
		}

		return FALSE;
	}


	public function get($code)
	{
		$rs = $this->db->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_header($code)
	{
		$rs = $this->db->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function get_detail($id)
	{
		$rs = $this->db->where('id', $id)->get($this->td);

		if($rs->num_rows() === 1)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_details($code)
	{
		$rs = $this->db
		->where('quotation_code', $code)
		->order_by('LineNum', 'ASC')
		->get($this->td);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	//--- for print
	public function get_un_child_details($code)
	{
		$rs = $this->db
		->where('quotation_code', $code)
		->where('TreeType !=', 'I')
		->order_by('LineNum', 'ASC')
		->get($this->td);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_childs_row($code, $father_uid)
	{
		$rs = $this->db
		->where('quotation_code', $code)
		->where('TreeType', 'I')
		->where('father_uid', $father_uid)
		->order_by('LineNum', 'ASC')
		->get($this->td);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function update($code, array $ds = array())
	{
		if(!empty($ds))
		{
			return $this->db->where('code', $code)->update($this->tb, $ds);
		}

		return FALSE;
	}


	public function update_detail($id, array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->where('id', $id)->update($this->td, $ds);
		}

		return FALSE;
	}


	public function delete_detail($id)
	{
		return $this->db->where('id', $id)->delete($this->td);
	}


	public function drop_details($code)
	{
		return $this->db->where('quotation_code', $code)->delete($this->td);
	}


	public function cancle_details($code)
	{
		return $this->db->set('LineStatus', 'D')->where('quotation_code', $code)->update($this->td);
	}


	public function cancle_order($code)
	{
		return $this->db->set('Status', 2)->where('code', $code)->update($this->tb);
	}


	public function close_details($code)
	{
		return $this->db->set('LineStatus', 'C')->where('quotation_code', $code)->update($this->td);
	}

	public function is_document_avalible($code, $uuid)
  {
    $rs = $this->db
    ->where('code', $code)
    ->where('session_uuid !=', $uuid)
    ->where('session_expire >=', date('Y-m-d H:i:s'))
    ->count_all_results($this->tb);

    if($rs == 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function update_uuid($code, $uuid)
  {
    $expiration = date('Y-m-d H:i:s', time() + 1 * 60);
    $ds = array(
      'session_uuid' => $uuid,
      'session_expire' => $expiration
    );

    return $this->db->where('code', $code)->update($this->tb, $ds);
  }



	public function update_doc_total($code)
	{
		$ds = $this->db
		->select_sum('TotalVatAmount')
		->select_sum('LineTotal')
		->where('quotation_code', $code)
		->get($this->td);

		if($ds->num_rows() === 1)
		{
			$DiscPrcnt = $this->get_order_disc_percent($code);

			$DiscAmount = $ds->row()->TotalAmount * ($DiscPrcnt * 0.01);

			return $this->db
			->set('DocTotal', $ds->row()->LineTotal)
			->set('VatSum', $ds->row()->TotalVatAmount)
			->set('DiscAmount', $DiscAmount)
			->where('code', $code)
			->update($this->tb);
		}

		return FALSE;
	}


	public function get_order_disc_percent($code)
	{
		$rs = $this->db->select('DiscPrcnt')->where('code', $code)->get($this->tb);

		if($rs->num_rows() === 1)
		{
			return $rs->row()->DiscPrcnt;
		}

		return 0;
	}



	public function get_max_code($pre)
  {
    $rs = $this->db
    ->select_max('code')
    ->like('code', $pre, 'after')
    ->order_by('code', 'DESC')
    ->get($this->tb);

    if($rs->num_rows() === 1)
		{
			return $rs->row()->code;
		}

		return NULL;
  }


	public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		if( isset($ds['code']) && $ds['code'] != '')
		{
			$this->db->like('code', $ds['code']);
		}

		if( isset($ds['sqNo']) && $ds['sqNo'] != '')
		{
			$this->db->like('DocNum', $ds['sqNo']);
		}

		if( isset($ds['project']) && $ds['project'] != '')
		{
			$this->db->like('Project', $ds['project']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$this->db
			->where('DocDate >=', from_date($ds['from_date']))
			->where('DocDate <=', to_date($ds['to_date']));
		}

		if(isset($ds['user_id']) && $ds['user_id'] != 'all')
		{
			$this->db->where('user_id', $ds['user_id']);
		}

		if(isset($ds['emp_id']) && $ds['emp_id'] != 'all')
		{
			$this->db->where('OwnerCode', $ds['emp_id']);
		}

		if(isset($ds['customer']) && $ds['customer'] != '')
		{
			$this->db
			->group_start()
			->like('CardCode', $ds['customer'])
			->or_like('CardName', $ds['customer'])
			->group_end();
		}

		if(isset($ds['sale_id']) && $ds['sale_id'] != 'all')
		{
			$this->db->where('SlpCode', $ds['sale_id']);
		}

		if(isset($ds['approval']) && $ds['approval'] != 'all')
		{
			$this->db->where('Approved', $ds['approval']);
		}

		if(isset($ds['review']) && $ds['review'] != 'all')
		{
			$this->db->where('Review', $ds['review']);
		}

		if(isset($ds['status']) && $ds['status'] != 'all')
		{
			$this->db->where('Status', $ds['status']);
		}

		$rs = $this->db->order_by('DocDate', 'DESC')->order_by('code', 'DESC')->limit($perpage, $offset)->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}



	public function count_rows(array $ds = array())
	{
		if( isset($ds['code']) && $ds['code'] != '')
		{
			$this->db->like('code', $ds['code']);
		}

		if( isset($ds['sqNo']) && $ds['sqNo'] != '')
		{
			$this->db->like('SqNo', $ds['sqNo']);
		}

		if( isset($ds['project']) && $ds['project'] != '')
		{
			$this->db->like('Project', $ds['project']);
		}

		if( isset($ds['soNo']) && $ds['soNo'] != '')
		{
			$this->db->like('DocNum', $ds['soNo']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$this->db
			->where('DocDate >=', from_date($ds['from_date']))
			->where('DocDate <=', to_date($ds['to_date']));
		}

		if(isset($ds['user_id']) && $ds['user_id'] != 'all')
		{
			$this->db->where('user_id', $ds['user_id']);
		}

		if(isset($ds['emp_id']) && $ds['emp_id'] != 'all')
		{
			$this->db->where('OwnerCode', $ds['emp_id']);
		}

		if(isset($ds['customer']) && $ds['customer'] != '')
		{
			$this->db
			->group_start()
			->like('CardCode', $ds['customer'])
			->or_like('CardName', $ds['customer'])
			->group_end();
		}


		if(isset($ds['sale_id']) && $ds['sale_id'] != 'all')
		{
			$this->db->where('SlpCode', $ds['sale_id']);
		}

		if(isset($ds['approval']) && $ds['approval'] != 'all')
		{
			$this->db->where('Approved', $ds['approval']);
		}

		if(isset($ds['review']) && $ds['review'] != 'all')
		{
			$this->db->where('Review', $ds['review']);
		}

		if(isset($ds['status']) && $ds['status'] != 'all')
		{
			$this->db->where('Status', $ds['status']);
		}

		return $this->db->count_all_results($this->tb);
	}


	public function get_quotation_address($code)
	{
		$rs = $this->db->where('quotation_code', $code)->get('quotation_address');

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function drop_address($code)
	{
		return $this->db->where('quotation_code', $code)->delete('quotation_address');
	}


	public function add_address(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert('quotation_address', $ds);
		}

		return FALSE;
	}

	public function get_logs($code)
	{
		$rs = $this->db->where('docType', 'SQ')->where('docNum', $code)->order_by('id', 'DESC')->get('access_logs');

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

	public function getSapDocNum($code)
	{
		$rs = $this->ms
		->select('DocNum')
		->where('U_WebNumber', $code)
		->where('CANCELED', 'N')
		->get('OQUT');

		if($rs->num_rows() > 0)
		{
			return $rs->row()->DocNum;
		}

		return NULL;
	}

	public function get_approve_list($minDisc, $maxDisc, $minAmount, $maxAmount, $ds = array())
	{
		$this->db
		->where('Status', 0)
		->where('must_approve', 1)
		->where_in('Review', array('A', 'S'))
		->where('Approved', 'P')
		->where('disc_diff >=', $minDisc, FALSE)
		->where('disc_diff <=', $maxDisc, FALSE)
		->where('DocTotal >=', $minAmount, FALSE)
		->where('DocTotal <=', $maxAmount, FALSE);

		if( ! empty($ds['code']))
		{
			$this->db->like('code', $ds['code']);
		}

		if( ! empty($ds['customer']))
		{
			$this->db->group_start()->like('CardCode', $ds['customer'])->or_like('CardName', $ds['customer'])->group_end();
		}

		if( ! empty($ds['project']))
		{
			$this->db->like('Project', $ds['project']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$this->db
			->where('DocDate >=', from_date($ds['from_date']))
			->where('DocDate <=', to_date($ds['to_date']));
		}

		$this->db->get($this->tb);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}

} //---- End class

 ?>
