<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_sales_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('sale_id' => $CI->lang->line('common_id')),
		array('sale_no' => 'Sale No'),
		array('store_name' => 'Store Name'),
		array('sale_date' => 'Date'),
		array('customer_name' => $CI->lang->line('customers_customer')),
		array('amount_due' => $CI->lang->line('sales_amount_due')),
		array('amount_tendered' => $CI->lang->line('sales_amount_tendered')),
		array('discount_percent' => 'Discount'),
		array('change_due' => $CI->lang->line('sales_change_due')),
		array('payment_type' => $CI->lang->line('sales_payment_type'))
	);

	if($CI->config->item('invoice_enable') == TRUE)
	{
		$headers[] = array('invoice_number' => $CI->lang->line('sales_invoice_number'));
		$headers[] = array('invoice' => '&nbsp', 'sortable' => FALSE);
	}
	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))), FALSE, FALSE);
}

/*
 Gets the html data rows for the sales.
 */
function get_sale_data_last_row($sales, $controller)
{
	$CI =& get_instance();
	$sum_amount_due = 0;
	$sum_amount_tendered = 0;
	$sum_change_due = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$sum_amount_due += $sale->amount_due;
		$sum_amount_tendered += $sale->amount_tendered;
		$sum_change_due += $sale->change_due;
		$sale_date = date('Y-m-d h:i A', strtotime($sale->sale_date));
	}
	
	return array(
		'sale_id' => '-',
		'sale_no' => '-',
		'sale_date' => '<b>'.$sale_date.'</b>',
		'amount_due' => '<b>'.number_format($sum_amount_due, 2).'</b>',
		'amount_tendered' => '<b>'. number_format($sum_amount_tendered, 2).'</b>',
		'change_due' => '<b>'.number_format($sum_change_due, 2).'</b>'
	);
}

function get_sale_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'sale_id' => $sale->sale_id,
		'sale_no' => $sale->sale_no,
		'store_name' => $sale->store_name,
		'sale_date' =>  date('Y-m-d h:i A', strtotime($sale->sale_date)),
		'customer_name' => $sale->customer_name,
		'amount_due' => number_format($sale->amount_due, 2),
		'amount_tendered' => $sale->amount_tendered,
		'discount_percent' => $sale->discount,
		'change_due' => number_format($sale->change_due, 2),
		'payment_type' => $sale->payment_type
	);
	
	if($CI->config->item('invoice_enable'))
	{
		$row['invoice_number'] = $sale->invoice_number;
		$row['invoice'] = empty($sale->invoice_number) ? '' : anchor($controller_name."/invoice/$sale->sale_id", '<span class="glyphicon glyphicon-list-alt"></span>',
			array('title'=>$CI->lang->line('sales_show_invoice'))
		);
	}

	$row['receipt'] = anchor($controller_name."/receipt/$sale->sale_id", '<span style="font-size:17px">&#8369;</span>',
		array('title' => $CI->lang->line('sales_show_receipt'))
	);

	return $row;
}

function get_back_order_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('sale_id' => $CI->lang->line('common_id')),
		array('sale_no' => 'Sale No'),
		array('store_name' => 'Store Name'),
		array('sale_date' => 'Date'),
		array('customer_name' => $CI->lang->line('customers_customer'))
	);

	if($CI->config->item('invoice_enable') == TRUE)
	{
		$headers[] = array('invoice_number' => $CI->lang->line('sales_invoice_number'));
		$headers[] = array('invoice' => '&nbsp', 'sortable' => FALSE);
	}
	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))), FALSE, FALSE);
}

function get_back_order_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'sale_no' => $sale->sale_no,
		'store_name' => $sale->store_name,
		'sale_date' =>  date('Y-m-d h:i A', strtotime($sale->sale_date)),
		'customer_name' => $sale->customer_name
	);

	$row['receipt'] = anchor("sales/receipt/$sale->sale_id/1", '<span style="font-size:17px">&#8369;</span>',
		array('title' => $CI->lang->line('sales_show_receipt'))
	);

	return $row;
}

function get_sales_item_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('sale_id' => 'POS No.'),
		array('sale_date' => 'Date'),
		array('name' => 'Item Name'),
		array('quantity_purchased' => 'Qty Sold'),
		array('discount' => 'Discount'),
		array('total' => 'Total')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

/*
 Gets the html data rows for the sales.
 */
function get_sale_item_data_last_row($sales, $controller)
{
	$CI =& get_instance();
	$sum_amount_due = 0;
	$items_purchased = 0;
	$discount = 0;
	$total = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$discount += $sale->discount;
		$items_purchased += $sale->items_purchased;
		$total += $sale->total;
	}
	
	return array(
		'sale_id' => '-',
		'sale_date' => '-',
		'name' => '-',
		'quantity_purchased' => '<b>'. number_format($items_purchased, 2).'</b>',
		'discount' => '<b>'.number_format($discount, 2).'</b>',
		'total' => '<b>'.number_format($total, 2).'</b>'
	);
}

function get_sale_item_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'sale_id' => $sale->sale_id,
		'sale_date' =>  date('Y-m-d', strtotime($sale->sale_date)),
		'name' => $sale->item_name,
		'quantity_purchased' => number_format($sale->items_purchased, 2),
		'discount' => $sale->discount,
		'total' => number_format($sale->total, 2)
	);
	return $row;
}

function get_total_sales_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('sale_date' => 'Date'),
		array('quantity_purchased' => 'Qty Sold'),
		array('total' => 'Total')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE, 'checkbox' => FALSE))), FALSE, FALSE);
}

/*
 Gets the html data rows for the sales.
 */
function get_total_sales_data_last_row($sales, $controller)
{
	$CI =& get_instance();
	$quantity_purchased = 0;
	$total = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$quantity_purchased += $sale->quantity_purchased;
		$total += $sale->total;
	}
	
	return array(
		'sale_date' => '-',
		'quantity_purchased' => '<b>'. number_format($quantity_purchased, 2).'</b>',
		'total' => '<b>'.number_format($total, 2).'</b>'
	);
}

function get_total_sales_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'sale_date' =>  date('Y-m-d', strtotime($sale->date)),
		'quantity_purchased' => number_format($sale->quantity_purchased, 2),
		'total' => number_format($sale->total, 2)
	);
	return $row;
}

