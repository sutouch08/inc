<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends PS_Controller
{
  public $menu_code = 'DBPROD';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'Products';
	public $segment = 4;

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/products';

    //--- load model
    $this->load->model('masters/products_model');

    //---- load helper
    $this->load->helper('products');
    $this->load->helper('product_images');

  }


  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'item_code', ''),
      'name' => get_filter('name', 'item_name', ''),
			'model' => get_filter('model', 'item_model', ''),
      'category' => get_filter('category', 'item_category', 'all'),
      'type' => get_filter('type', 'item_type', 'all'),
      'brand' => get_filter('brand', 'item_brand', 'all'),
			'status' => get_filter('status', 'item_status', 'all'),
			'count_stock' => get_filter('count_stock', 'count_stock', 'all'),
			'allow_change_discount' => get_filter('allow_change_discount', 'allow_change_discount', 'all'),
			'customer_view' => get_filter('customer_view', 'customer_view', 'all')
    );

		$perpage = get_rows();

		$rows = $this->products_model->count_rows($filter);
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
		$filter['data'] = $this->products_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
		$this->pagination->initialize($init);
    $this->load->view('masters/products/products_list', $filter);
  }



	public function edit($id)
	{
		if($this->pm->can_edit)
		{
			$rs = $this->products_model->get_by_id($id);

			if( ! empty($rs))
			{
				$this->load->view('masters/products/products_edit', $rs);
			}
			else
			{
				$this->page_error();
			}
		}
		else
		{
			$this->permission_page();
		}
	}



	public function update()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			if($this->input->post('id'))
			{
				$id = $this->input->post('id');

				$arr = array(
					'model_code' => get_null($this->input->post('model')),
					'brand_code' => get_null($this->input->post('brand')),
					'category_code' => get_null($this->input->post('category')),
					'type_code' => get_null($this->input->post('type')),
					'category_code_1' => get_null($this->input->post('cateCode1')),
					'category_code_2' => get_null($this->input->post('cateCode2')),
					'category_code_3' => get_null($this->input->post('cateCode3')),
					'category_code_4' => get_null($this->input->post('cateCode4')),
					'category_code_5' => get_null($this->input->post('category')),
					'is_cover' => $this->input->post('cover') == 1 ? 1 : 0
				);

				if( ! $this->products_model->update_by_id($id, $arr))
				{
					$sc = FALSE;
					set_error('update');
				}
				else
				{
					$this->update_sap($id);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}


	public function update_sap($id)
	{
		$pd = $this->products_model->get_by_id($id);

		if(!empty($pd))
		{
			$this->load->library('update_api');
			$arr = array(
				'ItemCode' => $pd->code,
				'ItemName' => $pd->name,
				'CodeBars' => $pd->barcode,
				'SUoMEntry' => $pd->uom_id,
				'Price' => $pd->price,
				'Cost' => $pd->cost,
				'VatGourpSa' => $pd->vat_group,
				'validFor' => $pd->status == 1 ? 'Y' : 'N',
				'Product_ModelCode' => $pd->model_code,
				'Product_CategoryCode' => $pd->category_code,
				'Product_BrandCode' => $pd->brand_code,
				'Product_TypeCode' => $pd->type_code,
				'CategoryCode1' => $pd->category_code_1,
				'CategoryCode2' => $pd->category_code_2,
				'CategoryCode3' => $pd->category_code_3,
				'CategoryCode4' => $pd->category_code_4,
				'CategoryCode5' => $pd->category_code_5
			);

			return $this->update_api->updateProduct($arr);
		}

		return FALSE;
	}



	public function view_detail($id)
	{
		$rs = $this->products_model->get_by_id($id);

		if( ! empty($rs))
		{
			$this->load->view('masters/products/products_detail', $rs);
		}
		else
		{
			$this->page_error();
		}
	}


	public function search_model()
	{
		$sc = array();

		$txt = $_REQUEST['term'];

		$query = "SELECT * FROM product_model WHERE id IS NOT NULL ";
		if($txt != "*")
		{
			$query .= "AND name LIKE '%{$txt}' ";
		}

		$query .= "ORDER BY name ASC LIMIT 50";

		$rs = $this->db->query($query);

		if($rs->num_rows() > 0)
		{
			foreach($rs->result() as $rd)
			{
				$sc[] = $rd->name.' | '.$rd->id;
			}
		}
		else
		{
			$sc[] = "not found";
		}

		echo json_encode($sc);
	}



	public function change_image()
	{
		$sc = TRUE;

		if($this->input->post('id') && $this->input->post('code'))
		{
			$file = isset( $_FILES['image'] ) ? $_FILES['image'] : FALSE;
			$id = $this->input->post('id');
			$code = $this->input->post('code'); //--- item code

			if($file !== FALSE)
			{
				;
				if(! $this->do_upload($file, $id, $code))
				{
					$sc = FALSE;
				}
			}
			else
			{
				$sc = FALSE;
				$this->error = "File not found";
			}
		}
		else
		{
			$sc = FALSE;
			set_error('required');
		}

		$this->_response($sc);
	}


	public function do_upload($file, $product_id)
	{
		$sc = TRUE;
    $this->load->library('upload');

		$img_name 	= $product_id; //-- ตั้งชื่อรูปตาม id_product
		$image_path = $this->config->item('image_path').'products/';
		$use_size 	= array('mini', 'default', 'medium', 'large'); //---- ใช้ทั้งหมด 4 ขนาด
    $image 	= new Upload($file);

    if( $image->uploaded )
    {
      foreach($use_size as $size)
      {
				$imagePath = $image_path.$size.'/'; //--- แต่ละ folder
        $img	= $this->getImageSizeProperties($size); //--- ได้ $img['prefix'] , $img['size'] กลับมา
        $image->file_new_name_body = $img['prefix'] . $img_name; 		//--- เปลี่ยนชือ่ไฟล์ตาม prefix + id_image
        $image->image_resize			 = TRUE;		//--- อนุญาติให้ปรับขนาด
        $image->image_retio_fill	 = TRUE;		//--- เติกสีให้เต็มขนาดหากรูปภาพไม่ได้สัดส่วน
        $image->file_overwrite		 = TRUE;		//--- เขียนทับไฟล์เดิมได้เลย
        $image->auto_create_dir		 = TRUE;		//--- สร้างโฟลเดอร์อัตโนมัติ กรณีที่ไม่มีโฟลเดอร์
        $image->image_x					   = $img['size'];		//--- ปรับขนาดแนวตั้ง
        $image->image_y					   = $img['size'];		//--- ปรับขนาดแนวนอน
        $image->image_background_color	= "#FFFFFF";		//---  เติมสีให้ตามี่กำหนดหากรูปภาพไม่ได้สัดส่วน
        $image->image_convert			= 'jpg';		//--- แปลงไฟล์

        $image->process($imagePath);						//--- ดำเนินการตามที่ได้ตั้งค่าไว้ข้างบน

				if( ! $image->processed )	//--- ถ้าไม่สำเร็จ
				{
					$sc = FALSE;
					$this->error = $image->error;
				}
      } //--- end foreach
    } //--- end if

    $image->clean();	//--- เคลียร์รูปภาพออกจากหน่วยความจำ

		return $sc;
	}


	public function getImageSizeProperties($size)
	{
		$sc = array();
		switch($size)
		{
			case "mini" :
			$sc['prefix']	= "product_mini_";
			$sc['size'] 	= 60;
			break;
			case "default" :
			$sc['prefix'] 	= "product_default_";
			$sc['size'] 	= 125;
			break;
			case "medium" :
			$sc['prefix'] 	= "product_medium_";
			$sc['size'] 	= 250;
			break;
			case "large" :
			$sc['prefix'] 	= "product_large_";
			$sc['size'] 	= 1500;
			break;
			default :
			$sc['prefix'] 	= "";
			$sc['size'] 	= 300;
			break;
		}//--- end switch
		return $sc;
	}


	public function delete_image($product_id)
	{
		$sc = TRUE;

		if(!empty($product_id))
		{
			if(! delete_product_image($product_id))
			{

				$sc = FALSE;
				$this->error = "Delete image failed";
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "ไม่พบ id image";
		}

		$this->_response($sc);
	}


	public function get_category_parent_list()
	{
		$code = $this->input->get('code');
		$this->load->model('masters/product_category_model');

		$list = $this->product_category_model->get_parent_list($code);

		if(!empty($list))
		{
			echo json_encode($list);
		}
		else
		{
			echo "no parent";
		}
	}


	public function get_last_sync_date()
	{
		$date = $this->products_model->get_last_sync_date();

		echo $date;
	}

	public function count_update_rows()
	{
		$date = $this->input->get('last_sync_date');

		$this->load->library('api');

		echo $this->api->countUpdateProduct($date);
	}


  public function sync_data()
	{
		$this->load->library('api');

		$sc = TRUE;

		$last_sync = $this->input->get('last_sync');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');

		$i = 0;

		$res = $this->api->getUpdateProduct($last_sync, $limit, $offset);

		if(! empty($res))
		{
			foreach($res as $rs)
			{
				if(isset($rs->ItemCode) && $rs->ItemCode != "" && isset($rs->ItemName))
				{
					$arr = array(
						"code" => $rs->ItemCode,
						"name" => $rs->ItemName,
						"barcode" => empty($rs->CodeBars) ? NULL : $rs->CodeBars,
						"uom_id" => empty($rs->SUoMEntry) ? NULL : $rs->SUoMEntry,
						"price" => empty($rs->Price) ? 0.00 : get_zero($rs->Price),
						"cost" => empty($rs->Cost) ? 0.00 : get_zero($rs->Cost),
						"vat_group" => get_null($rs->VatGourpSa),
						"model_code" => empty($rs->Product_ModelCode) ? NULL : $rs->Product_ModelCode,
						"brand_code" => empty($rs->Product_BrandCode) ? NULL : $rs->Product_BrandCode,
						"type_code" => empty($rs->Product_TypeCode) ? NULL : $rs->Product_TypeCode,
						"category_code" => empty($rs->Product_CategoryCode) ? NULL : $rs->Product_CategoryCode,
						"status" => empty($rs->validFor) ? 1 : ($rs->validFor == 'N' ? 0 : 1),
						"last_sync" => now()
					);

					if( ! $this->products_model->is_exists($rs->ItemCode))
					{
						if($this->products_model->add($arr))
						{
							if(!empty($rs->Product_CategoryCode))
							{
								$this->update_item_parent_category($rs->ItemCode);
							}
						}
					}
					else
					{
						if($this->products_model->update($rs->ItemCode, $arr))
						{
							if(!empty($rs->Product_CategoryCode))
							{
								$this->update_item_parent_category($rs->ItemCode);
							}
						}
					}

					$i++;
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "no data found";
		}

		echo $sc === TRUE ? $i : $this->error;
	}



	public function update_parent_category()
	{
		$this->load->model('masters/product_category_model');

		$qs = $this->db->distinct()->select('category_code')->where('category_code IS NOT NULL', NULL, FALSE)->get('products');

		if($qs->num_rows() > 0)
		{
			foreach($qs->result() as $rs)
			{
				$list = $this->product_category_model->get_parent_list($rs->category_code);

				if( ! empty($list))
				{
					$arr = array(
						"category_code_1" => $list->l1,
						"category_code_2" => $list->l2,
						"category_code_3" => $list->l3,
						"category_code_4" => $list->l4,
						"category_code_5" => $list->l5
					);

					$this->db->where('category_code', $rs->category_code)->update('products', $arr);
				}
			}
		}
	}


	public function update_item_parent_category($code)
	{
		$this->load->model('masters/product_category_model');

		$rs = $this->db->distinct()->select('category_code')->where('code', $code)->get('products');

		if($rs->num_rows() == 1)
		{
			$list = $this->product_category_model->get_parent_list($rs->row()->category_code);

			if( ! empty($list))
			{
				$arr = array(
					"category_code_1" => $list->l1,
					"category_code_2" => $list->l2,
					"category_code_3" => $list->l3,
					"category_code_4" => $list->l4,
					"category_code_5" => $list->l5
				);

				$this->db->where('code', $code)->update('products', $arr);
			}
		}
	}


	public function set_count_stock()
	{
		$id = $this->input->get('id');
		$count_stock = $this->input->get('count_stock');

		$arr = array(
			'count_stock' => $count_stock == 1 ? 1 : 0
		);

		$this->products_model->update_by_id($id, $arr);
	}


	public function set_allow_change_discount()
	{
		$id = $this->input->get('id');
		$allow_change_discount = $this->input->get('allow_change_discount');

		$arr = array(
			'allow_change_discount' => $allow_change_discount == 1 ? 1 : 0
		);

		$this->products_model->update_by_id($id, $arr);
	}


	public function set_customer_view()
	{
		$id = $this->input->get('id');
		$customer_view = $this->input->get('customer_view');

		$arr = array(
			'customer_view' => $customer_view == 1 ? 1 : 0
		);

		$this->products_model->update_by_id($id, $arr);
	}



  public function sync_item()
	{
		$this->load->library('api');

		$sc = TRUE;

		$itemCode = $this->input->get('code');

    $rs = $this->api->getItem($itemCode);

		if(! empty($rs))
		{
      if(isset($rs->ItemCode) && $rs->ItemCode != "" && isset($rs->ItemName))
      {
        $arr = array(
          "code" => $rs->ItemCode,
          "name" => $rs->ItemName,
          "barcode" => empty($rs->CodeBars) ? NULL : $rs->CodeBars,
          "uom_id" => empty($rs->SUoMEntry) ? NULL : $rs->SUoMEntry,
          "price" => empty($rs->Price) ? 0.00 : get_zero($rs->Price),
          "cost" => empty($rs->Cost) ? 0.00 : get_zero($rs->Cost),
          "vat_group" => get_null($rs->VatGourpSa),
          "model_code" => empty($rs->Product_ModelCode) ? NULL : $rs->Product_ModelCode,
          "brand_code" => empty($rs->Product_BrandCode) ? NULL : $rs->Product_BrandCode,
          "type_code" => empty($rs->Product_TypeCode) ? NULL : $rs->Product_TypeCode,
          "category_code" => empty($rs->Product_CategoryCode) ? NULL : $rs->Product_CategoryCode,
          "status" => empty($rs->validFor) ? 1 : ($rs->validFor == 'N' ? 0 : 1)
        );

        if( ! $this->products_model->is_exists($rs->ItemCode))
        {
          if($this->products_model->add($arr))
          {
            if(!empty($rs->Product_CategoryCode))
            {
              $this->update_item_parent_category($rs->ItemCode);
            }
          }
        }
        else
        {
          if($this->products_model->update($rs->ItemCode, $arr))
          {
            if(!empty($rs->Product_CategoryCode))
            {
              $this->update_item_parent_category($rs->ItemCode);
            }
          }
        }
      }
		}
		else
		{
			$sc = FALSE;
			$this->error = "no data found";
		}

		$this->_response($sc);
	}



  public function clear_filter()
	{
    $filter = array(
			'item_code',
			'item_name',
			'item_model',
			'item_type',
			'item_category',
			'item_brand',
			'item_status',
			'count_stock',
			'customer_view',
			'allow_change_discount'
		);

    clear_filter($filter);
	}
} //--- end class

?>
