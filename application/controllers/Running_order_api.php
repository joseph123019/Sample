<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
if(!class_exists("REST_Controller"))
{
	require(APPPATH . 'libraries/REST_Controller.php');
}
class Running_order_api extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Manage_store');
		$this->load->model('Order_model');
	}

	function cons_get()
	{
		$client_id = 1;
		$store_id = 1;
		$table = $this->session->userdata ('table');

		$data['items'] = $this->Order_model->get_running_items($client_id, $store_id);
		$data['categories'] = $this->Order_model->get_running_categories($store_id);
		$data['tables'] = $this->Order_model->get_running_tables($store_id);
		$data['store_id'] = $store_id;

		if(isset($data['tables']) && !isset($table))
		{
			$this->session->set_userdata('table', $data['tables'][0]['name']);
			$table = $this->session->userdata ('table');
		}
		elseif(isset($table))
		{
			$table = $this->session->userdata ('table');
		}
		else
		{
			$this->session->unset_userdata('table');
			$table = NULL;
		}
		$table_id = $this->Order_model->get_table_orders_by($table, $store_id);
		$data['table'] = $table;
		$data['orders'] = $this->Order_model->get_table_orders($store_id, $table_id);
		$data['order_details'] = $this->Order_model->get_table_detail_orders($store_id, $table_id);
		
		echo json_encode($data);
	}

	function category_items_get()
	{
		$client_id = 1;
		$store_id = 1;
		$table = $this->session->userdata ('table');

		$stores = $this->Manage_store->get_all(0, 0, $client_id)->result_array();
		$newStores = array("store_id" => 0, "name" => "");
		array_unshift($stores, $newStores);

		$categories = $this->Order_model->get_categories($store_id);
		$newCategories = array("category_id" => 0, "name" => "");
		array_unshift($categories, $newCategories);

		$items = $this->Order_model->get_items($client_id, $store_id);
		$newItems = array("item_id" => 0, "name" => "");
		array_unshift($items, $newItems);

		$data['items'] = $items;
		$data['categories'] = $categories;
		$data['stores'] = $stores;
		
		echo json_encode($data);
	}

	function order_item_post()
	{
		$client_id = 1;
		$store_id = 1;

		$datas = json_decode(file_get_contents('php://input'), true);

		$item_id = $datas['item_id'];
		$order_id = $datas['order_id'];

		$exists = $this->Order_model->item_exists($item_id, $order_id);
		if(!$exists)
		{
			$price = $this->Order_model->get_item_unit_price($item_id);
			$item_data = array(
				'item_id' => $item_id,
				'order_id' => $order_id,
				'quantity_purchased' => 1,
				'item_price' => $price,
				'date_created' => date('Y-m-d H:i:s')
			);
			$this->Order_model->item_insert($item_data);
		}
		else
		{
			$ordered_quantity = $this->Order_model->get_order_details($order_id, $item_id)->quantity_purchased;
			$ordered_quantity = $ordered_quantity + 1;
			$this->Order_model->item_update($item_id, $order_id, $ordered_quantity);
		}
		$data['order_details'] = $this->Order_model->get_detail_orders_by($order_id);

		echo json_encode($data);
	}

	function decrease_order_item_post()
	{
		$client_id = 1;
		$store_id = 1;

		$datas = json_decode(file_get_contents('php://input'), true);

		$item_id = $datas['item_id'];
		$order_id = $datas['order_id'];

		$exists = $this->Order_model->item_exists($item_id, $order_id);
		if(!$exists)
		{
			$price = $this->Order_model->get_item_unit_price($item_id);
			$item_data = array(
				'item_id' => $item_id,
				'order_id' => $order_id,
				'quantity_purchased' => 1,
				'item_price' => $price,
				'date_created' => date('Y-m-d H:i:s')
			);
			$this->Order_model->item_insert($item_data);
		}
		else
		{
			$ordered_quantity = $this->Order_model->get_order_details($order_id, $item_id)->quantity_purchased;
			$ordered_quantity = $ordered_quantity - 1;
			$this->Order_model->item_update($item_id, $order_id, $ordered_quantity);
		}
		$data['order_details'] = $this->Order_model->get_detail_orders_by($order_id);

		echo json_encode($data);
	}

	function delete_order_item_post()
	{
		$client_id = 1;
		$store_id = 1;

		$datas = json_decode(file_get_contents('php://input'), true);

		$item_id = $datas['item_id'];
		$order_id = $datas['order_id'];

		$exists = $this->Order_model->item_exists($item_id, $order_id);
		if(!$exists)
		{
			$price = $this->Order_model->get_item_unit_price($item_id);
			$item_data = array(
				'item_id' => $item_id,
				'order_id' => $order_id,
				'quantity_purchased' => 1,
				'item_price' => $price,
				'date_created' => date('Y-m-d H:i:s')
			);
			$this->Order_model->item_insert($item_data);
		}
		else
		{
			$this->Order_model->item_delete($item_id, $order_id);
		}
		$data['order_details'] = $this->Order_model->get_detail_orders_by($order_id);

		echo json_encode($data);
	}

	function create_order_table_post()
	{
		$client_id = 1;
		$store_id = 1;
		$store_address = $this->Sale->get_store_address($store_id)->store_address;
		$datas = json_decode(file_get_contents('php://input'), true);
	}

	function get_orders()
	{
		$client_id = 1;
		$store_id = 1;

		$datas = json_decode(file_get_contents('php://input'), true);

		$item_id = $datas['item_id'];

		//$this->Order_model->addToOrder();

		echo json_encode();
	}

	function create_order_per_table()
	{
		$this->Order_model->get_table_orders_by($order_table_name, $store_id);
	}

	function change_table_post()
	{
		$client_id = 1;
		$store_id = 1;

		$datas = json_decode(file_get_contents('php://input'), true);
		$table = $datas['table'];

		$this->session->set_userdata('table', $table);
		$table_id = $this->Order_model->get_table_orders_by($table, $store_id);
		$data['orders'] = $this->Order_model->get_table_orders($store_id, $table_id);
		$data['order_details'] = $this->Order_model->get_table_detail_orders($store_id, $table_id);
		
		echo json_encode($data);
	}

	function change_param_post()
	{
		$client_id = 1;
		$store_id = 1;

		$datas = json_decode(file_get_contents('php://input'), true);
		$store = $datas['store'];
		$item = $datas['item'];
		$category = $datas['category'];

		$param_store_id = $this->Order_model->get_store_id($store, $client_id);
		$param_item_id = $this->Order_model->get_item_id($item, $client_id);
		$param_category_id = $this->Order_model->get_category_id($category, $store_id);

		$data['item_details'] = $this->Order_model->inventory_per_item($param_store_id, $param_item_id, $param_category_id, $client_id);
		//$data['order_details'] = $this->Order_model->get_table_detail_orders($store_id, $table_id);
		
		echo json_encode($data);
	}

	function add_new_customer_post()
	{
		$client_id = 1;
		$store_id = 1;
		$user_id = 1;

		$datas = json_decode(file_get_contents('php://input'), true);
		$table = $datas['table'];
		$pax = $datas['pax'];
		$cname = $datas['cname'];

		$table_id = $this->Order_model->get_table_orders_by($table, $store_id);

		$sale_order_data = array(
			'order_table_id' => $table_id,
			'pax' => $pax,
			'customer_name' => $cname,
			'created_by' => $user_id,
			'created_date' => date('Y-m-d H:i:s'),
			'store_id' => $store_id,
			'client_id' => $client_id
		);
		$insert_id = $this->Order_model->save($sale_order_data);

		$data['orders'] = $this->Order_model->get_orders_by($insert_id);
		$data['order_id'] = $insert_id;
		echo json_encode($data);
	}
}
?>