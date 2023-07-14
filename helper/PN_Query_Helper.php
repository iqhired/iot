<?php
	/**
	 * @author Ayesha
	 */
	/**
	 * This method return the Line Name By ID
	 * @param $id
	 * @return Line Name
	 */
	
	/**
	 * @param $db
	 * @param $id
	 * @return mixed
	 */
	function getLineNameByID($db,$id) {
		$qur04 = mysqli_query($db, "SELECT line_name FROM  cam_line where line_id = '$id'");
		$rowc04 = mysqli_fetch_array($qur04);
		return $rowc04["line_name"];
	}
	
	/**
	 * @param $db
	 * @param $id
	 * @return mixed
	 */
	function getPositionNameByID($db,$id){
		$qur04 = mysqli_query($db, "SELECT position_name FROM  cam_position where position_id = '$id'");
		$rowc04 = mysqli_fetch_array($qur04);
		return $rowc04["position_name"];
	}
	
	/**
	 * @param $db
	 * @param $id
	 * @return string
	 */
	function getUserNameByID($db,$id){
		$qur04 = mysqli_query($db, "SELECT firstname,lastname  FROM  cam_users where users_id = '$id'");
		$rowc04 = mysqli_fetch_array($qur04);
		return $rowc04["firstname"] . ' ' . $rowc04["lastname"];
	}
