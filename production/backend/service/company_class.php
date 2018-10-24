<?php

//require_once "./service/config.php";
//require_once "./service/service.php";
require_once "config.php";
require_once "service.php";
class Company
{

    public $sName       = "";
    public $sPhones		="";
    public $sEmail      = "";
    public $status      ="";

    public function GetCompanyInfo($oDBHandler, $companyId)
    {

        $sQuery = "SELECT name,phones,email
	      FROM companies
	      where id = ".$companyId;

        //echo  $sQuery;

        $oResult = $oDBHandler->query($sQuery);

        if ($oDBHandler->affected_rows > 0)
        {

            $oRow = $oResult->fetch_assoc();
            $this->sName = $oRow["name"];
            $this->status = 'ok';
            $this->sPhones = $oRow["phones"];
            $this->sEmail = $oRow["email"];

        }

        return $this;
    }

}

?>