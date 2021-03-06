<?php

/**
 * Magazin Model - Manages magazine
 *
 * @category Admin Controller
 * @package  Linkshare
 * @author   Weblight <office@weblight.ro>
 * @license  License http://www.weblight.ro/
 * @link     http://www.weblight.ro/
 *
 */

class Advertiser_model extends CI_Model
{

    private $CI;

    function __construct()
    {
        parent::__construct();

        $this->CI = & get_instance();
    }

    /**
     * Get Magazine
     * array $filters
     *
     * @return array
     */
    function getAdvertisers($filters = array())
    {
        $row = array();
        $order_dir = (isset($filters['sort_dir'])) ? $filters['sort_dir'] : 'ASC';

        $this->load->model('site_model');

        if(isset($filters['sort']))  $this->db->order_by($filters['sort'], $order_dir);

        if (isset($filters['limit'])) {
            $offset = (isset($filters['offset'])) ? $filters['offset'] : 0;
            $this->db->limit($filters['limit'], $offset);
        }

        if (isset($filters['id_site'])) {
            $this->db->where('id_site', $filters['id_site']);
        }

        if (isset($filters['id_categories'])) {
            $this->db->like('id_categories', $filters['id_categories']);
        }

        if (isset($filters['name_status'])) {
            $this->db->like('status', $filters['name_status']);
        }
        
        if (isset($filters['name'])) {
            $this->db->like('name', $filters['name']);
        }

        if (isset($filters['mid'])) {
            $this->db->where('mid', $filters['mid']);
        }

        if (isset($filters['id_status']))
            $this->db->where('id_status', $filters['id_status']);
            $this->db->order_by('id');
            $result = $this->db->get('linkshare_advertisers');

        foreach ($result->result_array() as $linie) {

            $site = $this->site_model->getSite($linie['id_site']);
            $linie['name_site'] = $site['name'];

            $nr_products = $this->getCountProductsByMID($linie['mid'], $linie['id_site']);
            $linie['nr_products'] = $nr_products;
            $row[] = $linie;
        }

        return $row;
    }

    /**
     * Get Magazin
     *
     * @param int $id
     * @param boolean $name 	
     *
     * @return array
     */
    function getAdvertiser($id, $name = false)
    {
        $row = array();
        $this->db->where('id', $id);
        $result = $this->db->get('linkshare_advertisers');

        if ($name)
            $this->load->model('site_model');

        foreach ($result->result_array() as $row) {
            if ($name) {
                $site = $this->site_model->getSite($row['id_site']);
                $row['id_site'] = $site['name'];
            }
            return $row;
        }

        return $row;
    }

    /**
     * Get Magazin By Mid
     *
     * @param int $mid
     * @param int $id_site	
     *
     * @return array
     */
    function getAdvertiserByMID($mid, $id_site)
    {
        $row = array();
        $this->db->where('mid', $mid);
        $this->db->where('id_site', $id_site);
        $result = $this->db->get('linkshare_advertisers');

        foreach ($result->result_array() as $row) {
            return $row;
        }

        return $row;
    }

    /**
     * Get Count Products By Mid
     *
     * @param int $mid
     * @param int $id_site
     * @param array $filters
     *
     * @return array
     */
    function getCountProductsByMID($mid, $id_site, $filters = array())
    {
        /* $i = 0;
          $row = array(); */
        $this->db->where('mid', $mid);
        $this->db->where('id_site', $id_site);
        if (isset($filters['cat_creative_id']))
            $this->db->where('cat_creative_id', $filters['cat_creative_id']);
        if (isset($filters['cat_creative_name']))
            $this->db->where('cat_creative_name', $filters['cat_creative_name']);
        $result = $this->db->get('linkshare_produs');

        return $result->num_rows();

        //crapa memoria daca parcurg tot vectorul :)
        /* foreach ($result->result_array() as $row) {
          $i++;
          }

          return $i; */
    }

    /**
     * Create New Magazin
     *
     * Creates a new magazin
     *
     * @param array $insert_fields	
     *
     * @return int $insert_id
     */
    function newAdvertiser($insert_fields)
    {
        $this->db->insert('linkshare_advertisers', $insert_fields);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    /**
     * Create New Magazine
     *
     * Creates new magazine
     *
     * @param array $mids
     *
     * @return int $count
     */
    function newAdvertisers($mids)
    {
        $cate = count($mids);
        for ($i = 0; $i < $cate; $i++) {
            $this->db->insert('linkshare_advertisers', $mids[$i]);
        }
        return $cate;
    }

    /**
     * Update Magazin
     *
     * Updates magazin
     * 
     * @param array $update_fields
     * @param int $id	
     *
     * @return boolean true
     */
    function updateAdvertiser($update_fields, $id)
    {
        $this->db->update('linkshare_advertisers', $update_fields, array('id' => $id));

        return true;
    }

    /**
     * Delete Magazin
     *
     * Deletes magazin
     * 	
     * @param int $id	
     *
     * @return boolean true
     */
    function deleteAdvertiser($id)
    {
        $this->db->delete('linkshare_advertisers', array('id' => $id));

        return true;
    }

    function parseAdvertiser($params)
    {
        if ($params[0]['limit'])
            $limit = $params[0]['limit'];
        else
            $limit = 10;
        if ($params[0]['offset'])
            $offset = $params[0]['offset'];
        else
            $offset = 0;
        return array_slice($params, $offset, $limit, true);
    }

    /**
     * Delete Magazin By Status	
     * 
     * @param int $id_site	
     * @param int $id_status	
     *
     * @return boolean true
     */
    function deleteAdvertiserByStatus($id_site, $id_status)
    {
        $this->db->query("DELETE FROM  linkshare_advertisers WHERE id_site='$id_site' AND id_status='$id_status'");
        return true;
    }

    function get_num_rows($filters = array())
    {

        if (isset($filters['id_categories'])) {
            $this->db->like('id_categories', $filters['id_categories']);
        }

        if (isset($filters['name'])) {
            $this->db->like('name', $filters['name']);
        }

        if (isset($filters['mid'])) {
            $this->db->where('mid', $filters['mid']);
        }

        $result = $this->db->get('linkshare_advertisers');
        return $result->num_rows();
    }

}
