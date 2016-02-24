<?php

require_once __DIR__ . '/../classes/VHFFS_db.class.php';


class VHFFS_letsencrypt {
	
	private $httpd_id;
	private $certificate_date;
	private $error_log;
	
	
	public function __construct($httpd_id = NULL) {
		$args = func_get_args();
		if(count($args) === 0) {
			// constructor without params (PDO)
		}
		else {
			$this->httpd_id = $args[0];
			$this->certificate_date = NULL;
			$this->error_log = NULL;
		}
	}
	
	
	public function get_httpd_id() {
		return $this->httpd_id;
	}
	
	public function get_certificate_date() {
		return $this->certificate_date;
	}
	
	public function get_error_log() {
		return $this->error_log;
	}
	
	
	/**
	 * 
	 * @param int $httpd_id
	 * @return VHFFS_letsencrypt
	 */
	public static function get_from_httpd_id($httpd_id) {
		$sql = "
		SELECT	*
		FROM	vhffs_letsencrypt
		WHERE	httpd_id = " . VHFFS_db::get()->quote($httpd_id);
		
		$st = VHFFS_db::get()->query($sql);
		$st->setFetchMode(PDO::FETCH_CLASS, 'VHFFS_letsencrypt');
		$res = $st->fetch(PDO::FETCH_CLASS);
		if($res === FALSE)
			$res = NULL;
		return($res);
	}
	
	
	private function save() {
		global $conf;
		
		VHFFS_db::get()->beginTransaction();
		
		$sql = "LOCK TABLE " . $conf['postgresql_tablename'] . " IN EXCLUSIVE MODE";
		$res = VHFFS_db::get()->exec($sql);
		
		$sql = "
		UPDATE " . $conf['postgresql_tablename'] . "
		SET httpd_id = :httpd_id,
			certificate_date = :certificate_date,
			error_log = :error_log
		WHERE httpd_id = :httpd_id";
		$st = VHFFS_db::get()->prepare($sql);
		$array = array(
			'httpd_id' => $this->httpd_id,
			'certificate_date' => $this->certificate_date,
			'error_log' => $this->error_log);
		VHFFS_db::bindArrayValue($st, $array);
		$res = $st->execute();
		if($st->errorCode() != 0) {
			echo '<pre>' . $sql . '</pre>';
			foreach($st->errorInfo() as $info) {
				echo $info . '<br>';
			}
			die;
		}
		$row_count = $st->rowCount();
// 		var_dump($row_count);
				
		if($row_count === 0) {
			$sql = "
			INSERT INTO " . $conf['postgresql_tablename'] . " (httpd_id, certificate_date, error_log)
			VALUES (:httpd_id, :certificate_date, :error_log)";
			$st = VHFFS_db::get()->prepare($sql);
			$array = array(
				'httpd_id' => $this->httpd_id,
				'certificate_date' => $this->certificate_date,
				'error_log' => $this->error_log);
			VHFFS_db::bindArrayValue($st, $array);
			$res = $st->execute();
			if($st->errorCode() != 0) {
				echo '<pre>' . $sql . '</pre>';
				foreach($st->errorInfo() as $info) {
					echo $info . '<br>';
				}
				die;
			}
			$row_count = $st->rowCount();
//			var_dump($row_count);
		}
		
		VHFFS_db::get()->commit();
	}
	
	
	public function delete() {
		
	}
	
	
	public function cert_ok() {
		$certificate_date = (new DateTime('now'))->format('Y-m-d');
		$this->certificate_date = (new DateTime('now'))->format('Y-m-d');
		$this->error_log = NULL;
		$this->save();
	}
	
	
	public function cert_error($error_log) {
		$this->error_log = $error_log;
		$this->save();
	}
	
}