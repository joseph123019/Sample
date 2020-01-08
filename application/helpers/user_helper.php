<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSaleDetails($sale_id)
{
   $CI =& get_instance();
   return $CI->logs_model->get_sales_items($sale_id);
   //$conditions = array('sale_id'=>$sale_id);

}// end function
function getSaleTax($sale_id, $item_id)
{
    $CI =& get_instance();
    return $CI->logs_model->get_sale_items_taxes($sale_id, $item_id);
}// end function
?>