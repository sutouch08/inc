<?php
class Orders_model extends CI_Model
{
	private $tb = "orders";
	private $td = "order_details";

  public function __construct()
  {
    parent::__construct();
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
		->where('order_code', $code)
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
		->where('order_code', $code)
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
		->where('order_code', $code)
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
		return $this->db->where('order_code', $code)->delete($this->td);
	}


	public function cancle_details($code)
	{
		return $this->db->set('LineStatus', 'D')->where('order_code', $code)->update($this->td);
	}


	public function cancle_order($code)
	{
		return $this->db->set('Status', 2)->where('code', $code)->update($this->tb);
	}


	public function close_details($code)
	{
		return $this->db->set('LineStatus', 'C')->where('order_code', $code)->update($this->td);
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
		->where('order_code', $code)
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

		if( isset($ds['doc_num']) && $ds['doc_num'] != '')
		{
			$this->db->like('DocNum', $ds['doc_num']);
		}

		if( isset($ds['SQNO']) && $ds['SQNO'] != '')
		{
			$this->db->like('SQNO', $ds['SQNO']);
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

		$rs = $this->db
		->order_by('DocDate', 'DESC')
		->order_by('code', 'DESC')
		->limit($perpage, $offset)
		->get($this->tb);

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

		if( isset($ds['doc_num']) && $ds['doc_num'] != '')
		{
			$this->db->like('DocNum', $ds['doc_num']);
		}

		if( isset($ds['SQNO']) && $ds['SQNO'] != '')
		{
			$this->db->like('SQNO', $ds['SQNO']);
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


	public function get_order_address($code)
	{
		$rs = $this->db->where('order_code', $code)->get('order_address');

		if($rs->num_rows() === 1)
		{
			return $rs->row();
		}

		return NULL;
	}


	public function drop_address($code)
	{
		return $this->db->where('order_code', $code)->delete('order_address');
	}


	public function add_address(array $ds = array())
	{
		if( ! empty($ds))
		{
			return $this->db->insert('order_address', $ds);
		}

		return FALSE;
	}

	public function get_logs($code)
	{
		$rs = $this->db
		->where('docType', 'SO')
		->where('docNum', $code)
		->order_by('id', 'DESC')
		->get('access_logs');

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
		->get('ORDR');

		if($rs->num_rows() > 0)
		{
			return $rs->row()->DocNum;
		}

		return NULL;
	}


	public function get_approval_list(array $ds = array(), $perpage = 20, $offset = 0)
	{
		$qr  = "SELECT * FROM {$this->tb} ";
		$qr .= "WHERE Status = 0 ";
		$qr .= "AND must_approve = 1 ";
		$qr .= "AND Review IN('A', 'S') ";
		$qr .= "AND Approved = 'P' ";
		$qr .= "AND ((disc_diff >= {$ds['minDisc']} AND disc_diff <= {$ds['maxDisc']}) OR (DocTotal >= {$ds['minAmount']} AND DocTotal <= {$ds['maxAmount']})) ";

		if( ! empty($ds['code']))
		{
			$qr .= "AND code LIKE '%{$ds['code']}%' ";
		}

		if( ! empty($ds['customer']))
		{
			$cust = $ds['customer'];
			$qr .= "AND (CardCode LIKE '%{$cust}%' OR CardName LIKE '%{$cust}%') ";
		}

		if( ! empty($ds['project']))
		{
			$qr .= "AND Project LIKE '%{$ds['project']}%' ";
			$this->db->like('Project', $ds['project']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$qr .= "AND DocDate >= '".from_date($ds['from_date'])."' ";
			$qr .= "AND DocDate <= '".to_date($ds['to_date'])."' ";
		}

		$rs = $this->db->query($qr);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function count_approval_rows(array $ds = array())
	{
		$qr  = "SELECT COUNT(*) AS num_rows FROM {$this->tb} ";
		$qr .= "WHERE Status = 0 ";
		$qr .= "AND must_approve = 1 ";
		$qr .= "AND Review IN('A', 'S') ";
		$qr .= "AND Approved = 'P' ";
		$qr .= "AND ((disc_diff >= {$ds['minDisc']} AND disc_diff <= {$ds['maxDisc']}) OR (DocTotal >= {$ds['minAmount']} AND DocTotal <= {$ds['maxAmount']})) ";

		if( ! empty($ds['code']))
		{
			$qr .= "AND code LIKE '%{$ds['code']}%' ";
		}

		if( ! empty($ds['customer']))
		{
			$cust = $ds['customer'];
			$qr .= "AND (CardCode LIKE '%{$cust}%' OR CardName LIKE '%{$cust}%') ";
		}

		if( ! empty($ds['project']))
		{
			$qr .= "AND Project LIKE '%{$ds['project']}%' ";
			$this->db->like('Project', $ds['project']);
		}

		if(isset($ds['from_date']) && isset($ds['to_date']) && $ds['from_date'] != '' && $ds['to_date'] != '' )
		{
			$qr .= "AND DocDate >= '".from_date($ds['from_date'])."' ";
			$qr .= "AND DocDate <= '".to_date($ds['to_date'])."' ";
		}

		$rs = $this->db->query($qr);

		return $rs->row()->num_rows;
	}


	public function get_quotation_list(array $ds = array(), $limit = 50)
	{
		$qr  = "SELECT DocEntry, DocNum, DocDate, CardCode, CardName, U_WEBORDER ";
		$qr .= "FROM OQUT WHERE DocStatus = 'O' AND CANCELED = 'N' ";

		if( ! empty($ds['SQNO']))
		{
			$qr .= "AND DocNum LIKE N'%{$ds['SQNO']}%' ";
		}

		if( ! empty($ds['CardCode']))
		{
			$qr .= "AND (CardCode LIKE N'%{$ds['CardCode']}%' OR CardName LIKE N'%{$ds['CardCode']}%') ";
		}

		if( ! empty($ds['fromDate']) && ! empty($ds['toDate']))
		{
			$qr .= "AND DocDate >= '".from_date($ds['fromDate'])."' ";
			$qr .= "AND DocDate <= '".to_date($ds['toDate'])."' ";
		}

		$qr .= "ORDER BY DocEntry DESC OFFSET 0 ROW FETCH FIRST {$limit} ROWS ONLY";

		$rs = $this->ms->query($qr);

		if($rs->num_rows() > 0)
		{
			return $rs->result();
		}

		return NULL;
	}


	public function get_sap_quotation_details(array $ds = array())
	{
		//--- ds = array of DocEntry
		if( ! empty($ds))
		{
			$rs = $this->ms
			->select('d.DocEntry, h.DocNum, d.LineNum, d.ItemCode, d.Dscription AS ItemName, d.Text AS Description')
			->select('d.OpenQty AS Qty, d.Price AS SellPrice, d.Currency, d.Rate, d.DiscPrcnt, d.PriceBefDi AS stdPrice, p.LstEvlPric AS Cost')
			->select('d.WhsCode, d.SlpCode, d.TreeType, d.BaseCard, d.VatGroup, d.VatPrcnt AS VatRate, d.UomEntry, d.UomCode')
			->from('QUT1 AS d')
			->join('OQUT AS h', 'd.DocEntry = h.DocEntry', 'left')
			->join('OITM AS p', 'd.ItemCode = p.ItemCode', 'left')
			->where_in('d.DocEntry', $ds)
			->where('d.LineStatus', 'O')
			->order_by('d.DocEntry', 'ASC')
			->order_by('d.LineNum', 'ASC')
			->get();

			if($rs->num_rows() > 0)
			{
				return $rs->result();
			}
		}

		return NULL;
	}

} //---- End class

 ?>
