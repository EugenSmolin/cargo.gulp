<?php
/**
 * Created by PhpStorm.
 * User: Erl
 * Date: 13.08.2018
 * Time: 16:03
 */

class Discount {
	public $id = null;
	public $name = '';
	public $value = 0.0;
	public $category = 0;
	public $dateStart = 0;
	public $dateEnd = 0;
	public $usePromo = null;
	public $promo =  '';
	public $isActive = true;
	public $isForever = false;

	public function SaveDiscount($mysqli, $countries, $companies, $userId = null){
		if(isset($userId) AND $userId != null AND $userId != '') $this->category = 3;
		//drop process if values are empty
		if(
			!isset($this->name) OR
			!isset($this->value) OR
			!isset($this->category) OR
			!isset($this->dateStart) OR
			//!isset($this->dateEnd) OR
			!isset($this->usePromo)
		) DropWithServerError("Not enough params supplied");

		//TODO: Legacy, test and delete
//secondary info
//		$countries = $oPOSTData->data->discount->countries;
//		$companies = $oPOSTData->data->discount->companies;

//for editing of existing discount
		if($this->id != null){
			//updating main info
			$query = "UPDATE `". DB_USER_DISCOUNTS_TABLE . "` SET ".
			         "`name` = '". $this->name . "', ".
			         "`cat_id` = " . intval($this->category) . ", ".
			         "`use_promo` = ". intval($this->usePromo) . ", ".
			         "`promo` = '". $this->promo . "', ".
			         "`value` = " . $this->value . ", ".
			         //"`is_active` = 1, ".
			         "`date_start` = '" . date(DATE_MYSQL_FORMAT, $this->dateStart) . "', ".
			         "`date_end` = '" . date(DATE_MYSQL_FORMAT, $this->dateEnd) . "', ".
			         "`is_forever` = " . intval($this->isForever) .", ".
			         "WHERE id = " . $this->id;
			$mysqli->query($query);

			//updating secondary info
			//editing country list
			$deleteRedCountriesQuery = "DELETE FROM " . DB_USER_DISCOUNT_COUNTRIES . " WHERE `discount_id` = " . $this->id . " "; //for deleting entries not present in the list
			$insertQuery = "INSERT INTO `". DB_USER_DISCOUNT_COUNTRIES . "` (`discount_id`, `country_code`) VALUES ";
			foreach($countries as $country){
				$temp = strtoupper($country);
				$query = "SELECT * FROM `". DB_USER_DISCOUNT_COUNTRIES . "` WHERE `discount_id` = ". $this->id .
				         " AND `country_code` = '" . $temp ."';";
				//inserting new country code
				if($mysqli->query($query)->num_rows == 0){
					$insertQuery .="(" . $this->id . ", '" . $temp . "'), ";
					$deleteRedCountriesQuery .= "AND `country_code` != '" . $temp . "' ";
				} else $deleteRedCountriesQuery .= " AND `country_code` != '" . $temp . "' "; //skipping if country exists
			}
			$insertQuery = rtrim($insertQuery, ", ");
			$insertQuery .= ";";
			$mysqli->query($insertQuery);
			$mysqli->query($deleteRedCountriesQuery);

			//updating secondary info
			//editing company list
			$deleteRedCountriesQuery = "DELETE FROM " . DB_USER_DISCOUNT_COMPANIES . " WHERE `discount_id` = " . $this->id . " "; //for deleting entries not present in the list
			$insertQuery = "INSERT INTO `". DB_USER_DISCOUNT_COMPANIES . "` (`discount_id`, `company_id`) VALUES ";
			foreach($companies as $company){
				$query = "SELECT * FROM `". DB_USER_DISCOUNT_COMPANIES . "` WHERE `discount_id` = ". $this->id .
				         " AND `company_id` = " . $company .";";
				//inserting new country code
				if($mysqli->query($query)->num_rows == 0){
					$insertQuery .="(" . $this->id . ", " . $company . "), ";
					$deleteRedCountriesQuery .= "AND `company_id` != " . $company . " ";
				} else $deleteRedCountriesQuery .= " AND `company_id` != " . $company . " "; //skipping if company exists
			}
			$insertQuery = rtrim($insertQuery, ", ");
			$insertQuery .= ";";
			$mysqli->query($insertQuery);
			$mysqli->query($deleteRedCountriesQuery);
		}

		//for new discount
		else{
			//creating new discount
			$query = "INSERT INTO `" . DB_USER_DISCOUNTS_TABLE . "` ".
			         "(`name`, 
			         `cat_id`, 
			         `use_promo`, 
			         `promo`, 
			         `value`, 
			         `is_active`, 
			         `date_start`, 
			         `date_end`,
			         `is_forever` ";
					if(isset($userId)) $query .= ", `user_id` ";
			         $query .= ") VALUES (".
			         "'". $mysqli->real_escape_string($this->name) ."', ";
			         $query .= (isset($userId) AND $userId != null AND $userId != '') ? 3 : $this->category;
			         $query .= ", ".
			         intval($this->usePromo) . ", ".
			         "'". $mysqli->real_escape_string($this->promo)."', ".
			         $this->value .", ".
			         "1, ".
			         "'". date(DATE_MYSQL_FORMAT, $this->dateStart) ."', ".
			         "'". date(DATE_MYSQL_FORMAT, $this->dateEnd) ."', " .
                     intval($this->isForever) . " ";
			if(isset($userId)) $query .= ", " . intval($userId);

			$query .= ");";
			$mysqli->query($query);

			//fetching id of the newly created discount
			$newId = 0;
			$query = "SELECT `id` FROM `". DB_USER_DISCOUNTS_TABLE . "` ORDER BY `id` DESC LIMIT 0, 1";
			$res = $mysqli->query($query);
			$newId = $res->fetch_assoc()['id'];

			//specifying countries for the newly created discount
			$query = "INSERT INTO `". DB_USER_DISCOUNT_COUNTRIES . "` (`discount_id`, `country_code`) VALUES ";
			foreach($countries as $country){
				$query .= "(". $newId . ", '" . strtoupper($country) . "'), ";
			}
			$query = rtrim($query, ', ');
			$query .= ";";
			$mysqli->query($query);

			//specifying companies for the newly created discount
			$query = "INSERT INTO `". DB_USER_DISCOUNT_COMPANIES . "` (`discount_id`, `company_id`) VALUES ";
			foreach($companies as $company){
				$query .= "(". $newId . ", " . $company . "), ";
			}
			$query = rtrim($query, ", ");
			$query .= ";";
			$mysqli->query($query);
		}
	}

