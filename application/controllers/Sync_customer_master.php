<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_customer_master extends CI_Controller
{
  public $title = 'Sync customer master';
	public $menu_code = '';
	public $menu_group_code = '';
	public $pm;
  public $limit = 200;
  public $date;

  public function __construct()
  {
    parent::__construct();
    $this->date = date('Y-d-m H:i:s');
		$this->load->library('api');
		$this->load->model('sync_logs_model');
  }


  public function index()
  {

		$this->syncCustomerGroup();

		$this->syncCustomerType();

		$this->syncCustomerRegion();

		$this->syncCustomerArea();

		$this->syncCustomerGrade();

		$this->syncCustomers();

		$this->syncCustomerAddress();

  }


	public function syncCustomerGroup()
	{
		$this->load->model('masters/customer_group_model');

		$res = $this->api->getCustomerGroupUpdateData();

		$sc = TRUE;

		$i = 0;

		$message = "";

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->customer_group_model->get($rs->GroupCode);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->GroupCode,
						"name" => $rs->GroupName,
						"type" => $rs->GroupType,
						"last_sync" => now()
					);

					if( ! $this->customer_group_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->GroupCode}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->GroupName,
						"type" => $rs->GroupType,
						"last_sync" => now()
					);

					if( ! $this->customer_group_model->update($rs->GroupCode, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->GroupCode}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'CustomerGroup',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}



	public function syncCustomerType()
	{
		$this->load->model('masters/customer_type_model');

		$res = $this->api->getCustomerTypeUpdateData();

		$sc = TRUE;

		$i = 0;

		$message = "";


		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->customer_type_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_type_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->name}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_type_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->name}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'CustomerType',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}



	public function syncCustomerRegion()
	{
		$this->load->model('masters/customer_region_model');

		$res = $this->api->getCustomerRegionUpdateData();

		$sc = TRUE;

		$i = 0;

		$message = "";


		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->customer_region_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_region_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->name}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_region_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->name}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'CustomerRegion',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}


	public function syncCustomerArea()
	{
		$this->load->model('masters/customer_area_model');

		$res = $this->api->getCustomerAreaUpdateData();

		$sc = TRUE;

		$i = 0;

		$message = "";

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->customer_area_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_area_model->add($arr))
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->name}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_area_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->name}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'CustomerArea',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}


	public function syncCustomerGrade()
	{
		$this->load->model('masters/customer_grade_model');

		$res = $this->api->getCustomerGradeUpdateData();

		$sc = TRUE;

		$i = 0;

		$message = "";

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				$cr = $this->customer_grade_model->get_by_code($rs->id);

				if(empty($cr))
				{
					$arr = array(
						"code" => $rs->id,
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_grade_model->add($arr) )
					{
						$sc = FALSE;
						$message .= " Insert failed : {$rs->name}";
					}
				}
				else
				{
					$arr = array(
						"name" => $rs->name,
						"last_sync" => now()
					);

					if( ! $this->customer_grade_model->update($cr->id, $arr))
					{
						$sc = FALSE;
						$message .= " Update failed : {$rs->name}";
					}
				}

				$i++;
			}
		}

		$arr = array(
			'type' => 'CustomerGrade',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $i,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}



	public function syncCustomers()
	{
		$this->load->model('masters/customers_model');

		$last_sync = $this->customers_model->get_last_sync_date();

		$count = $this->api->countUpdateCustomer($last_sync);

		$sc = TRUE;

		$message = "";

		$limit = 100;
		$offset = 0;
		$update = 0;

		if($count)
		{
			$total = $count;

			while($total > $update)
			{

				$ds = $this->api->getCustomerUpdateData($last_sync, $limit, $offset);

				if(! empty($ds))
				{
					foreach($ds as $rs)
					{
						$cs = $this->customers_model->get($rs->CardCode);

						if(empty($cs))
						{
							$arr = array(
								'CardCode' => $rs->CardCode,
								'CardName' => $rs->CardName,
								'LicTradNum' => get_null($rs->LicTradNum),
								'CardType' => $rs->CardType,
								'CmpPrivate' => $rs->CmpPrivate,
								'GroupCode' => get_null($rs->GroupCode),
								'GroupNum' => get_null($rs->GroupNum),
								'ListNum' => empty($rs->ListNum) ? NULL : $rs->ListNum,
								'SlpCode' => get_null($rs->SlpCode),
								'RegionCode' => get_null($rs->RegionCode),
								'AreaCode' => get_null($rs->AreaCode),
								'TypeCode' => get_null($rs->TypeCode),
								'GradeCode' => get_null($rs->GradeCode),
								'SaleTeam' => get_null($rs->Sales_Team),
								'SaleTeamName' => get_null($rs->Sales_Team_Name),
								'CreditLine' => $rs->CreditLine,
								'Status' => $rs->validFor == 'Y' ? 1 : 0,
								'last_sync' => now()
							);

							if( ! $this->customers_model->add($arr))
							{
								$sc = FALSE;
								$message .= "{$rs->CardCode},";
							}
						}
						else
						{
							$arr = array(
								'CardName' => $rs->CardName,
								'LicTradNum' => get_null($rs->LicTradNum),
								'CardType' => $rs->CardType,
								'CmpPrivate' => $rs->CmpPrivate,
								'GroupCode' => get_null($rs->GroupCode),
								'GroupNum' => get_null($rs->GroupNum),
								'ListNum' => empty($rs->ListNum) ? NULL : $rs->ListNum,
								'SlpCode' => get_null($rs->SlpCode),
								'RegionCode' => get_null($rs->RegionCode),
								'AreaCode' => get_null($rs->AreaCode),
								'TypeCode' => get_null($rs->TypeCode),
								'GradeCode' => get_null($rs->GradeCode),
								'SaleTeam' => get_null($rs->Sales_Team),
								'SaleTeamName' => get_null($rs->Sales_Team_Name),
								'CreditLine' => $rs->CreditLine,
								'Status' => $rs->validFor == 'Y' ? 1 : 0,
								'last_sync' => now()
							);

							if(! $this->customers_model->update($rs->CardCode, $arr))
							{
								$sc = FALSE;
								$message .= "{$rs->CardCode},";
							}
						}

						$update++;
						$offset++;
					}
				}
			}
		}

		$arr = array(
			'type' => 'Customers',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $update,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}



	public function syncCustomerAddress()
	{
		$this->load->model('masters/customer_address_model');

		$last_sync = $this->customer_address_model->get_last_sync_date();

		$count = $this->api->countUpdateAddress($last_sync);

		$sc = TRUE;

		$message = "";

		$limit = 100;
		$offset = 0;
		$update = 0;

		if($count)
		{
			$total = $count;

			while($total > $update)
			{

				$ds = $this->api->getUpdateAddress($last_sync, $limit, $offset);

				if(! empty($ds))
				{
					foreach($ds as $rs)
					{
						$cr = $this->customer_address_model->get($rs->CardCode, $rs->AddressType, $rs->Address);

						if(empty($cr))
						{
							$arr = array(
								'Address' => $rs->Address,
								'CardCode' => $rs->CardCode,
								'AdresType' => $rs->AddressType,
								'Address2' => $rs->Address2,
								'Address3' => $rs->Address3,
								'Street' => $rs->Street,
								'Block' => $rs->Block,
								'City' => $rs->City,
								'County' => $rs->County,
								'Country' => $rs->Country,
								'ZipCode' => $rs->ZipCode,
								'last_sync' => now()
							);

							if( ! $this->customer_address_model->add($arr))
							{
								$sc = FALSE;
								$message .= "{$rs->CardCode}, ";
							}
						}
						else
						{
							$arr = array(
								'Address' => $rs->Address,
								'CardCode' => $rs->CardCode,
								'AdresType' => $rs->AddressType,
								'Address2' => $rs->Address2,
								'Address3' => $rs->Address3,
								'Street' => $rs->Street,
								'Block' => $rs->Block,
								'City' => $rs->City,
								'County' => $rs->County,
								'Country' => $rs->Country,
								'ZipCode' => $rs->ZipCode,
								'last_sync' => now()
							);

							if( ! $this->customer_address_model->update($cr->id, $arr))
							{
								$sc = FALSE;
								$message .= "{$rs->CardCode}, ";
							}
						}

						$update++;
						$offset++;
					}
				}
			}
		}

		$arr = array(
			'type' => 'Address',
			'status' => $sc === TRUE ? 'S' : 'E',
			'qty' => $update,
			'message' => $sc === FALSE ? $message : NULL
		);

		$this->sync_logs_model->add_logs($arr);
	}

} //--- end class

 ?>
