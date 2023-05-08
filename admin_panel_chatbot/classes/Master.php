<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_response(){
		extract($_POST);
		$data = "";
		
		// Check if the record already exists in the database
		$result = $this->conn->query("SELECT * FROM `news` WHERE `eventn` = '{$eventn}'");
	
		if ($result->num_rows > 0) {
			// If the record already exists, update it
			$update_resp = $this->conn->query("UPDATE `news` SET `descr` = '{$descr}' WHERE `eventn` = '{$eventn}'");
			if(!$update_resp){
				return 2;
				exit;
			}
		} else {
			// Otherwise, insert a new record
			$ins_resp = $this->conn->query("INSERT INTO `news` VALUES (NULL , '{$eventn}' , '{$descr}' )");
			if(!$ins_resp){
				return 2;
				exit;
			}
		}
		
		$this->settings->set_flashdata("success","Data successfully saved");
		return 1;
	}
	
	public function delete_response(){
		extract($_POST);
		 $del = $this->conn->query("DELETE FROM `news` where id = $id");
		 if($del){
			$this->settings->set_flashdata("success"," Data successfully deleted");
		 	return 1;
		 }else{
		 	$this->conn->error;
		 }
	}
	public function delete_unanswered(){
		extract($_POST);
		 $del = $this->conn->query("DELETE FROM `unanswered` where id = $id");
		 if($del){
			$this->settings->set_flashdata("success"," Data successfully deleted");
		 	return 1;
		 }else{
		 	$this->conn->error;
		 }
	}
	public function save_disponibilite(){
		extract($_POST);
		$data = "";
		
		// Check if the record already exists in the database
		$result = $this->conn->query("SELECT * FROM `disponibilite_prof2` WHERE `name` = '{$prof_name}'");

		if ($result->num_rows > 0) {
			// If the record already exists, update it
			$update_resp = $this->conn->query("UPDATE `disponibilite_prof2` SET `disponibilite` = '{$Disponibilite}' WHERE `name` = '{$prof_name}'");
			if(!$update_resp){
				return 2;
				exit;
			}
		} else {
			// Otherwise, insert a new record
			$ins_resp = $this->conn->query("INSERT INTO `disponibilite_prof2` VALUES (NULL , '{$prof_name}' , '{$Disponibilite}' )");
			if(!$ins_resp){
				return 2;
				exit;
			}
		}
		
		$this->settings->set_flashdata("success","Data successfully saved");
		return 1;
	}
	
	public function delete_disponibilite(){
		extract($_POST);
		 $del = $this->conn->query("DELETE FROM `disponibilite_prof2` where id = $id");
		 if($del){
			$this->settings->set_flashdata("success"," Data successfully deleted");
		 	return 1;
		 }else{
		 	$this->conn->error;
		 }
	}

	public function delete_major(){
		extract($_POST);
		 $del = $this->conn->query("DELETE FROM `major` where id = $id");
		 if($del){
			$this->settings->set_flashdata("success"," Data successfully deleted");
		 	return 1;
		 }else{
		 	$this->conn->error;
		 }
	}
	public function save_major(){
		extract($_POST);
		$data = "";
		// Otherwise, insert a new record
		$ins_resp = $this->conn->query("INSERT INTO `major` VALUES (NULL , '{$niv_sp}' , '{$fullname}' )");
		if(!$ins_resp){
			return 2;
			exit;
		}
		
		$this->settings->set_flashdata("success","Data successfully saved");
		return 1;
	}
	public function update_major(){
		extract($_POST);
		$data = "";
		// If the record already exists, update it
		$update_resp = $this->conn->query("UPDATE `major` SET `fullname` = '{$fullname}' WHERE `niv_sp` = '{$niv_sp}'");
		if(!$update_resp){
			return 2;
			exit;
		}
		$this->settings->set_flashdata("success","Data successfully saved");
		return 1;
	}
	public function delete_vacation(){
		extract($_POST);
		 $del = $this->conn->query("DELETE FROM `vacation` where id = $id");
		 if($del){
			$this->settings->set_flashdata("success"," Data successfully deleted");
		 	return 1;
		 }else{
		 	$this->conn->error;
		 }
	}
	public function save_vacation(){
		extract($_POST);
		$data = "";
		// Otherwise, insert a new record
		$ins_resp = $this->conn->query("INSERT INTO `vacation` VALUES (NULL , '{$vacation_name}' , '{$vacation_date}' )");
		if(!$ins_resp){
			return 2;
			exit;
		}
		
		$this->settings->set_flashdata("success","Data successfully saved");
		return 1;
	}
	public function update_vacation(){
		extract($_POST);
		$data = "";
		// If the record already exists, update it
		$update_resp = $this->conn->query("UPDATE `vacation` SET `vacation_date` = '{$vacation_date}' WHERE `vacation_name` = '{$vacation_name}'");
		if(!$update_resp){
			return 2;
			exit;
		}
		$this->settings->set_flashdata("success","Data successfully saved");
		return 1;
	}
}
$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_response':
		echo $Master->save_response();
	break;
	case 'delete_response':
		echo $Master->delete_response();
	break;
	case 'delete_unanswered':
		echo $Master->delete_unanswered();
	break;
	case 'save_disponibilite':
		echo $Master->save_disponibilite();
	break;
	case 'delete_disponibilite':
		echo $Master->delete_disponibilite();
	break;
	case 'delete_major':
		echo $Master->delete_major();
	break;
	case 'save_major':
		echo $Master->save_major();
	break;
	case 'update_major':
		echo $Master->update_major();
	break;
	case 'delete_vacation':
		echo $Master->delete_vacation();
	break;
	case 'save_vacation':
		echo $Master->save_vacation();
	break;
	case 'update_vacation':
		echo $Master->update_vacation();
	break;
	default:
		// echo $sysset->index();
		break;
}