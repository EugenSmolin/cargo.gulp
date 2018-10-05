<?php

require_once "./service/config.php";
require_once "./service/service.php";


class JurForm
{
    public $iJurFormID				=	0;
    public $sJurFormName            =   "";
    public $sJurFormShortName		=	"";

    public $objectOK				=	false;

    public function  GetJurForm($oDBHandler,$companyFormId)
    {
        $sSearchQuery = "SELECT id, name, short_name FROM `" . DB_JUR_FORM_TABLE. "` " .
                        "WHERE id= $companyFormId";

        $oSearchResult = $oDBHandler->query($sSearchQuery);
        //echo  $sSearchQuery,'<br>', $oDBHandler->error;
        $result = new stdClass();

        if ($oDBHandler->error) {
            $this->objectOK = false;
            $result->status = 'error';
            $result->error = USER_DB_ERROR;
            return $result;
        }
        // compile ret array
        $iRecipientID = 1;

        if ($oDBHandler->affected_rows > 0)
        {
            $oRow = $oSearchResult->fetch_assoc();

            $this->objectOK = true;
            $this->iJurFormID =  intval($oRow["id"]);
            $this->sJurFormName = strval($oRow["name"]);
            $this->sJurFormShortName = strval($oRow["short_name"]);

            $result->status = 'ok';
            $result->error = '';
            return $result;
        }
        else
        {
            $this->objectOK = false;
            $result->status = 'miss';
            $result->error = 'Not exist';
            return $result;
        }
    }
}

?>