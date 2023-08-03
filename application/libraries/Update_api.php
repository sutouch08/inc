<?php
class Update_api
{
  private $url;
  protected $ci;
	protected $logs;
	public $error;

  public function __construct()
  {
		$this->ci =& get_instance();

    $this->url = getConfig('SAP_API_HOST');
		if($this->url[-1] != '/')
		{
			$this->url .'/';
		}

		$this->ci->load->model('rest/logs_model');
  }



	public function updateProduct($arr)
	{
		$url = $this->url .'Products';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}


  public function updateProductModel($arr)
	{
		$url = $this->url .'ProductModel';
		$curl = curl_init();
		$ds = array(
			'code' => "model",
			"status" => 'success',
			"json" => json_encode($arr)
		);
		$this->ci->logs_model->order_logs($ds);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}


  public function updateProductType($arr)
	{
		$url = $this->url .'ProductType';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}

	public function createProductCategory($arr)
	{
		$url = $this->url .'ProductCategory';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs))
		{
			if($rs->status == 'success')
			{
				return TRUE;
			}
			else
			{
				$this->error = $rs->error;
			}
		}
    else
    {
      $this->error = "Update Interface failed : {$response}";
    }

    return FALSE;
	}


  public function updateProductCategory($arr)
	{
		$url = $this->url .'ProductCategory';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs))
		{
			if($rs->status == 'success')
			{
				return TRUE;
			}
			else
			{
				$this->error = $rs->error;
			}
		}
    else
    {
      $this->error = "Update Interface failed : {$response}";
    }

    return FALSE;
	}


  public function updateProductBrand($arr)
	{
		$url = $this->url .'ProductBrand';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}


	public function updateCustomers($arr)
	{
		$url = $this->url .'Customer';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}


	public function updateCustomerArea($arr)
	{
		$url = $this->url .'CustomerArea';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}


	public function updateCustomerGrade($arr)
	{
		$url = $this->url .'CustomerGrade';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}

	public function updateCustomerRegion($arr)
	{
		$url = $this->url .'CustomerRegion';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}

	public function updateCustomerType($arr)
	{
		$url = $this->url .'CustomerType';
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arr));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		$response = curl_exec($curl);
		curl_close($curl);
		$rs = json_decode($response);

		if(! empty($rs) && $rs->status == 'success') {

			return TRUE;
		}
    else
    {
      $this->error = $rs->error;
    }

    return FALSE;
	}

} //-- end class

 ?>
