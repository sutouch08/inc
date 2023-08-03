<?php
class Zone extends PS_Controller
{
	public $menu_code = 'DBOBIN';
	public $menu_group_code = 'DB';
	public $title = 'Bin Locations';
	public $segment = 4;

	public function __construct()
	{
		parent::__construct();
		$this->home = base_url().'masters/zone';
		$this->load->model('masters/zone_model');
		$this->load->model('masters/warehouse_model');
		$this->load->helper('warehouse');
	}


	public function index()
	{
		$filter = array(
			'code' => get_filter('code', 'bin_code', ''),
			'name' => get_filter('name', 'bin_name', ''),
			'warehouse' => get_filter('warehouse', 'bin_warehouse', 'all')
		);

		$perpage = get_rows();

		$rows = $this->zone_model->count_rows($filter);

		$filter['data'] = $this->zone_model->get_list($filter, $perpage, $this->uri->segment($this->segment));

		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);

		$this->pagination->initialize($init);

		$this->load->view('masters/zone/zone_list', $filter);
	}


	public function sync_data()
	{
		$sc = TRUE;

		$response = json_encode(array(
			array("AbsEntry" => 1, "BinCode" => "AFG-0000-SYSTEM-BIN-LOCATION", "WhsCode" => "AFG-0000", "Descr" => NULL, "SysBin" => "Y"),
			array("AbsEntry" => 2, "BinCode" => "AFG-0000-A001", "WhsCode" => "AFG-0000", "Descr" => "A001", "SysBin" => "N"),
			array("AbsEntry" => 3, "BinCode" => "AFG-0000-A002", "WhsCode" => "AFG-0000", "Descr" => "A002", "SysBin" => "N"),
			array("AbsEntry" => 4, "BinCode" => "AFG-0000-A003", "WhsCode" => "AFG-0000", "Descr" => "A003", "SysBin" => "N"),
			array("AbsEntry" => 5, "BinCode" => "AFG-0001-B001", "WhsCode" => "AFG-0001", "Descr" => "B001", "SysBin" => "N"),
			array("AbsEntry" => 6, "BinCode" => "AFG-0001-B002", "WhsCode" => "AFG-0001", "Descr" => "B002", "SysBin" => "N"),
			array("AbsEntry" => 7, "BinCode" => "AFG-0001-B003", "WhsCode" => "AFG-0001", "Descr" => "B003", "SysBin" => "N")
		));

		$res = json_decode($response);

		if( ! empty($res))
		{
			foreach($res as $rs)
			{
				$bin = $this->zone_model->get($rs->AbsEntry);

				if(empty($bin))
				{
					if( ! $this->zone_model->is_exists_code($rs->BinCode))
					{
						$whs = $this->warehouse_model->get($rs->WhsCode);

						if(! empty($whs))
						{
							$arr = array(
								"id" => $rs->AbsEntry,
								"code" => $rs->BinCode,
								"name" => $rs->Descr,
								"warehouse_id" => $whs->id,
								"warehouse_code" => $rs->WhsCode,
								"sysBin" => $rs->SysBin == 'Y' ? 1 : 0,
								"last_sync" => now()
							);

							$this->zone_model->add($arr);
						}
					}
				}
				else
				{
					$whs = $this->warehouse_model->get($rs->WhsCode);

					if(! empty($whs))
					{
						$arr = array(
							"code" => $rs->BinCode,
							"name" => $rs->Descr,
							"warehouse_id" => $whs->id,
							"warehouse_code" => $rs->WhsCode,
							"sysBin" => $rs->SysBin == 'Y' ? 1 : 0,
							"last_sync" => now()
						);

						$this->zone_model->update($rs->AbsEntry, $arr);
					}
				}
			}
		}

		$this->_response($sc);
	}


	public function clear_filter()
	{
		return clear_filter(array("bin_code", "bin_name", "bin_warehouse"));
	}


} //-- end class
 ?>
