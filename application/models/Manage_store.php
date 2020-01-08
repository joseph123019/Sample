<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage_store extends CI_Model
{
	/*
	Determines if a given item_id is an item
	*/
	public function exists($store_id, $ignore_deleted = FALSE, $deleted = FALSE)
	{
		if (ctype_digit($store_id))
		{
			$this->db->from('ssp_stores');
			$this->db->where('store_id', (int)$store_id);
			if ($ignore_deleted == FALSE)
			{
				$this->db->where('isDeleted', $deleted);
			}

			return ($this->db->get()->num_rows() == 1);
		}

		return FALSE;
	}

	public function delete_list($store_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		// set to 0 quantities
		$this->db->where_in('store_id', $store_ids);
		$success = $this->db->update('ssp_stores', array('isDeleted'=>1));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	function get_active_shift($store_id)
	{
		$this->db->select('ssp_store_shifts.*');
		$this->db->from('ssp_store_shifts');
		$this->db->where('store_id', $store_id);
		$this->db->where('ssp_store_shifts.status', 1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $category_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('ssp_store_shifts') as $field)
			{
				$item_obj->$field = '';
			}

			return $item_obj;
		}
	}

	function start_shift($store_id, $user_id, $pos_shift_id, $date)
	{
		$this->db->trans_start();
        
		$store_shift = array('status'=>1, 'pos_shift_id'=>$pos_shift_id, 'store_id'=>$store_id, 'started_by'=>$user_id, 'date_started'=>$date);

        $this->db->insert('ssp_store_shifts', $store_shift);

        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
	}

	function end_shift($store_shift_id, $user_id, $date)
	{
		$this->db->trans_start();

		// set to 0 quantities
		$this->db->where_in('store_shift_id', $store_shift_id);
		$success = $this->db->update('ssp_store_shifts', array('status'=>0, 'ended_by'=>$user_id, 'date_ended'=>$date));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	function start_shift_inventory($store_shift_id, $item_id, $available_qty)
	{
		$this->db->trans_start();
        $clientInfo = array('store_shift_id'=>$store_shift_id, 'item_id'=>$item_id, 'start_quantity'=>$available_qty);

        $this->db->insert('ssp_store_shift_inventory', $clientInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
	}

	function end_shift_inventory($store_shift_id, $item_id, $available_qty)
	{
		$this->db->trans_start();

		// set to 0 quantities
		$this->db->where('store_shift_id', $store_shift_id);
		$this->db->where('item_id', $item_id);
		$success = $this->db->update('ssp_store_shift_inventory', array('end_quantity'=>$available_qty));

		$this->db->trans_complete();

		$success &= $this->db->trans_status();

		return $success;
	}

	function get_role_list($client_id)
	{
		$this->db->select('*');
        $this->db->from('ssp_roles as BaseTbl');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId <>', 1);
        $this->db->where_in('BaseTbl.client_id', array($client_id, 0));
        $query = $this->db->get();
        
        $result = $query->result();    
        return $result;
	}

	function get_store_role_access($store_id)
    {
        $this->db->select('BaseTbl.store_id, BaseTbl.role_id');
        $this->db->from('ssp_store_access_role as BaseTbl');
        $this->db->join('ssp_stores as store', 'store.store_id = BaseTbl.store_id ', 'left outer');
        $this->db->where('BaseTbl.store_id', $store_id);
        $this->db->where('store.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->result();
    }

    function remove_store_access($store_id)
    {
        if (empty($store_id))
        {
            return FALSE;
        }
        if (!empty($store_id))
        {
            $this->db->delete('ssp_store_access_role', array('store_id' => $store_id));
            $return = TRUE;
        }
        return $return;
    }

    function add_store_access($role_ids, $store_id = FALSE, $user_id)
    {	
    	$return = FALSE;
        if(!is_array($role_ids))
        {
            $role_ids = array($role_ids);
        }

        // Then insert each into the database
        foreach ($role_ids as $role_id)
        {
            $this->db->insert('ssp_store_access_role', array('role_id' => $role_id, 'store_id' => $store_id, 'createdBy' => $user_id));
            $return = TRUE;
        }
  		return $return;
    }

	public function store_get_info($store_id)
	{
		$this->db->select('ssp_stores.*');
		$this->db->from('ssp_stores');
		$this->db->where('store_id', $store_id);
		$this->db->where('ssp_stores.isDeleted', 0);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $category_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('ssp_stores') as $field)
			{
				$item_obj->$field = '';
			}

			return $item_obj;
		}
	}
	public function get_store_items($store_id){

			$query = $this->db->get_where('ssp_stores', array('store_id' => $store_id));
            //return $query->result_array();
			return $query->row();
	}
	
	public function get_item_seleted_stores($item_id)
	{
		$this->db->select('pis.store_id');
		$this->db->from('items');
		$this->db->join('ssp_item_stores pis', 'pis.item_id = items.item_id');
		$this->db->where('pis.item_id', $item_id);
		$this->db->where('items.deleted', 0);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_category_seleted_stores($category_id)
	{
		$this->db->select('pis.store_id');
		$this->db->from('ssp_categories');
		$this->db->join('ssp_category_stores pis', 'pis.category_id = ssp_categories.category_id');
		$this->db->where('pis.category_id', $category_id);
		$this->db->where('ssp_categories.isDeleted', 0);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_total_rows()
	{
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);

		return $this->db->count_all_results();
	}

	public function get_found_rows($search, $filters, $rows = 0, $limit_from = 0, $sort = 'stores.name', $order = 'asc', $client_id)
	{
		return $this->search($search, $filters, $rows, $limit_from, $sort, $order, $client_id)->num_rows();
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'stores.name', $order = 'asc', $client_id)
	{
		$this->db->from('ssp_stores as stores');
		$this->db->where('client_id', $client_id);
		$this->db->where('isDeleted', 0);
		if(!empty($search))
		{
			if($filters['search_custom'] == FALSE)
			{
				$this->db->group_start();
					$this->db->like('name', $search);
					/*$this->db->or_like('username', $search);
					$this->db->or_like('users.userId', $search);
					$this->db->or_like('password', $search);*/
				$this->db->group_end();
			}
		}

		// avoid duplicated entries with same name because of inventory reporting multiple changes on the same item in the same date range
		$this->db->group_by('stores.store_id');

		// order by name of item
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Returns all the items
	*/
	public function get_all($rows = 0, $limit_from = 0, $client_id)
	{
		$this->db->from('ssp_stores');

		$this->db->where('ssp_stores.isDeleted', 0);
		$this->db->where('client_id', $client_id);
		$this->db->where('isDeleted', 0);

		$this->db->order_by('ssp_stores.name', 'asc');

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}
		
		return $this->db->get();
	}

	public function get_all_stores($client_id, $role_id)
	{
		$this->db->select('ssp_stores.*');
		$this->db->from('ssp_stores');
		$this->db->join('ssp_store_access_role ar', 'ar.store_id = ssp_stores.store_id');
		$this->db->where('ar.role_id', $role_id);
		$this->db->where('ssp_stores.isDeleted', 0);
		$this->db->where('client_id', $client_id);
		$this->db->where('isDeleted', 0);

		$this->db->order_by('ssp_stores.name', 'asc');
		
		return $this->db->get();
	}

	/*
	Gets information about a particular item
	*/
	public function get_info($store_id)
	{
		$this->db->select('ssp_stores.*');
		$this->db->from('ssp_stores');
		$this->db->where('store_id', $store_id);
		$this->db->where('isDeleted', 0);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('ssp_stores') as $field)
			{
				$item_obj->$field = '';
			}

			return $item_obj;
		}
	}

	public function update_stock_location($store_id, $store_name)
	{
		$this->db->trans_start();
		$store_name = array(
			'location_name' => $store_name,
		);

		$this->db->where('store_id', $store_id);
		$this->db->update('stock_locations', $store_name);
		$this->db->trans_complete();

		$success = $this->db->trans_status();
		
		return $success;
	}

	public function get_stores()
	{
		$this->db->select(array('ssp_stores.*'));
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_multiple_info($store_ids)
	{
		$this->db->from('ssp_stores');
		$this->db->where_in('store_id', $store_ids);
		$this->db->where('isDeleted', 0);

		return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/

	public function get_store_id($store_name, $client_id)
	{
		$this->db->select('*');
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);
		$this->db->where('name', $store_name);
		$this->db->where('client_id', $client_id);
		$this->db->where('isDeleted', 0);
		$result = $this->db->get();

		$store_id = $result->row();
		return $store_id;
	}

	public function get_category_id($category_name, $client_id)
	{
		$this->db->select('*');
		$this->db->from('ssp_categories');
		$this->db->where('isDeleted', 0);
		$this->db->where('name', $category_name);
		$this->db->where('client_id', $client_id);
		$result = $this->db->get();

		$category_id = $result->row();
		return $category_id;
	}

	public function get_unit_id($unit_abbr)
	{
		$this->db->select('*');
		$this->db->from('ssp_units');
		$this->db->where('abbreviation', $unit_abbr);
		$result = $this->db->get();

		$category_id = $result->row();
		return $category_id;
	}

	public function is_beg_end_inv($store_id)
	{
		$this->db->select('*');
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);
		$this->db->where('store_id', $store_id);
		$result = $this->db->get();

		$store = $result->row();
		return $store;
	}

	public function save(&$store_data, $store_id = FALSE)
	{
		if(!$store_id || !$this->exists($store_id, TRUE))
		{
			if($this->db->insert('ssp_stores', $store_data))
			{
				$store_data['store_id'] = $this->db->insert_id();

				return TRUE;
			}
			return FALSE;
		}

		$this->db->where('store_id', $store_id);

		return $this->db->update('ssp_stores', $store_data);
	}

	public function get_store_by_name($client_id, $store_name)
	{
		$this->db->select('store_id, name');
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);
		$this->db->where('name', $store_name);
		$this->db->where('client_id', $client_id);

		$query = $this->db->get();
        $result = $query->result();
        return $result;
	}

	public function get_count($client_id)
	{
		$this->db->select('store_id, name');
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);
		$this->db->where('client_id', $client_id);

		return $this->db->get();
	}

	public function get_clientnoOfStores($client_id)
	{
		$this->db->select('*');
		$this->db->from('ssp_clients');
		$this->db->where('isDeleted', 0);
		$this->db->where('clientId', $client_id);

		return $this->db->get()->result();
	}

	function generateNewMobileSettings($clientId, $store_id, $user_id)
    {
        $query = $this->db->query('call ssp_generate_mobile_settings('.$clientId.', '.$store_id.', '.$user_id.')');
        
        return $query->result_id;
    }

    function generate_stock_location($client_id, $store_id)
    {
    	$this->db->select('*');
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);
		$this->db->where('store_id', $store_id);
		$result = $this->db->get();

		$location_name = $result->row()->name;

        $query = $this->db->query('call ssp_generate_stock_locations("'.$location_name.'", '.$client_id.', '.$store_id.')');
        
        return $query->result_id;
    }

    function generate_default_grocery_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_grocery_items('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_gasoline_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_gasoline_items('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_others_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_others_items('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_apparel_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_apparel_items('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_restaurant_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_restaurant_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_cafe_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_cafe_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_retail_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_retail_items('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_buy_sell_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_buysell_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_bakery_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_bakery_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_rice_retailing_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_rice_retail_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_water_refilling_station_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_water_station_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_catering_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_catering_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_meat_shop_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_meatshop_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_food_cart_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_food_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_organic_food_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_organic_food_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_clothing_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_clothing_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_collectible_trading_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_collectible_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_spa_hms_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_spa_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_laundromat_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_laundromat_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_rental_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_car_rental_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_printing_services_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_printing_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_cctv_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_cctv_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_furnitures_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_furniture_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_photo_video_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_photography_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_agency_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_agency_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_internet_cafe_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_internet_cafe_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_bike_rental_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_bike_rental_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_party_supplies_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_part_supply_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_vape_shop_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_vape_shop_type('.$client_id.')');
        
        return $query->result_id;
    }

    function generate_default_other_items($client_id, $store_id)
    {
        $query = $this->db->query('call ssp_generate_default_other_type('.$client_id.')');
        
        return $query->result_id;
    }
}
?>