	public function GetListsForDiscount($mysqli){
		$lists['companies'] = array();

		$query = "SELECT `id`, `name` FROM " . DB_COMPANY_TABLE;
		$res = $mysqli->query($query);
        if($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $temp['id'] = intval($row['id']);
                $temp['name'] = $row['name'];
                $lists['companies'][] = $temp;
            }
        }

		$lists['categories'] = array();
		$query = "SELECT `id`, `name` FROM " . DB_USER_DISCOUNT_CATEGORIES. " WHERE id <> 3";
		$res = $mysqli->query($query);
        if($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $temp['id'] = intval($row['id']);
                $temp['name'] = $row['name'];
                $lists['categories'][] = $temp;
            }
        }

		$lists['countries'] = array();
		$query = "SELECT `iso`, `name_rus` FROM " . DB_COUNTRY_TABLE;
		$res = $mysqli->query($query);

        if($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $temp['id'] = $row['iso'];
                $temp['name'] = $row['name_rus'];
                $lists['countries'][] = $temp;
            }
        }
		return $lists;
	}

	public function GetAdvancedLists($mysqli){
		$lists = $this->GetListsForDiscount($mysqli);
		$lists['promos'] = array();
		$lists['names'] = array();
		$query = "SELECT `id`, `name`, `promo`, `is_active`, `use_promo` FROM ". DB_USER_DISCOUNTS_TABLE;
		$res = $mysqli->query($query);
		if($res->num_rows > 0){
			//$result = array();
			while($row = $res->fetch_assoc()){
				if(isset($row['promo']) AND $row['promo'] != ""){
					$tempProm = array();
					$tempProm['isActive'] = boolval($row['use_promo']);
					$tempProm['code'] = $row['promo'];
					$lists['promos'][] = $tempProm;
				}
				$tempName = array();
				$tempName['name'] = $row['name'];
				$tempName['isActive'] = boolval($row['is_active']);
				$lists['names'][] = $tempName;
			}
		}
		else DropWithServerError();

		return $lists;
	}

	public function GetDiscounts($mysqli, $limit, $offset, $activity, $keyword, $sortCol = '', $order = '', $userId = null){
		//definig sorting column for output
		$sortQ = " ORDER BY ";
		switch($sortCol){
			case 'discountName':
				$sortQ .= "ud.name ";
				break;
			case 'discountSize':
				$sortQ .= "ud.value ";
				break;
			case 'discountCategory':
				$sortQ .= "ud.cat_id";
				break;
			case 'discountDate':
				$sortQ .= "ud.date_end";
				break;
			default:
				$sortQ .= "ud.date_end";
				break;
		}
		$sortQ .= " " . $order . " ";

		//TESTING BLOCK
		//$userId = 15;
		$discounts = array();

		//get main info about discounts
		$query = "SELECT ud.id, ud.name as name, ud.is_active, ud.value, cat.name AS cat_name, ud.date_end FROM `" . DB_USER_DISCOUNTS_TABLE . "` ud ".
		         "INNER JOIN `". DB_USER_DISCOUNT_CATEGORIES ."` cat ON cat.id = ud.cat_id";

		if(isset($userId)){
			$query .= " WHERE ud.cat_id = 3 AND user_id = " . intval($userId);
		}
		else {
			$query .= " WHERE ud.cat_id < 3";
		}

		switch ($activity){
			case "ACTIVE":
				$query .= " AND ud.is_active = 1";
				break;
			case "ARCHIVE":
				$query .= " AND ud.is_active = 0";
				break;
			default:
				break;
		}

		if($keyword != ''){
			$query .= " AND ud.name LIKE '%" . $mysqli->real_escape_string($keyword) . "%' ";
		}
		$query .= $sortQ;
		$query .= " LIMIT " . $offset . ", " . $limit;
		//var_dump($query); die;
		$res = $mysqli->query($query);
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$temp = array();
				$temp['id'] = intval($row['id']);
				$temp['name'] = $row['name'];
				$temp['value'] = floatval($row['value']);
				$temp['dateEnd'] = strtotime($row['date_end']);
				$temp['category'] = $row['cat_name'];
				$temp['isActive'] = boolval($row['is_active']);
				$temp['countries'] = array();
				$temp['companies'] = array();
				$discounts[] = $temp;
			}
		}
		//get countries list for specific discount
		for($i = 0; $i < count($discounts); $i++){
			$query = "SELECT dc.company_id,  c.name FROM `". DB_USER_DISCOUNT_COMPANIES ."` dc
			 INNER JOIN `" . DB_COMPANY_TABLE . "` c ON dc.company_id = c.id
			 WHERE dc.discount_id = " . $discounts[$i]['id'];
			$res = $mysqli->query($query);
			if($res->num_rows > 0) {
				while ($row = $res->fetch_assoc()) {
					$discounts[$i]['companies'][] = $row['name'];
				}
			}
		}
		//get countries list for specific discount
		for($i = 0; $i < count($discounts); $i++){
			$query = "SELECT dc.country_code, c.name_rus FROM `". DB_USER_DISCOUNT_COUNTRIES ."` dc
			 INNER JOIN `" . DB_COUNTRY_TABLE . "` c ON dc.country_code = c.iso
			 WHERE dc.discount_id = " . $discounts[$i]['id'];
			$res = $mysqli->query($query);
			if($res->num_rows > 0) {
				while ($row = $res->fetch_assoc()) {
					$discounts[$i]['countries'][] = $row['country_code'];
				}
			}
		}
		return $discounts;
	}

	public function GetSingleDiscount($mysqli, $discountId){
		$discount = array();
		$query = "SELECT 
		ud.id,
		ud.name,
		ud.use_promo,
		ud.promo,
		ud.value,
		ud.is_active,
		ud.date_start,
		ud.date_end,
		ud.cat_id,
		ud.is_forever 
		FROM `" . DB_USER_DISCOUNTS_TABLE ."` ud ".
		         " WHERE ud.id = " . $discountId;

		$res = $mysqli->query($query);
	//	var_dump($res);
        if($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $discount['id'] = intval($row['id']);
            $discount['discountName'] = $row['name'];
            $discount['usePromo'] = boolval($row['use_promo']);
            if ($discount['usePromo']) $discount['promo'] = $row['promo'];
            $discount['value'] = floatval($row['value']);
            $discount['isActive'] = boolval($row['is_active']);
            $discount['dateStart'] = strtotime($row['date_start']);
            $discount['dateEnd'] = strtotime($row['date_end']);
            $discount['discountCategory'] = intval($row['cat_id']);
            $discount['isForever'] = boolval($row['is_forever']);
        }

		$query = "SELECT company_id FROM `" . DB_USER_DISCOUNT_COMPANIES . "` WHERE discount_id = ". $discountId;
		$res = $mysqli->query($query);
		while ($row = $res->fetch_assoc()){
			$discount['companies'][] = intval($row['company_id']);
		};

		$query = "SELECT country_code FROM `" . DB_USER_DISCOUNT_COUNTRIES . "` WHERE discount_id = ". $discountId;
		$res = $mysqli->query($query);
		while ($row = $res->fetch_assoc()){
			$discount['countries'][] = $row['country_code'];
		};
		return $discount;
	}

	public function GetCount($mysqli, $keyword, $singleUser = false, $singleUserId = ''){
		$count = 0;
		$query = "SELECT COUNT(`id`) as total FROM `". DB_USER_DISCOUNTS_TABLE . "` WHERE `cat_id` ";
		if($singleUser){
			$query .= " = 3 AND `user_id` = " . $singleUserId;
		}
		else $query .= " < 3";

		if($keyword != ''){
			$query .= " AND name LIKE '%" . $keyword . "%' ";
		}

		$res = $mysqli->query($query);
		$row = $res->fetch_assoc();
		$count = intval($row['total']);
		return $count;
	}

	public function ChangeStatus($mysqli, $isActive, $discountId){
		$query = "UPDATE " . DB_USER_DISCOUNTS_TABLE . " SET `is_active` = " . intval($isActive) . " WHERE `id` = " . $discountId;
		$mysqli->query($query);
	}

}

?>