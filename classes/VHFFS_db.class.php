<?php

abstract class VHFFS_db {
	
	private static $db = NULL;
	
	
	public static function get() {
		global $conf;
		if(self::$db === NULL) {
			self::$db = new PDO('pgsql:host=' . $conf['postgresql_host'] . ';port=' . $conf['postgresql_port'] . ';dbname=' . $conf['postgresql_dbname'], $conf['postgresql_username'], $conf['postgresql_password']);
		}
		return self::$db;
	}
	
	
	/**
	 * http://php.net/manual/en/pdostatement.bindvalue.php#104939 corrected !
	 * @param string $req : the query on which link the values
	 * @param array $array : associative array containing the values ​​to bind
	 * @param array $typeArray : associative array with the desired value for its corresponding key in $array
	 * */
	public static function bindArrayValue($req, $array, $typeArray = false)
	{
		if(is_object($req) && ($req instanceof PDOStatement))
		{
			foreach($array as $key => $value)
			{
				if($typeArray)
					$req->bindValue(":$key", $value, $typeArray[$key]);
				else
				{
// 					echo "testing value : " . var_export($value, TRUE) . "<br/>";
					if(is_int($value)) {
						$param = PDO::PARAM_INT;
// 						echo "affected the INT param.<br/>";
					}
					elseif(is_bool($value)){
						$param = PDO::PARAM_BOOL;
// 						echo "affected the BOOL param.<br/>";
					}
					elseif(is_null($value)) {
						$param = PDO::PARAM_NULL;
// 						echo "affected the NULL param.<br/>";
					}
					elseif(is_string($value)) {
						$param = PDO::PARAM_STR;
// 						echo "affected the STR param.<br/>";
					}
					else {
						$param = FALSE;
// 						echo "didn't affected any param.<br/>";
					}
					
					if(isset($param)) {
// 						echo "binding :$key to $value with param $param <br/>";
						$req->bindValue(":$key", $value, $param);
					}
					else {
// 						echo "not binding, as not param detected. <br/>";
					}
				}
			}
		}
	}
	
}
