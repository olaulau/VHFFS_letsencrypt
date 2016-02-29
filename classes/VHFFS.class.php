<?php

require_once __DIR__ . '/../includes/autoload.inc.php';


class VHFFS {
	
	public static function get_alpha_domains() {
		$sql = '
		SELECT		servername
		FROM		vhffs_httpd
		ORDER BY	servername';
		$st = VHFFS_db::get()->query($sql);
		
		$res = $st->fetchAll(PDO::FETCH_COLUMN, 'servername');
		return $res;
	}
	

	public static function get_project_domains() {
		//TODO : tester avec ca :
		// http://fr2.php.net/manual/en/pdostatement.fetchall.php
		// To return an associative array grouped by the values of a specified column, bitwise-OR PDO::FETCH_COLUMN with PDO::FETCH_GROUP. 
		$sql = '
		SELECT		DISTINCT vg.groupname, vh.servername
		FROM		vhffs_httpd vh, vhffs_object vo, vhffs_groups vg
		WHERE		vh.object_id = vo.object_id
			AND		vo.owner_gid = vg.gid
		ORDER BY	vg.groupname, vh.servername';
		$st = VHFFS_db::get()->query($sql);
		$table = $st->fetchAll(PDO::FETCH_ASSOC);
// 		var_dump($res); die;
		
		$i = 0;
		$res = array();
		while ($i < count($table)) {
			$res[$table[$i]['groupname']][] = $table[$i]['servername'];
			$i ++;
		}
// 		var_dump($res); die;
		return $res;
	}
	
	
	public static function get_owner_user_from_httpd_servername($servername) {
		$httpd = self::get_httpd_from_servername($servername);
		$sql = '
		SELECT		vu.*
		FROM		vhffs_httpd vh, vhffs_object vo, vhffs_users vu
		WHERE		vo.object_id = ' . VHFFS_db::get()->quote($httpd->object_id) . '
			AND		vo.owner_uid = vu.uid
			AND		vh.servername = ' . VHFFS_db::get()->quote($servername);
		
		$st = VHFFS_db::get()->query($sql);
		if(VHFFS_db::get()->errorCode() != 0) {
			echo '<pre>' . $sql . '</pre>';
			foreach(VHFFS_db::get()->errorInfo() as $info) {
				echo $info . '<br>';
			}
			die;
		}
		
		$res = $st->fetch(PDO::FETCH_OBJ);
		return $res;
	}
	
	
	public static function get_webrootpath_from_servername($servername) {
		global $conf;
		$md5 = md5($servername);
		
		$res = $conf['webarea_root'] . '/' . substr($md5, 0, 2) . '/' . substr($md5, 2, 2) . '/' . substr($md5, 4, 2) . '/' . $servername . '/' . 'htdocs' . '/';
		return $res;
	}
	
	
	public static function create_table_if_needed() {
		global $conf;
		
		$sql = "
		SELECT EXISTS (
			SELECT 1 
			FROM   pg_catalog.pg_class c
			JOIN   pg_catalog.pg_namespace n ON n.oid = c.relnamespace
			WHERE  n.nspname = " .  VHFFS_db::get()->quote($conf['postgresql_schema']) . "
			AND    c.relname = " .  VHFFS_db::get()->quote($conf['postgresql_tablename']) . "
			AND    c.relkind = 'r'
		) AS exists";
// 		echo "<pre> $sql </pre>"; die;
		
		$st = VHFFS_db::get()->query($sql);
		if(VHFFS_db::get()->errorCode() != 0) {
			echo '<pre>' . $sql . '</pre>';
			foreach(VHFFS_db::get()->errorInfo() as $info) {
				echo $info . '<br>';
			}
			die;
		}
		
		$res = $st->fetch(PDO::FETCH_OBJ);
		if(!$res->exists) { // table not present
			$sql = "
			CREATE TABLE " .  $conf['postgresql_tablename'] . " (
				httpd_id integer NOT NULL,
				certificate_date date DEFAULT NULL,
				error_log text DEFAULT NULL
			);
			ALTER TABLE ONLY " .  $conf['postgresql_tablename'] . "
				ADD CONSTRAINT vhffs_letsencrypt_pkey PRIMARY KEY (httpd_id);
			ALTER TABLE ONLY " .  $conf['postgresql_tablename'] . "
				ADD CONSTRAINT fk_vhffs_letsencrypt_vhffs_httpd FOREIGN KEY (httpd_id) REFERENCES vhffs_httpd(httpd_id);";
// 			echo "<pre> $sql </pre>"; die;
			
			$res = VHFFS_db::get()->exec($sql);
			if(VHFFS_db::get()->errorCode() != 0) {
				echo '<pre>' . $sql . '</pre>';
				foreach(VHFFS_db::get()->errorInfo() as $info) {
					echo $info . '<br>';
				}
				die;
			}
			if($res === FALSE) {
				$_SESSION['messages'][] = 'error occured while creating letsencrypt table';
			}
			else {
				$_SESSION['messages'][] = 'created letsencrypt table';
			}
		}
	}
	
	
	public static function get_httpd_from_servername($servername) {
		$sql = '
		SELECT		vh.*
		FROM		vhffs_httpd vh
		WHERE		vh.servername = ' . VHFFS_db::get()->quote($servername);
	
		$st = VHFFS_db::get()->query($sql);
		if(VHFFS_db::get()->errorCode() != 0) {
			echo '<pre>' . $sql . '</pre>';
			foreach(VHFFS_db::get()->errorInfo() as $info) {
				echo $info . '<br>';
			}
			die;
		}
	
		$res = $st->fetch(PDO::FETCH_OBJ);
		return $res;
	}
	
}
