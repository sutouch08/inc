<?php
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Products extends REST_Controller
{
    public $error;
		public $logs;
		public $log_json;

  public function __construct()
  {
    parent::__construct();
		$this->logs = $this->load->database('logs', TRUE);
		$this->load->model('masters/products_model');
		$this->load->model('rest/logs_model');
		$this->log_json = is_true(getConfig('LOGS_JSON'));
  }


	public function get_get($code = NULL)
	{
		if( ! empty($code))
		{
			$rs = $this->products_model->get($code);

			if( ! empty($rs))
			{
				$result = array(
					'ItemCode' => $rs->code,
					'ItemName' => $rs->name,
					'CodeBars' => $rs->barcode,
					'SUoMEntry' => $rs->uom_id,
					'Price' => $rs->price,
					'Cost' => $rs->cost,
					'VatGourpSa' => $rs->vat_group,
					'validFor' => $rs->status == 1 ? 'Y' : 'N',
					'ModelCode' => $rs->model_id,
					'CategoryCode' => $rs->category_id,
					'BrandCode' => $rs->brand_id,
					'TypeCode' => $rs->type_id,
					'DateCreate' => $rs->date_add,
					'DateUpdate' => $rs->date_upd,
					"LastSync" => $rs->last_sync,
					"Cover" => $rs->is_cover == 1 ? 'Y' : 'N'
				);

				$this->response($result, 200);
			}
			else
			{
				$result = array(
					'status' => FALSE,
					'error' => 'No data found'
				);

				$this->response($result, 400);
			}
		}
		else
		{
			$ds = $this->products_model->get_all();

			if( ! empty($ds))
			{
				$result = array();

				foreach($ds as $rs)
				{
					$arr = array(
						'ItemCode' => $rs->code,
						'ItemName' => $rs->name,
						'CodeBars' => $rs->barcode,
						'SUoMEntry' => $rs->uom_id,
						'Price' => $rs->price,
						'Cost' => $rs->cost,
						'VatGourpSa' => $rs->vat_group,
						'validFor' => $rs->status == 1 ? 'Y' : 'N',
						'ModelCode' => $rs->model_id,
						'CategoryCode' => $rs->category_id,
						'BrandCode' => $rs->brand_id,
						'TypeCode' => $rs->type_id,
						'DateCreate' => $rs->date_add,
						'DateUpdate' => $rs->date_upd,
						"LastSync" => $rs->last_sync,
						"Cover" => $rs->is_cover == 1 ? 'Y' : 'N'
					);

					array_push($result, $arr);
				}

				$this->response($result, 200);
			}
			else
			{
				$result = array(
					'status' => FALSE,
					'error' => 'No data found'
				);

				$this->response($result, 400);
			}
		}



	}




  public function create_post()
  {
    $sc = TRUE;
		$result = array();

    $json = file_get_contents("php://input");

	  $ds = json_decode($json);

	  if(empty($ds))
	  {
		  $sc = FALSE;
		  $this->error = "Empty data";
	  }

		if($sc === TRUE && (empty($ds->ItemCode) OR empty($ds->ItemName)))
		{
			$sc = FALSE;
			$this->error = "Missing required parameter : ItemCode or ItemName";
		}


	  if($sc === TRUE)
	  {
		  $is_exists = $this->products_model->is_exists($ds->ItemCode);

		  if(! $is_exists)
		  {
				$arr = array(
					'code' => $ds->ItemCode,
					'name' => $ds->ItemName,
					'barcode' => empty($ds->CodeBars) ? NULL : get_null($ds->CodeBars),
					'uom_id' => empty($ds->SUoMEntry) ? NULL : get_null($ds->SUoMEntry),
					'price' => empty($ds->Price) ? 0.00 : get_zero($ds->Price),
					'cost' => empty($ds->Cost) ? 0.00 : get_zero($ds->Cost),
					'vat_group' => empty($ds->VatGourpSa) ? NULL : get_null($ds->VatGourpSa),
					'model_id' => empty($ds->ModelCode) ? NULL : get_null($ds->ModelCode),
					'category_id' => empty($ds->CategoryCode) ? NULL : get_null($ds->CategoryCode),
					'brand_id' => empty($ds->BrandCode) ? NULL : get_null($ds->BrandCode),
					'type_id' => empty($ds->TypeCode) ? NULL : get_null($ds->TypeCode),
					'status' => empty($ds->validFor) ? 1 : ($ds->validFor == 'Y' ? 1 : 0)
				);

				if(! $this->products_model->add($arr))
				{
					$sc = FALSE;
					set_error('insert');
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "{$ds->ItemCode} already exists";
		  }
	  }


		if($sc === TRUE)
		{
			$logs = array(
				'action' => 'create',
				'status' => 'success',
				'message' => NULL,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_products($logs);


			$result = array(
				'status' => 'success'
			);

			$this->response($result, 200);
		}
		else
		{
			$logs = array(
				'action' => 'create',
				'status' => 'error',
				'message' => $this->error,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_products($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }



	public function update_post($code = NULL)
  {
    $sc = TRUE;
		$result = array();

    $json = file_get_contents("php://input");

	  $ds = json_decode($json);

	  if(empty($ds))
	  {
		  $sc = FALSE;
		  $this->error = "Empty data";
	  }


		if($sc === TRUE && (empty($ds->ItemCode) OR empty($ds->ItemName)))
		{
			$sc = FALSE;
			$this->error = "Missing required parameter : ItemCode or ItemName";
		}


	  if($sc === TRUE)
	  {
		  $is_exists = $this->products_model->is_exists($ds->ItemCode);

		  if($is_exists)
		  {
				$arr = array(
					'name' => $ds->ItemName,
					'barcode' => empty($ds->CodeBars) ? NULL : get_null($ds->CodeBars),
					'uom_id' => empty($ds->SUoMEntry) ? NULL : get_null($ds->SUoMEntry),
					'price' => empty($ds->Price) ? 0.00 : get_zero($ds->Price),
					'cost' => empty($ds->Cost) ? 0.00 : get_zero($ds->Cost),
					'vat_group' => empty($ds->VatGourpSa) ? NULL : get_null($ds->VatGourpSa),
					'model_id' => empty($ds->ModelCode) ? NULL : get_null($ds->ModelCode),
					'category_id' => empty($ds->CategoryCode) ? NULL : get_null($ds->CategoryCode),
					'brand_id' => empty($ds->BrandCode) ? NULL : get_null($ds->BrandCode),
					'type_id' => empty($ds->TypeCode) ? NULL : get_null($ds->TypeCode),
					'status' => empty($ds->validFor) ? 1 : ($ds->validFor == 'Y' ? 1 : 0)
				);

				if(! $this->products_model->update($ds->ItemCode, $arr))
				{
					$sc = FALSE;
					set_error('update');
				}
		  }
		  else
		  {
				$sc = FALSE;
				$this->error = "{$ds->ItemCode} not exists";
		  }
	  }


		if($sc === TRUE)
		{
			$logs = array(
				'action' => 'update',
				'status' => 'success',
				'message' => NULL,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_products($logs);


			$result = array(
				'status' => 'success'
			);

			$this->response($result, 200);
		}
		else
		{
			$logs = array(
				'action' => 'update',
				'status' => 'error',
				'message' => $this->error,
				'json' => $this->log_json ? $json : NULL
			);

			$this->logs_model->logs_products($logs);

			$result = array(
				'status' => FALSE,
				'error' => $this->error
			);

			$this->response($result, 400);
		}

  }


} //--- end class