function get_total_sale_per_item_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('item_name' => 'Item Name'),
		array('quantity_purchased' => 'Qty Sold'),
		array('total' => 'Total')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE, 'checkbox' => FALSE))), FALSE, FALSE);
}

/*
 Gets the html data rows for the sales.
 */
function get_total_sale_per_item_data_last_row($sales, $controller)
{
	$CI =& get_instance();
	$quantity_purchased = 0;
	$total = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$quantity_purchased += $sale->quantity_purchased;
		$total += $sale->total;
	}
	
	return array(
		'item_name' => '-',
		'quantity_purchased' => '<b>'. number_format($quantity_purchased, 2).'</b>',
		'total' => '<b>'.number_format($total, 2).'</b>'
	);
}

function get_total_sale_per_item_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'item_name' => $sale->item_name,
		'quantity_purchased' => number_format($sale->quantity_purchased, 2),
		'total' => number_format($sale->total, 2)
	);
	return $row;
}

function get_top_item_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('name' => 'Item Name'),
		array('category' => 'Category Name'),
		array('total_qty_purchased' => 'Total Qty Sold')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

/*
 Gets the html data rows for the sales.
 */
function get_top_item_data_last_row($sales, $controller)
{
	$CI =& get_instance();
	$total_qty_purchased = 0;
	$total = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$total_qty_purchased += $sale->total_qty_purchased;
	}
	
	return array(
		'name' => '-',
		'category' => '-',
		'total_qty_purchased' => '<b>'. number_format($total_qty_purchased, 2).'</b>'
	);
}

function get_top_item_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'name' =>  $sale->name,
		'category' =>  $sale->category,
		'total_qty_purchased' => number_format($sale->total_qty_purchased, 2)
	);
	return $row;
}

function get_detailed_sales($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);

	$row = array (
		'id' =>  $sale->sale_id,
		'sale_date' =>  $sale->sale_date,
		'quantity' =>  number_format($sale->items_purchased, 2),
		'employee_name' =>  $sale->employee_name,
		'customer_name' =>  $sale->customer_name,
		'subtotal' =>  $sale->subtotal,
		'tax' =>  $sale->tax,
		'total' =>  $sale->total
	);
	return $row;
}

function get_detailed_sales_item($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);

	$row = array (
		'name' =>  $sale->name,
		'category' =>  $sale->category,
		'serialnumber' =>  $sale->serialnumber,
		'description' =>  $sale->description,
		'quantity' =>  number_format($sale->quantity_purchased, 2)
	);
	return $row;
}

function get_detailed_sales_last_row($sales, $controller)
{
	$CI =& get_instance();
	$total_qty_purchased = 0;
	$total = 0;
	$tax = 0;
	$subtotal = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$total_qty_purchased += $sale->items_purchased;
		$total += $sale->total;
		$tax += $sale->tax;
		$subtotal += $sale->subtotal;
	}
	
	return array(
		'id' => '-',
		'sale_date' => '-',
		'quantity' => '<b>'. number_format($total_qty_purchased, 2).'</b>',
		'employee_name' => '-',
		'customer_name' => '-',
		'subtotal' => number_format($subtotal ,2),
		'tax' => number_format($tax ,2),
		'total' => number_format($total ,2)
	);
}

function get_inventory_per_item_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('name' => 'Item Name'),
		array('category' => 'Category Name'),
		array('start_inventory' => 'Starting Qty'),
		array('available_qty' => 'Available Qty'),
		array('total_qty_purchased' => 'Total Qty Sold')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

/*
 Gets the html data rows for the sales.
 */
function get_inventory_per_item_data_last_row($sales, $controller)
{
	$CI =& get_instance();
	$total_qty_purchased = 0;
	$total = 0;
	$trans_inventory = 0;

	foreach($sales->result() as $key=>$sale)
	{
		$total_qty_purchased += $sale->total_qty_purchased;
		$trans_inventory += $sale->trans_inventory;
	}
	
	return array(
		'name' => '-',
		'category' => '-',
		'start_inventory' => '-',
		'available_qty' => number_format($sale->trans_inventory, 2),
		'total_qty_purchased' => '<b>'. number_format($total_qty_purchased, 2).'</b>'
	);
}

function get_inventory_per_item_data_row($sale, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'name' =>  $sale->name,
		'category' =>  $sale->category,
		'start_inventory' =>  number_format($sale->start_inventory, 2),
		'available_qty' =>  number_format($sale->trans_inventory, 2). ' ' . $sale->abbreviation,
		'total_qty_purchased' => number_format($sale->total_qty_purchased, 2). ' ' . $sale->abbreviation
	);
	return $row;
}

function get_inventory_tracking_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('name' => 'Item Name'),
		array('location_name' => 'Location'),
		array('trans_date' => 'Date'),
		array('trans_inventory' => 'In/Out Qty'),
		array('trans_current_inventory' => 'Running Balance'),
		array('username' => 'Created By'),
		array('trans_comment' => 'Remarks')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

function get_inventory_material_tracking_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('name' => 'Material Name'),
		array('location_name' => 'Location'),
		array('trans_date' => 'Date'),
		array('trans_inventory' => 'In/Out Qty'),
		array('trans_current_inventory' => 'Running Balance'),
		array('username' => 'Created By'),
		array('trans_comment' => 'Remarks')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

function get_inventory_tracking_data_row($item, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'name' =>  $item->name,
		'location_name'=> $item->location_name,
		'trans_date' =>  $item->trans_date,
		'trans_inventory' =>  number_format($item->trans_inventory, 2),
		'trans_current_inventory' => number_format($item->trans_current_inventory, 2),
		'username' => $item->username,
		'trans_comment' => $item->trans_comment
	);
	return $row;
}

function get_critical_item_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('name' => 'Item Name'),
		array('category' => 'Category Name'),
		array('location_name' => 'Location Name'),
		array('quantity' => 'Available Qty'),
		array('reorder_level' => 'Reorder Level')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))));
}

function get_critical_item_data_row($item, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'name' =>  $item->name,
		'category' =>  $item->category,
		'location_name'=> $item->location_name,
		'quantity' =>  number_format($item->quantity, 2),
		'reorder_level' => number_format($item->reorder_level, 2)
	);
	return $row;
}

