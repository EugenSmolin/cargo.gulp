<?php

//require_once('predis/autoload.php/');

/* 
 * 
 * Hash&Cache class definition
 * 
 */

class HCache 
{
    private $redisConnection;
    /**
     * 
     * @return string
     */
    public function isCacheAvailable()
    {
//	return false;
        try {
            $redisConn = new Redis();
            if ($redisConn->connect(REDIS_HOST,REDIS_PORT)) {
        	$this->redisConnection = $redisConn;
            }
        } catch (Exception $ex) {
            $this->redisConnection = FALSE;
        }
        
        return $this->redisConnection;
    }

    public function FlushDB()
    {
        if (!$this->redisConnection)
            return false;
        $this->redisConnection->flushDB();
        return true;
    }


    public function FlushAll()
    {
        if (!$this->redisConnection)
            return false;
        $this->redisConnection->flushAll();
        return true;
    }

    public function GenerateHash($transportNumber, $cityFrom, $cityTo, $countryFrom,
            $countryTo, $stateFrom = "", $stateTo = "", $cargoFromStreet="", $cargoToStreet="",$cargoFromStreetCode="",
            $cargoToStreetCode="", $weight=0, $volume=0,
            $insurance = 0, $isActiveLineParams=0, $width=0, $length=0, $height=0, $paymentType, $options = [])
    {
        $sRawHash = $transportNumber . $cityFrom . $cityTo . $countryFrom . $countryTo . $stateFrom . $stateTo .
            $cargoFromStreet.$cargoToStreet.$cargoFromStreetCode.$cargoToStreetCode.$weight . $volume .
            $isActiveLineParams . $width . $length . $height .
            $paymentType . $insurance . serialize($options);
        $sEncHash = base64_encode($sRawHash);
        return $sEncHash;
    }
    
    public function dataExists($hashKey)
    {
	return $this->redisConnection->exists($hashKey);
    }
    
    public function setData($hashKey,$data)
    {
	if (!$this->redisConnection)
	    return false;
	
	$this->redisConnection->set($hashKey,serialize($data));
	$this->redisConnection->expire($hashKey,REDIS_EXPIRE);
    }
    
    public function getData($hashKey)
    {
	if (!$this->redisConnection)
	    return false;
	
	return unserialize($this->redisConnection->get($hashKey));
    }
}