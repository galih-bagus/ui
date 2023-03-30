<?php
class Mbilling extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}
	function getAllPayment()
	{
		//select semua data yang ada pada table msProduct $this--->db->select("*");
		$this->db->select("*");
		$this->db->from("payment_bills");
		$this->db->order_by("id", "desc");
		return $this->db->get();
	}

	function getStudentByPriceId($id)
	{
		$query = $this->db->query("SELECT s.id, s.name, s.condition, s.adjusment, lpr.program, lpr.course, lpr.monthpay
								   FROM `last_payment_regular` lpr
								   LEFT OUTER JOIN student s ON lpr.id_student=s.id
								   WHERE s.status = 'ACTIVE'
								   AND lpr.level <> 'Private'
								   AND s.priceid = " . $id . "
								   ");
		$result = $query->result();
		return $result;
	}

	function addBill($data)
	{
		$this->db->insert('payment_bills', $data);
		$id = $this->db->insert_id();
		$query = $this->db->query("SELECT id FROM payment_bills where id=$id");
		// $query = $this->db->query("SELECT id FROM payment ORDER BY id DESC LIMIT 1");
		$result = $query->row_array();
		return $result;
	}

	function history()
	{
		$query = $this->db->query("SELECT history_billing.*, parents.name FROM history_billing JOIN parents ON history_billing.created_by=parents.id");
		$result = $query->result();
		return $result;
	}

	
}