function get_critical_item_data_last_row($items, $controller)
{
	$CI =& get_instance();
	$reorder_level = 0;
	$quantity = 0;

	foreach($items->result() as $key=>$item)
	{
		$reorder_level += $item->reorder_level;
		$quantity += $item->quantity;
	}
	
	return array(
		'name' => '-',
		'category' => '-',
		'location_name'=> '-',
		'quantity' => number_format($quantity, 2),
		'reorder_level' => '<b>'. number_format($reorder_level, 2).'</b>'
	);
}

function get_beginning_end_inv_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('date_started' => 'Date Started'),
		array('date_ended' => 'Date Ended'),
		array('name' => 'Item Name'),
		array('location_name' => 'Location Name'),
		array('user_started' => 'Started By'),
		array('user_ended' => 'Ended By'),
		array('shift_name' => 'Shift Name'),
		array('start_quantity' => 'Start Qty'),
		array('end_quantity' => 'End Qty'),
		array('sold_quantity' => 'Sold Qty')
	);

	return transform_headers(array_merge($headers, array(array('receipt' => '&nbsp', 'sortable' => FALSE))), FALSE, FALSE);
}

function get_beginning_end_inv_data_row($item, $controller)
{
	$CI =& get_instance();
	$controller_name = $CI->uri->segment(1);
	
	$row = array (
		'date_started' =>  date('Y-m-d h:i A', strtotime($item->date_started)),
		'date_ended' =>  date('Y-m-d h:i A', strtotime($item->date_ended)),
		'name' =>  $item->name,
		'location_name'=> $item->location_name,
		'user_started' =>  $item->user_started,
		'user_ended' =>  $item->user_ended,
		'shift_name'=> $item->shift_name,
		'start_quantity' =>  number_format($item->start_quantity, 2),
		'end_quantity' => number_format($item->end_quantity, 2),
		'sold_quantity' => number_format($item->sold_quantity, 2),
	);
	return $row;
}

function get_beginning_end_inv_data_last_row($items, $controller)
{
	$CI =& get_instance();
	$reorder_level = 0;
	$start_quantity = 0;
	$end_quantity = 0;
	$sold_quantity = 0;

	foreach($items->result() as $key=>$item)
	{
		$end_quantity += $item->end_quantity;
		$start_quantity += $item->start_quantity;
		$sold_quantity += $item->sold_quantity;
	}
	return array(
		'date_started' => '-',
		'date_ended' => '-',
		'name' => '-',
		'location_name'=> '-',
		'user_started' => '-',
		'user_ended' => '-',
		'shift_name'=> '-',
		'start_quantity' => '<b>'. number_format($start_quantity, 2).'</b>',
		'end_quantity' => '<b>'. number_format($end_quantity, 2).'</b>',
		'sold_quantity' => '<b>'. number_format($sold_quantity, 2).'</b>'
	);
}

/*
Get the sales payments summary
*/
function get_sales_manage_payments_summary($payments, $sales, $controller)
{
	$CI =& get_instance();
	$table = '<div id="report_summary">';
	$amount = 0;
	$change_due = 0;
	foreach($payments as $key=>$payment)
	{
		$amount += $payment['payment_amount'];
		// WARNING: the strong assumption here is that if a change is due it was a cash transaction always
		// therefore we remove from the total cash amount any change due
		/*if( $payment['payment_type'] == $CI->lang->line('sales_cash') )
		{
			
		}*/
	}
	foreach($sales->result_array() as $key=>$sale)
	{
		$change_due += $sale['change_due'];
	}
	$amount = $amount - $change_due;
	$table .= '<div class="summary_row"> Cash: ' . number_format($amount, 2) . '</div>';
	$table .= '</div>';

	return $table;
}

function transform_headers_readonly($array)
{
	$result = array();
	foreach($array as $key => $value)
	{
		$result[] = array('field' => $key, 'title' => $value, 'sortable' => $value != '', 'switchable' => !preg_match('(^$|&nbsp)', $value));
	}

	return json_encode($result);
}

function transform_headers($array, $readonly = FALSE, $editable = TRUE)
{
	$result = array();

	if (!$readonly)
	{
		$array = array_merge(array(array('checkbox' => 'select', 'sortable' => FALSE)), $array);
	}

	if ($editable)
	{
		$array[] = array('edit' => '');
	}

	foreach($array as $element)
	{
		reset($element);
		$result[] = array('field' => key($element),
			'title' => current($element),
			'switchable' => isset($element['switchable']) ?
				$element['switchable'] : !preg_match('(^$|&nbsp)', current($element)),
			'sortable' => isset($element['sortable']) ?
				$element['sortable'] : current($element) != '',
			'checkbox' => isset($element['checkbox']) ?
				$element['checkbox'] : FALSE,
			'class' => isset($element['checkbox']) || preg_match('(^$|&nbsp)', current($element)) ?
				'print_hide' : '',
			'sorter' => isset($element['sorter']) ?
				$element ['sorter'] : '');
	}
	return json_encode($result);
}

function get_people_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('people.person_id' => $CI->lang->line('common_id')),
		array('last_name' => $CI->lang->line('common_last_name')),
		array('first_name' => $CI->lang->line('common_first_name')),
		array('email' => $CI->lang->line('common_email')),
		array('phone_number' => $CI->lang->line('common_phone_number'))
	);

	if($CI->Employee->has_grant('messages', $CI->session->userdata('person_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	return transform_headers($headers);
}

function get_person_data_row($person, $controller)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));

	return array (
		'people.person_id' => $person->person_id,
		'last_name' => $person->last_name,
		'first_name' => $person->first_name,
		'email' => empty($person->email) ? '' : mailto($person->email, $person->email),
		'phone_number' => $person->phone_number,
		'messages' => empty($person->phone_number) ? '' : anchor("Messages/view/$person->person_id", '<span class="glyphicon glyphicon-phone"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('messages_sms_send'))),
		'edit' => anchor($controller_name."/view/$person->person_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
	));
}

function get_customer_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('people.person_id' => $CI->lang->line('common_id')),
		array('last_name' => $CI->lang->line('common_last_name')),
		array('first_name' => $CI->lang->line('common_first_name')),
		array('email' => $CI->lang->line('common_email')),
		array('phone_number' => $CI->lang->line('common_phone_number')),
		array('total' => $CI->lang->line('common_total_spent'), 'sortable' => FALSE)
	);

	if($CI->Employee->has_grant('messages', $CI->session->userdata('person_id')))
	{
		$headers[] = array('messages' => '', 'sortable' => FALSE);
	}

	return transform_headers($headers);
}

