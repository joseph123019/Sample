<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Client_model extends CI_Model
{
    function clientListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.clientId, BaseTbl.clientName, BaseTbl.clientAddress, BaseTbl.numberOfStore, BaseTbl.notes, BaseTbl.clientURL, BaseTbl.status');
        $this->db->from('ssp_clients as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.clientName  LIKE '%".$searchText."%'
                            OR  BaseTbl.clientAddress  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    function get_client_logo($clientId){

        $query = $this->db->get_where('ssp_brand_settings', array('client_id' => $clientId));
        //return $query->row();
        return $query->result_array(); 

    }

    function clientListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.clientId, BaseTbl.clientName, BaseTbl.clientAddress, BaseTbl.numberOfStore, BaseTbl.notes, BaseTbl.clientURL, IF(BaseTbl.status = 1, "Active", "Inactive") AS status');
        $this->db->from('ssp_clients as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.clientName  LIKE '%".$searchText."%'
                            OR  BaseTbl.clientAddress  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();    
        return $result;
    }

    function getAllClients()
    {
        $this->db->select('BaseTbl.clientId, BaseTbl.clientName, BaseTbl.clientAddress, BaseTbl.numberOfStore, BaseTbl.notes, BaseTbl.clientURL, IF(BaseTbl.status = 1, "Active", "Inactive") AS status');
        $this->db->from('ssp_clients as BaseTbl');
        $query = $this->db->get();
        
        $result = $query->result();    
        return $result;
    }

    function getAllClientsNotifations()
    {
        $this->db->select('BaseTbl.client_notification_id, BaseTbl.client_id, BaseTbl.notification_id');
        $this->db->from('ssp_client_notifications as BaseTbl');
        $query = $this->db->get();
        
        $result = $query->result_array();    
        return $result;
    }

    function checkClientNameExists($clientName, $clientId = 0)
    {
        $this->db->select("clientName");
        $this->db->from("ssp_clients");
        $this->db->where("clientName", $clientName);
        if($clientId != 0){
            $this->db->where("clientId !=", $clientId);
        }
        $query = $this->db->get();

        return $query->result();
    }

    function get_client_via($username)
    {
        $this->db->select("ssp_clients.*");
        $this->db->from("ssp_clients");
        $this->db->join('ssp_users as user', 'user.clientId = ssp_clients.clientId');
        $this->db->where("user.wp_username", $username);
        $query = $this->db->get();

        return $query->result();
    }

    function addNewClient($clientInfo)
    {
        $this->db->trans_start();
        
        $this->db->insert('ssp_clients', $clientInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function getClient($clientId)
    {
        $this->db->select('BaseTbl.clientId, BaseTbl.clientName, BaseTbl.clientAddress, BaseTbl.numberOfStore, BaseTbl.notes, BaseTbl.clientURL, BaseTbl.url1, BaseTbl.api1, BaseTbl.url2, BaseTbl.api2, BaseTbl.url3, BaseTbl.api3, BaseTbl.url4, IF(BaseTbl.status = 1, "Active", "Inactive") AS status, BaseTbl.expiration_date, BaseTbl.is_exclude_expiration, BaseTbl.is_pia, BaseTbl.is_hrm, BaseTbl.is_media, BaseTbl.is_local, BaseTbl.is_reservation, BaseTbl.is_backend');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $clientId);
        $query = $this->db->get();

        return $query->result();
    }
    function getClientRow($clientId){

        $query = $this->db->get_where('ssp_clients', array('clientId' => $clientId));
        return $query->row();

    }
    function get_number_of_stores($clientId){

        $query = $this->db->get_where('ssp_stores', array('client_id' => $clientId));
        //return $query->row();
        return $query->result_array(); 
    }// end function

    function updateClient($clientId, $clientInfo){

        $this->db->where('clientId', $clientId);
        $this->db->update('ssp_clients', $clientInfo);
        
        return $this->db->affected_rows();
    }

    function editClient($clientInfo, $clientId)
    {
        $this->db->where('clientId', $clientId);
        $this->db->update('ssp_clients', $clientInfo);
        
        return TRUE;
    }
    
    function deleteClient($clientId)
    {
        $this->db->delete('ssp_clients', array('clientId' => $clientId));
        
        return $this->db->affected_rows();
    }

    function getMainURL($userClientId)
    {
        $this->db->select('BaseTbl.`clientURL`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
        return $query->result();
    }

    function generateNewStores($clientId, $user_id)
    {
        $query = $this->db->query('call ssp_generate_stores('.$clientId.', '.$user_id.')');
        $id = $query->result()[0]->lid;
        $this->db->close();
        return $id;
    }

    function generateNewRoleAccess($clientId, $user_id)
    {
        $query = $this->db->query('call ssp_generate_role_access('.$clientId.', '.$user_id.')');
        
        return $query->result_id;
    }

    function generateNewCustomerTypes($clientId, $user_id)
    {
        $query = $this->db->query('call ssp_generate_customer_types('.$clientId.', '.$user_id.')');
        
        return $query->result_id;
    }

    function generateNewBrandSettings($clientId, $user_id)
    {
        $query = $this->db->query('call ssp_generate_brand_settings('.$clientId.', '.$user_id.')');
        
        return $query->result_id;
    }

    function getURL1($userClientId)
    {
        $this->db->select('BaseTbl.`url1`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
        return $query->result();
    }
    function getAPI1($userClientId) // added 6/19/2018 by Bryan
    {
        $this->db->select('BaseTbl.`api1`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
         return $query->row_array();
        //return $query->row();
    }

    function getURL2($userClientId)
    {
        $this->db->select('BaseTbl.`url2`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
        return $query->result();
    }
    function getAPI2($userClientId) // added 6/19/2018 by Bryan
    {
        $this->db->select('BaseTbl.`api2`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
        return $query->row_array();

    }

    function getURL3($userClientId)
    {
        $this->db->select('BaseTbl.`url3`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
        return $query->result();
    }

    function getAPI3($userClientId) // added 6/19/2018 by Bryan
    {
        $this->db->select('BaseTbl.`api3`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
        return $query->row_array();
    }

    function getURL4($userClientId)
    {
        $this->db->select('BaseTbl.`url4`');
        $this->db->from('ssp_clients as BaseTbl');
        $this->db->where('BaseTbl.clientId', $userClientId);
        $query = $this->db->get();
        
        return $query->result();
    }
}

?>