<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
	public function get_running_categories($store_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('ssp_categories.category_id, ssp_categories.name');
		$this->db->from('ssp_categories');
		$this->db->join('ssp_category_stores as category_store', 'category_store.category_id = ssp_categories.category_id');
		$this->db->where('isDeleted', 0);
		$this->db->where('is_running', 1);
		$this->db->where('category_store.store_id', $store_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_categories($store_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('ssp_categories.category_id, ssp_categories.name');
		$this->db->from('ssp_categories');
		$this->db->join('ssp_category_stores as category_store', 'category_store.category_id = ssp_categories.category_id');
		$this->db->where('isDeleted', 0);
		$this->db->where('category_store.store_id', $store_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	public function get_running_items($client_id, $store_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('IFNULL(item_prices.`unit_price`, items.`unit_price`) as `unit_price`,items.*, cat.name as category_name, item_quantities.quantity, cat.category_id');
		$this->db->from('items');
		$this->db->join('ssp_item_stores as stores', 'stores.item_id = items.item_id AND stores.store_id = '. $store_id);
		$this->db->join('ssp_item_prices AS item_prices', 'items.item_id = item_prices.item_id AND item_prices.store_id = stores.store_id AND item_prices.store_id = '.$store_id, 'LEFT');
		$this->db->join('ssp_categories AS cat', 'cat.category_id = items.category_id', 'INNER');
		$this->db->join('stock_locations', 'stock_locations.store_id = stores.store_id');
		$this->db->join('item_quantities', 'item_quantities.item_id = items.item_id AND stock_locations.location_id = item_quantities.location_id', 'LEFT OUTER');
		$this->db->where('items.deleted', 0);
		$this->db->where('items.is_running', 1);
		$this->db->where('items.client_id', $client_id);

		$query = $this->db->get();

        $result = $query->result_array();
        return $result;
	}

	function get_items($client_id, $store_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('items.*, cat.name as category_name, item_quantities.quantity, cat.category_id');
		$this->db->from('items');
		$this->db->join('ssp_item_stores as stores', 'stores.item_id = items.item_id AND stores.store_id = '. $store_id);
		$this->db->join('ssp_categories AS cat', 'cat.category_id = items.category_id', 'INNER');
		$this->db->join('stock_locations', 'stock_locations.store_id = stores.store_id');
		$this->db->join('item_quantities', 'item_quantities.item_id = items.item_id AND stock_locations.location_id = item_quantities.location_id', 'LEFT OUTER');
		$this->db->where('items.deleted', 0);
		$this->db->where('items.client_id', $client_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_item_unit_price($item_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->from('items');
		$this->db->where('items.item_id', $item_id);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row()->unit_price;
		}
	}

	function get_running_tables($store_id, $room_id = 0)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->from('ssp_order_table');
		$this->db->where('store_id', $store_id);
		if($room_id != 0)
		{
			$this->db->where('room_id', $room_id);
		}

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_room_lists($store_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->from('ssp_rooms');
		$this->db->where('store_id', $store_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_table_orders($store_id, $order_table_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->from('ssp_orders');
		$this->db->where('ssp_orders.order_table_id', $order_table_id);
		$this->db->where('ssp_orders.store_id', $store_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_orders_by($order_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->from('ssp_orders');
		$this->db->where('ssp_orders.order_id', $order_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_table_detail_orders($store_id, $order_table_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('i.item_id, i.name, oi.quantity_purchased, oi.item_price');
		$this->db->from('ssp_orders');
		$this->db->join('ssp_order_items as oi', 'oi.order_id = ssp_orders.order_id');
		$this->db->join('items as i', 'i.item_id = oi.item_id');
		$this->db->where('ssp_orders.order_table_id', $order_table_id);
		$this->db->where('ssp_orders.store_id', $store_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_order_details($order_id, $item_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('i.item_id, i.name, oi.quantity_purchased, oi.item_price');
		$this->db->from('ssp_orders');
		$this->db->join('ssp_order_items as oi', 'oi.order_id = ssp_orders.order_id');
		$this->db->join('items as i', 'i.item_id = oi.item_id');
		$this->db->where('ssp_orders.order_id', $order_id);
		$this->db->where('oi.item_id', $item_id);

		$query = $this->db->get();
        return $query->row();
	}

	function get_detail_orders_by($order_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('i.item_id, i.name, oi.quantity_purchased, oi.item_price');
		$this->db->from('ssp_orders');
		$this->db->join('ssp_order_items as oi', 'oi.order_id = ssp_orders.order_id');
		$this->db->join('items as i', 'i.item_id = oi.item_id');
		$this->db->where('ssp_orders.order_id', $order_id);

		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
	}

	function get_table_orders_by($order_table_name, $store_id, $room_id = 0)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('order_table_id, name');
		$this->db->from('ssp_order_table');
		$this->db->where('is_deleted', 0);
		$this->db->where('ssp_order_table.store_id', $store_id);
		$this->db->where('name', $order_table_name);

		if($room_id != 0)
		{
			$this->db->where('ssp_order_table.room_id', $room_id);
		}

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row()->order_table_id;
		}

		return FALSE;
	}

	function get_room_by($room_name, $store_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('room_id, room_name');
		$this->db->from('ssp_rooms');
		$this->db->where('ssp_rooms.store_id', $store_id);
		$this->db->where('room_name', $room_name);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row()->room_id;
		}

		return FALSE;
	}

	function get_store_id($store_name, $client_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('store_id, name');
		$this->db->from('ssp_stores');
		$this->db->where('isDeleted', 0);
		$this->db->where('client_id', $client_id);
		$this->db->where('name', $store_name);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row()->store_id;
		}

		return FALSE;
	}

	function get_item_id($item_name, $client_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->from('items');
		$this->db->where('items.name', (string)$item_name);
		$this->db->where('items.client_id', $client_id);
		$this->db->where('items.deleted', 0);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row()->item_id;
		}

		return FALSE;
	}

	function get_category_id($category_name, $store_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->select('ssp_categories.category_id, ssp_categories.name');
		$this->db->from('ssp_categories');
		$this->db->join('ssp_category_stores as category_store', 'category_store.category_id = ssp_categories.category_id');
		$this->db->where('isDeleted', 0);
		$this->db->where('category_store.store_id', $store_id);
		$this->db->where('name', $category_name);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row()->category_id;
		}

		return FALSE;
	}

	function inventory_per_item($store_id, $item_id, $category_id, $client_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$where = 'i.stock_type = 0 ';

		$this->db->select('i.name, sc.name as category, inv.location_name as store_name, inv.trans_inventory as available_qty');
		$this->db->from('items as i');
		$this->db->join('ssp_categories as sc', 'sc.category_id = i.category_id');
		$this->db->join('(SELECT SUM(inv.trans_inventory) as trans_inventory, inv.trans_items, sl.store_id, sl.location_name
			FROM inventory inv
			INNER JOIN stock_locations AS sl ON sl.location_id = inv.trans_location
			GROUP BY inv.trans_items, inv.trans_location) inv', 'inv.trans_items = i.item_id');
		$this->db->where($where);

		if($store_id > 0)
		{
			$this->db->where('inv.store_id = ', $store_id);
		}
		if($item_id > 0)
		{
			$this->db->where('i.item_id = ', $item_id);
		}
		if($category_id > 0)
		{
			$this->db->where('i.category_id = ', $category_id);
		}
		$this->db->where('i.deleted =', 0);
		$this->db->where('i.client_id =', $client_id);
		
		$this->db->group_by('i.item_id');
		$this->db->limit(20);
		
		return $this->db->get()->result();
	}

	function save($sale_order_data)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->trans_start();
		
		$this->db->insert('ssp_orders', $sale_order_data);
		$return_id = $this->db->insert_id();
		$this->db->trans_complete();
		$success = $this->db->trans_status();

		return $return_id;
	}

	function item_exists($item_id, $order_id)
	{
		$this->db->query("SET sql_mode = '' ");
		if (ctype_digit($item_id))
		{
			$this->db->from('ssp_order_items');
			$this->db->where('item_id', (int)$item_id);
			$this->db->where('order_id', (int)$order_id);

			return ($this->db->get()->num_rows() == 1);
		}

		return FALSE;
	}

	function item_insert($item_data)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->trans_start();
		
		$this->db->insert('ssp_order_items', $item_data);
		$return_id = $this->db->insert_id();
		$this->db->trans_complete();
		$success = $this->db->trans_status();

		return $return_id;
	}

	function item_update($item_id, $order_id, $ordered_quantity)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->trans_start();

		$items = array(
			'quantity_purchased' => $ordered_quantity
		);

		$this->db->where('item_id', $item_id);
		$this->db->where('order_id', $order_id);
		$this->db->update('ssp_order_items', $items);
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	function item_delete($item_id, $order_id)
	{
		$this->db->query("SET sql_mode = '' ");
		$this->db->trans_start();
			$this->db->delete('ssp_order_items', array('item_id' => $item_id, 'order_id' => $order_id));
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

}