function get_customer_data_row($person, $stats, $controller)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));

	return array (
		'people.person_id' => $person->person_id,
		'last_name' => $person->last_name,
		'first_name' => $person->first_name,
		'email' => empty($person->email) ? '' : mailto($person->email, $person->email),
		'phone_number' => $person->phone_number,
		'total' => to_currency($stats->total),
		'messages' => empty($person->phone_number) ? '' : anchor("Messages/view/$person->person_id", '<span class="glyphicon glyphicon-phone"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('messages_sms_send'))),
		'edit' => anchor($controller_name."/view/$person->person_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
	));
}

function get_suppliers_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('people.person_id' => $CI->lang->line('common_id')),
		array('company_name' => $CI->lang->line('suppliers_company_name')),
		array('agency_name' => $CI->lang->line('suppliers_agency_name')),
		array('last_name' => $CI->lang->line('common_last_name')),
		array('first_name' => $CI->lang->line('common_first_name')),
		array('email' => $CI->lang->line('common_email')),
		array('phone_number' => $CI->lang->line('common_phone_number'))
	);

	if($CI->Employee->has_grant('messages', $CI->session->userdata('person_id')))
	{
		$headers[] = array('messages' => '');
	}

	return transform_headers($headers);
}

function get_supplier_data_row($supplier, $controller)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));

	return array (
		'people.person_id' => $supplier->person_id,
		'company_name' => $supplier->company_name,
		'agency_name' => $supplier->agency_name,
		'last_name' => $supplier->last_name,
		'first_name' => $supplier->first_name,
		'email' => empty($supplier->email) ? '' : mailto($supplier->email, $supplier->email),
		'phone_number' => $supplier->phone_number,
		'messages' => empty($supplier->phone_number) ? '' : anchor("Messages/view/$supplier->person_id", '<span class="glyphicon glyphicon-phone"></span>',
			array('class'=>"modal-dlg", 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line('messages_sms_send'))),
		'edit' => anchor($controller_name."/view/$supplier->person_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>"modal-dlg", 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update')))
		);
}

function get_categories_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('categories.category_id' => "Id"),
		array('name' => "Category Name"),
		array('description' => "Description"),
		array('item_pic' => $CI->lang->line('items_image'), 'sortable' => FALSE),
		array('upload_pic' => '')
	);

	return transform_headers($headers);
}

function get_category_data_row($category, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	$image = NULL;
	if ($category->pic_filename != '')
	{
		$ext = pathinfo($category->pic_filename, PATHINFO_EXTENSION);
		if($ext == '')
		{
			// legacy
			$images = glob('assets/uploads/item_pics/' . $category->pic_filename . '.*');
		}
		else
		{
			// preferred
			$images = glob('assets/uploads/item_pics/' . $category->pic_filename);
		}

		if (sizeof($images) > 0)
		{
			$image .= '<a class="rollover" href="'. base_url($images[0]) .'"><img src="'.site_url('items/pic_thumb/' . pathinfo($images[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	if(in_array("Edit Categories", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'categories.category_id' => $category->category_id,
		'name' => $category->name,
		'description' => $category->description,
		'item_pic' => $image,
		'upload_pic' => anchor("categories/upload_category_pic/$category->category_id", '<span class="fa fa-image"></span>',
			array('class' => 'modal-dlg item_pic ', 'data-btn-hidden' => 'Submit', 'title' => $CI->lang->line($controller_name.'_update'))
		),
		'edit' => anchor("categories/view/$category->category_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_sub_categories_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('name' => "Sub Category Name"),
		array('category_name' => "Category Name"),
		array('order_no' => "Order No"),
		array('item_pic' => $CI->lang->line('items_image'), 'sortable' => FALSE),
		array('items' => '')
	);

	return transform_headers($headers);
}

function get_sub_category_data_row($sub_category, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	$image = NULL;
	if ($sub_category->pic_filename != '')
	{
		$ext = pathinfo($sub_category->pic_filename, PATHINFO_EXTENSION);
		if($ext == '')
		{
			// legacy
			$images = glob('assets/uploads/item_pics/' . $sub_category->pic_filename . '.*');
		}
		else
		{
			// preferred
			$images = glob('assets/uploads/item_pics/' . $sub_category->pic_filename);
		}

		if (sizeof($images) > 0)
		{
			$image .= '<a class="rollover" href="'. base_url($images[0]) .'"><img src="'.site_url('items/pic_thumb/' . pathinfo($images[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	if(in_array("Edit Categories", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'name' => $sub_category->name,
		'category_name' => $sub_category->category_name,
		'order_no' => $sub_category->order_no,
		'item_pic' => $image,
		'items' => anchor("sub_categories/items/$sub_category->sub_category_id/$sub_category->category_id/$sub_category->order_no", '<span class="glyphicon glyphicon-tags'.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		),
		'edit' => anchor("sub_categories/view/$sub_category->sub_category_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_passcodes_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('passcodes.passcode_id' => "Id"),
		array('password' => "PassCode")
	);

	return transform_headers($headers);
}

function get_passcode_data_row($passcode, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Passcode", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'passcodes.passcode_id' => $passcode->passcode_id,
		'password' => $passcode->password,
		'edit' => anchor("passcodes/view/$passcode->passcode_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_shifts_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_pos_shifts.pos_shift_id' => "Id"),
		array('shift_name' => "Shift Name")
	);

	return transform_headers($headers);
}

function get_shift_data_row($shift, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Passcode", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'ssp_pos_shifts.pos_shift_id' => $shift->pos_shift_id,
		'shift_name' => $shift->shift_name,
		'edit' => anchor("shifts/view/$shift->pos_shift_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_customer_types_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('customer_types.customer_type_id' => "Id"),
		array('name' => "Name"),
		array('discount_percent' => "Discount%")
	);

	return transform_headers($headers);
}

function get_customer_type_data_row($customer_type, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Customer type", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'customer_types.customer_type_id' => $customer_type->customer_type_id,
		'name' => $customer_type->name,
		'discount_percent' => $customer_type->discount_percent,
		'edit' => anchor("customer_types/view/$customer_type->customer_type_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_expenses_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('or_no' => "OR No."),
		array('description' => "Description"),
		array('amount' => "Amount")
	);

	return transform_headers($headers);
}

function get_expenses_data_row($expenses, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Expenses", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'or_no' => $expenses->or_no,
		'description' => $expenses->description,
		'amount' => $expenses->amount,
		'edit' => anchor("expenses/view/$expenses->expense_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_store_profile_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_stores.store_id' => "Id"),
		array('name' => "Store Name"),
		array('show_users' => '')
	);

	return transform_headers($headers);
}

function get_store_profile_data_row($store_manager, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));
	
	return array (
		'ssp_stores.store_id' => $store_manager->store_id,
		'name' => $store_manager->name,
		'show_users' => anchor("Store_profile/view_recent_users/$store_manager->store_id", '<span class="fa fa-list"></span>',
			array('data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_store_manager_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_stores.store_id' => "Id"),
		array('name' => "Store Name"),
		array('description' => "Description"),
		array('edit_access_role' => ''),
		array('edit_access' => '')
	);

	return transform_headers($headers);
}

function get_store_manager_data_row($store_manager, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Store", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}
	
	return array (
		'ssp_stores.store_id' => $store_manager->store_id,
		'name' => $store_manager->name,
		'description' => $store_manager->description,
		'edit_access_role' => anchor("Store_manager/role_access/$store_manager->store_id", '<span class="fa fa-key"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		),
		'edit_access_role' => anchor("Store_manager/role_access/$store_manager->store_id", '<span class="fa fa-key"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		),
		'edit_access' => anchor("Store_manager/edit_access/$store_manager->store_id", '<span class="fa fa-arrow-circle-right"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		),
		'edit' => anchor("Store_manager/view/$store_manager->store_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => 'Editing Store:'.$store_manager->name)
		)
		
	);
}

function get_free_item_promo_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_free_items.free_item_id' => "Id"),
		array('name' => "Name")
	);

	return transform_headers($headers, FALSE, TRUE);
}

function get_free_item_promos_data_row($promo_free_item, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Store", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}
	
	return array (
		'ssp_free_items.free_item_id' => $promo_free_item->free_item_id,
		'name' => $promo_free_item->name,
		'edit' => anchor("Promo_Free_Items/view/$promo_free_item->free_item_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_item_promo_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_promo_items.promo_item_id' => "Id"),
		array('name' => "Name"),
		array('orig_price' => "Orig Price"),
		array('discount_percent' => "Discount%"),
		array('unit_price' => "Unit Price")
	);

	return transform_headers($headers, FALSE, TRUE);
}

function get_item_promos_data_row($promo_item, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Store", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}
	
	return array (
		'ssp_promo_items.promo_item_id' => $promo_item->promo_item_id,
		'name' => $promo_item->name,
		'orig_price' => number_format($promo_item->orig_price, 2),
		'discount_percent' => number_format($promo_item->discount_percent, 0),
		'unit_price' => number_format($promo_item->unit_price, 2),
		'edit' => anchor("Promo_Items/view/$promo_item->promo_item_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_item_material_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('item_materials.item_material_id' => "Id"),
		array('name' => "Name"),
		array('qty' => "Used Qty")
	);

	return transform_headers($headers, FALSE, TRUE);
}

function get_item_material_data_row($promo_item, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Store", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}
	
	return array (
		'item_materials.item_material_id' => $promo_item->item_material_id,
		'name' => $promo_item->name,
		'qty' => $promo_item->use_quantity
	);
}

function get_point_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_store_point_settings.store_point_setting_id' => "Id"),
		array('store' => "Store"),
		array('type' => "Type"),
		array('points_or_card' => "Points/Card"),
		array('points' => "Amount Accumulated/1 Point")
	);

	return transform_headers($headers, FALSE, TRUE);
}

function get_point_data_row($promo_item, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));
	
	return array (
		'ssp_store_point_settings.store_point_setting_id' => $promo_item->store_point_setting_id,
		'store' => $promo_item->store,
		'type' => $promo_item->status == 0 ? 'Amount Accumulated' : 'Use Product',
		'points_or_card' => $promo_item->points_or_card == 0 ? 'Points' : 'Card',
		'points' => $promo_item->payment_to_points,
		'edit' => anchor("Points/view/$promo_item->store_point_setting_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_item_reward_items_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_item_rewards.item_reward_id' => "Id"),
		array('name' => "Name"),
		array('orig_price' => "Orig Price"),
		array('discount_percent' => "Discount%"),
		array('unit_price' => "Unit Price")
	);

	return transform_headers($headers, FALSE, TRUE);
}

function get_item_reward_items_data_row($promo_item, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Store", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}
	
	return array (
		'ssp_item_rewards.item_reward_id' => $promo_item->item_reward_id,
		'name' => $promo_item->name,
		'orig_price' => number_format($promo_item->orig_price, 2),
		'discount_percent' => number_format($promo_item->discount_percent, 0),
		'unit_price' => number_format($promo_item->unit_price, 2),
		'edit' => anchor("Promo_Items/view/$promo_item->item_reward_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_rewards_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_rewards.reward_id' => "Id"),
		array('name' => "Rewards"),
		array('price' => "Price"),
		array('discount_percent' => "Discount%"),
		array('specific_price' => "Fixed Price"),
		array('no_of_free_items' => "Free Items"),
		array('from_date' => "From Date"),
		array('to_date' => "To Date"),
		array('reward_banner' => "Banner", 'sortable' => FALSE),
		array('view_free_items' => ''),
		array('view_items' => '')
	);

	return transform_headers($headers, FALSE, TRUE);
}

function get_rewards_data_row($reward, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	$image = NULL;

	if ($reward->no_of_free_items > 0)
	{
		$b = '';
	}
	else{
		$b = 'hidden';
	}

	if ($reward->reward_banner != '')
	{
		$ext = pathinfo($reward->reward_banner, PATHINFO_EXTENSION);
		if($ext == '')
		{
			$images = glob('assets/uploads/item_pics/' . $reward->reward_banner . '.*');
		}
		else
		{
			$images = glob('assets/uploads/item_pics/' . $reward->reward_banner);
		}

		if (sizeof($images) > 0)
		{
			$image .= '<a class="rollover" href="'. base_url($images[0]) .'"><img src="'.site_url('items/pic_thumb/' . pathinfo($images[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	if(in_array("Edit Store", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'ssp_rewards.reward_id' => $reward->reward_id,
		'name' => $reward->name,
		'price' => $reward->price,
		'discount_percent' => $reward->discount_percent,
		'specific_price' => $reward->specific_price,
		'no_of_free_items' => $reward->no_of_free_items,
		'from_date' => $reward->from_date,
		'to_date' => $reward->to_date,
		'reward_banner' => $image,
		'view_free_items'  => anchor("Reward_Free_Items/view_free_item_promo/$reward->reward_id", '<span class="fa fa-gift '.$b.'"></span>'),
		'view_items' => anchor("Item_Reward_Items/view_item_reward/$reward->reward_id", '<span class="glyphicon glyphicon-tag '.$a.'"></span>'),
		'edit' => anchor("Item_Rewards/view/$reward->reward_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_promos_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_promos.promo_id' => "Id"),
		array('name' => "Promo"),
		array('discount_percent' => "Discount%"),
		array('specific_price' => "Fixed Price"),
		array('no_of_free_items' => "Free Items"),
		array('from_date' => "From Date"),
		array('to_date' => "To Date"),
		array('promo_banner' => "Banner", 'sortable' => FALSE),
		array('view_free_items' => ''),
		array('view_items' => '')
	);

	return transform_headers($headers, FALSE, TRUE);
}

function get_promos_data_row($promo, $controller, $moduleName)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	$image = NULL;

	if ($promo->no_of_free_items > 0)
	{
		$b = '';
	}
	else{
		$b = 'hidden';
	}

	if ($promo->promo_banner != '')
	{
		$ext = pathinfo($promo->promo_banner, PATHINFO_EXTENSION);
		if($ext == '')
		{
			$images = glob('assets/uploads/item_pics/' . $promo->promo_banner . '.*');
		}
		else
		{
			$images = glob('assets/uploads/item_pics/' . $promo->promo_banner);
		}

		if (sizeof($images) > 0)
		{
			$image .= '<a class="rollover" href="'. base_url($images[0]) .'"><img src="'.site_url('items/pic_thumb/' . pathinfo($images[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	if(in_array("Edit Store", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}

	return array (
		'ssp_promos.promo_id' => $promo->promo_id,
		'name' => $promo->name,
		'discount_percent' => $promo->discount_percent,
		'specific_price' => $promo->specific_price,
		'no_of_free_items' => $promo->no_of_free_items,
		'from_date' => $promo->from_date,
		'to_date' => $promo->to_date,
		'promo_banner' => $image,
		'view_free_items'  => anchor("Promo_Free_Items/view_free_item_promo/$promo->promo_id", '<span class="fa fa-gift '.$b.'"></span>'),
		'view_items' => anchor("Promo_Items/view_item_promo/$promo->promo_id", '<span class="glyphicon glyphicon-tag '.$a.'"></span>'),
		'edit' => anchor("Promos/view/$promo->promo_id", '<span class="glyphicon glyphicon-edit '.$a.'"></span>',
			array('class' => 'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		)
	);
}

function get_password_manager_manage_table_headers()
{
	$CI =& get_instance();
	$headers = array(
		array('ssp_users.userId' => "Id"),
		array('name' => "Name"),
		array('username' => "Username"),
		array('password' => "Password")
	);

	return transform_headers($headers, FALSE, FALSE);
}

function get_password_manager_data_row($password_manager, $controller)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));
	
	return array (
		'ssp_users.userId' => $password_manager->userId,
		'name' => $password_manager->name,
		'username' => $password_manager->username
	);
}

function get_items_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('items.item_id' => $CI->lang->line('common_id')),
		array('item_number' => $CI->lang->line('items_item_number')),
		array('name' => $CI->lang->line('items_name')),
		array('category' => $CI->lang->line('items_category')),
		array('cost_price' => $CI->lang->line('items_cost_price')),
		array('unit_price' => $CI->lang->line('items_unit_price')),
		array('quantity' => $CI->lang->line('items_quantity')),
		array('item_pic' => $CI->lang->line('items_image'), 'sortable' => FALSE),
		array('upload_pic' => ''),
		array('inventory' => ''),
		array('stock' => ''),
		array('materials' => ''),
		array('price' => '')
	);

	return transform_headers($headers);
}

function get_item_data_row($item, $controller, $moduleName)
{
	$CI =& get_instance();
	$item_tax_info = $CI->Item_taxes->get_info($item->item_id);
	$tax_percents = '';
	foreach($item_tax_info as $tax_info)
	{
		$tax_percents .= to_tax_decimals($tax_info['percent']) . '%, ';
	}
	// remove ', ' from last item
	$tax_percents = substr($tax_percents, 0, -2);
	$controller_name = strtolower(get_class($CI));

	$image = NULL;
	if ($item->pic_filename != '')
	{
		$ext = pathinfo($item->pic_filename, PATHINFO_EXTENSION);
		if($ext == '')
		{
			// legacy
			$images = glob('assets/uploads/item_pics/' . $item->pic_filename . '.*');
		}
		else
		{
			// preferred
			$images = glob('assets/uploads/item_pics/' . $item->pic_filename);
		}

		if (sizeof($images) > 0)
		{
			$image .= '<a class="rollover" href="'. base_url($images[0]) .'"><img src="'.site_url('items/pic_thumb/' . pathinfo($images[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	if(in_array("Edit Item", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}
	if(in_array("Update Inventory", $moduleName)){
		$b = '';
	}
	else{
		$b = 'hidden';
	}

	return array (
		'items.item_id' => $item->item_id,
		'item_number' => $item->item_number,
		'name' => $item->name,
		'category' => $item->category,
		'cost_price' => number_format($item->cost_price, 2),
		'unit_price' => number_format($item->unit_price, 2),
		'quantity' => number_format($item->quantity, 2, '.', ''),
		'item_pic' => $image,
		'upload_pic' => anchor($controller_name."/upload_item_pic/$item->item_id", '<span class="fa fa-image"></span>',
			array('class' => 'modal-dlg item_pic '.$b, 'data-btn-hidden' => 'Submit', 'title' => $CI->lang->line($controller_name.'_count'))
		),
		'inventory' => anchor($controller_name."/inventory/$item->item_id", '<span class="glyphicon glyphicon-pushpin"></span>',
			array('class' => 'modal-dlg '.$b, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_count'))
		),
		'stock' => anchor($controller_name."/count_details/$item->item_id", '<span class="glyphicon glyphicon-list-alt"></span>',
			array('class' => 'modal-dlg', 'title' => $CI->lang->line($controller_name.'_details_count'))
		),
		'materials' => anchor($controller_name."/view_item_materials/$item->item_id", '<span class="glyphicon glyphicon-book"></span>'
		),
		'price' => anchor($controller_name."/price_change/$item->item_id", '<span style="font-size:17px">&#8369;</span>',
			array('class' => 'modal-dlg '.$a, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		),
		'edit' => anchor($controller_name."/view/$item->item_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class' => 'modal-dlg '.$a, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		));
}

function get_items_material_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('items.item_id' => $CI->lang->line('common_id')),
		array('name' => 'Ingredient Name'),
		array('quantity' => $CI->lang->line('items_quantity')),
		array('stock' => ''),
		array('inventory' => ''),
		array('mix_ingredients' => '')
	);

	return transform_headers($headers);
}

function get_item_material_material_data_row($item, $controller, $moduleName)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));

	if(in_array("Edit Item", $moduleName)){
		$a = '';
	}
	else{
		$a = 'hidden';
	}
	if(in_array("Update Inventory", $moduleName)){
		$b = '';
	}
	else{
		$b = 'hidden';
	}

	return array (
		'items.item_id' => $item->item_id,
		'name' => $item->name,
		'quantity' => number_format($item->quantity, 2).' '.$item->unit_abbreviation,
		'stock' => anchor("materials/count_details/$item->item_id", '<span class="glyphicon glyphicon-list-alt"></span>',
			array('class' => 'modal-dlg', 'title' => $CI->lang->line($controller_name.'_details_count'))
		),
		/*'inventory' => anchor("materials/inventory/$item->item_material_id", '<span class="glyphicon glyphicon-pushpin"></span>',
			array('class' => 'modal-dlg '.$b, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_count'))
		),
		'price' => anchor("materials/price_change/$item->item_material_id", '<span style="font-size:17px">&#8369;</span>',
			array('class' => 'modal-dlg '.$a, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		),*/
		'inventory' => anchor($controller_name."/inventory/$item->item_id", '<span class="glyphicon glyphicon-pushpin"></span>',
			array('class' => 'modal-dlg '.$b, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_count'))
		),
		'mix_ingredients' => anchor("materials/mix_ingredients/$item->item_id", '<span class="glyphicon glyphicon-plus-sign"></span>',
			array('class' => 'modal-dlg '.$b, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_count'))
		),
		'edit' => anchor("materials/view/$item->item_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class' => 'modal-dlg '.$a, 'data-btn-submit' => $CI->lang->line('common_submit'), 'title' => $CI->lang->line($controller_name.'_update'))
		));

}

function get_mobile_setting_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('ssp_mobile_settings.mobile_setting_id' => $CI->lang->line('common_id')),
		array('name' => 'Store Name'),
		array('weekly_special' => 'Weekly Special', 'sortable' => FALSE),
		array('menu' => 'Menu', 'sortable' => FALSE),
		array('promo' => 'Promo', 'sortable' => FALSE),
		array('rewards' => 'Rewards', 'sortable' => FALSE)
	);

	return transform_headers($headers);
}

function get_mobile_setting_data_row($item, $controller, $moduleName)
{
	$weekly_special = NULL;
	if ($item->weekly_special != '')
	{
		$ext = pathinfo($item->weekly_special, PATHINFO_EXTENSION);
		if($ext == '')
		{
			// legacy
			$weekly_specials = glob('assets/uploads/item_pics/' . $item->weekly_special . '.*');
		}
		else
		{
			// preferred
			$weekly_specials = glob('assets/uploads/item_pics/' . $item->weekly_special . '.*');
		}
		
		if (sizeof($weekly_specials) > 0)
		{
			$weekly_special .= '<a class="rollover" href="'. base_url($weekly_specials[0]) .'"><img src="'.site_url('Mobile_Settings/pic_thumb/' . pathinfo($weekly_specials[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	$menu = NULL;
	if ($item->menu != '')
	{
		$ext = pathinfo($item->menu, PATHINFO_EXTENSION);
		if($ext == '')
		{
			// legacy
			$menus = glob('assets/uploads/item_pics/' . $item->menu . '.*');
		}
		else
		{
			// preferred
			$menus = glob('assets/uploads/item_pics/' . $item->menu);
		}

		if (sizeof($menus) > 0)
		{
			$menu .= '<a class="rollover" href="'. base_url($menus[0]) .'"><img src="'.site_url('Mobile_Settings/pic_thumb/' . pathinfo($menus[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	$promo = NULL;
	if ($item->menu != '')
	{
		$ext = pathinfo($item->promo, PATHINFO_EXTENSION);

		if($ext == '')
		{
			// legacy
			$promos = glob('assets/uploads/item_pics/' . $item->promo . '.*');
		}
		else
		{
			// preferred
			$promos = glob('assets/uploads/item_pics/' . $item->promo);
		}
		if (sizeof($promos) > 0)
		{
			$promo .= '<a class="rollover" href="'. base_url($promos[0]) .'"><img src="'.site_url('Mobile_Settings/pic_thumb/' . pathinfo($promos[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	$reward = NULL;
	if ($item->rewards != '')
	{
		$ext = pathinfo($item->rewards, PATHINFO_EXTENSION);
		if($ext == '')
		{
			// legacy
			$rewards = glob('assets/uploads/item_pics/' . $item->rewards . '.*');
		}
		else
		{
			// preferred
			$rewards = glob('assets/uploads/item_pics/' . $item->rewards);
		}

		if (sizeof($rewards) > 0)
		{
			$reward .= '<a class="rollover" href="'. base_url($rewards[0]) .'"><img src="'.site_url('Mobile_Settings/pic_thumb/' . pathinfo($rewards[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}


	return array (
		'ssp_mobile_settings.mobile_setting_id' => $item->mobile_setting_id,
		'name' => $item->name,
		'weekly_special' => $weekly_special,
		'menu' => $menu,
		'promo' => $promo,
		'rewards' => $reward,
		'edit' => anchor("Mobile_Settings/view/$item->mobile_setting_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class' => 'modal-dlg ', 'data-btn-submit' => 'Submit', 'title' => 'Edit')
		)
	);
}

function get_machine_setting_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('ssp_machine.machine_id' => $CI->lang->line('common_id')),
		array('machine_identification_no' => 'Machine Identification No'),
		array('serial_no' => 'Serial No'),
		array('terminal_no' => 'Terminal No'),
		array('active' => 'Active')
	);

	return transform_headers($headers);
}

function get_machine_setting_data_row($machine_setting_row, $controller, $moduleName)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	return array (
		'machine_id' => $machine_setting_row->machine_id,
		'machine_identification_no' => $machine_setting_row->machine_identification_no,
		'serial_no' => $machine_setting_row->serial_no,
		'terminal_no' => $machine_setting_row->terminal_no,
		'active' =>$machine_setting_row->active,
		'edit' => anchor($controller_name."/view/$machine_setting_row->machine_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
		));
}

function get_brand_setting_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('ssp_brand_settings.brand_setting_id' => $CI->lang->line('common_id')),
		array('store_name' => 'Store Name'),
		array('tin' => 'TIN'),
		array('main_logo' => 'Main Logo', 'sortable' => FALSE),
		array('receipt_name' => 'Receipt Name'),
		array('receipt_logo' => 'Receipt Logo', 'sortable' => FALSE)
	);

	return transform_headers($headers);
}

function get_brand_setting_data_row($item, $controller, $moduleName)
{
	$main_logo = NULL;
	if ($item->main_logo != '')
	{
		$ext = pathinfo($item->main_logo, PATHINFO_EXTENSION);
		if($ext == '')
		{
			$main_logos = glob('assets/uploads/item_pics/' . $item->main_logo . '.*');
		}
		else
		{
			$main_logos = glob('assets/uploads/item_pics/' . $item->main_logo);
		}

		if (sizeof($main_logos) > 0)
		{
		$main_logo .= '<a class="rollover" href="'. base_url($main_logos[0]) .'"><img src="'.site_url('Web_Branding/pic_thumb/' . pathinfo($main_logos[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	$receipt_logo = NULL;
	if ($item->receipt_logo != '')
	{
		$ext = pathinfo($item->receipt_logo, PATHINFO_EXTENSION);
		if($ext == '')
		{
			// legacy
			$receipt_logos = glob('assets/uploads/item_pics/' . $item->receipt_logo . '.*');
		}
		else
		{
			// preferred
			$receipt_logos = glob('assets/uploads/item_pics/' . $item->receipt_logo);
		}

		if (sizeof($receipt_logos) > 0)
		{
			$receipt_logo .= '<a class="rollover" href="'. base_url($receipt_logos[0]) .'"><img src="'.site_url('Web_Branding/pic_thumb/' . pathinfo($receipt_logos[0], PATHINFO_BASENAME)) . '"></a>';
		}
	}

	return array (
		'ssp_brand_settings.brand_setting_id' => $item->brand_setting_id,
		'store_name' => $item->store_name,
		'tin' => $item->tin,
		'main_logo' => $main_logo,
		'receipt_name' => $item->receipt_name,
		'receipt_logo' => $receipt_logo,
		'edit' => anchor("Web_Branding/view/$item->brand_setting_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class' => 'modal-dlg ', 'data-btn-submit' => 'Submit', 'title' => 'Edit')
		)
	);
}

function get_giftcards_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('giftcard_id' => $CI->lang->line('common_id')),
		array('last_name' => $CI->lang->line('common_last_name')),
		array('first_name' => $CI->lang->line('common_first_name')),
		array('giftcard_number' => $CI->lang->line('giftcards_giftcard_number')),
		array('value' => $CI->lang->line('giftcards_card_value'))
	);

	return transform_headers($headers);
}

function get_taxes_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('tax_code' => $CI->lang->line('taxes_tax_code')),
		array('tax_code_name' => $CI->lang->line('taxes_tax_code_name')),
		array('tax_code_type_name' => $CI->lang->line('taxes_tax_code_type')),
		array('tax_rate' => $CI->lang->line('taxes_tax_rate')),
		array('rounding_code_name' => $CI->lang->line('taxes_rounding_code')),
		array('city' => $CI->lang->line('common_city')),
		array('state' => $CI->lang->line('common_state'))
	);

	return transform_headers($headers);
}

function get_giftcard_data_row($giftcard, $controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	return array (
		'giftcard_id' => $giftcard->giftcard_id,
		'last_name' => $giftcard->last_name,
		'first_name' => $giftcard->first_name,
		'giftcard_number' => $giftcard->giftcard_number,
		'value' => to_currency($giftcard->value),
		'edit' => anchor($controller_name."/view/$giftcard->giftcard_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
		));
}

function get_tax_data_row($tax_code_row, $controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	return array (
		'tax_code' => $tax_code_row->tax_code,
		'tax_code_name' => $tax_code_row->tax_code_name,
		'tax_code_type' => $tax_code_row->tax_code_type,
		'tax_rate' => $tax_code_row->tax_rate,
		'rounding_code' =>$tax_code_row->rounding_code,
		'tax_code_type_name' => $CI->Tax->get_tax_code_type_name($tax_code_row->tax_code_type),
		'rounding_code_name' => Rounding_mode::get_rounding_code_name($tax_code_row->rounding_code),
		'city' => $tax_code_row->city,
		'state' => $tax_code_row->state,
		'edit' => anchor($controller_name."/view/$tax_code_row->tax_code", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
		));
}

function get_item_kits_manage_table_headers()
{
	$CI =& get_instance();

	$headers = array(
		array('item_kit_id' => $CI->lang->line('item_kits_kit')),
		array('name' => $CI->lang->line('item_kits_name')),
		array('description' => $CI->lang->line('item_kits_description')),
		array('cost_price' => $CI->lang->line('items_cost_price'), 'sortable' => FALSE),
		array('unit_price' => $CI->lang->line('items_unit_price'), 'sortable' => FALSE)
	);

	return transform_headers($headers);
}

function get_item_kit_data_row($item_kit, $controller)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));

	return array (
		'item_kit_id' => $item_kit->item_kit_id,
		'name' => $item_kit->name,
		'description' => $item_kit->description,
		'cost_price' => to_currency($item_kit->total_cost_price),
		'unit_price' => to_currency($item_kit->total_unit_price),
		'edit' => anchor($controller_name."/view/$item_kit->item_kit_id", '<span class="glyphicon glyphicon-edit"></span>',
			array('class'=>'modal-dlg', 'data-btn-submit' => $CI->lang->line('common_submit'), 'title'=>$CI->lang->line($controller_name.'_update'))
		));
}
?>
