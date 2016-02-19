<?php

require_once __DIR__ . '/config.inc.php';

class VHFFS {
	
	private $db;
	
	
	public function __construct() {
		global $conf;
		$this->db = new PDO('pgsql:host=' . $conf['postgresql_host'] . ';port=' . $conf['postgresql_port'] . ';dbname=' . $conf['postgresql_schema'], $conf['postgresql_username'], $conf['postgresql_password']);
	}
	
	
	public function get_alpha_domains() {
		$sql = '
		SELECT		servername
		FROM		vhffs_httpd
		ORDER BY	servername
		';
		$st = $this->db->query($sql);
		
		$res = $st->fetchAll(PDO::FETCH_COLUMN, 'servername');
		return $res;
	}
	
	/*
SELECT		DISTINCT vg.groupname, vh.servername
FROM		vhffs_httpd vh, vhffs_object vo, vhffs_groups vg
WHERE		vh.object_id = vo.object_id
	AND		vo.owner_gid = vg.gid
ORDER BY	vg.groupname, vh.servername
	 */
	public function get_project_domains() {
		$sql = '
		SELECT		DISTINCT vg.groupname, vh.servername
		FROM		vhffs_httpd vh, vhffs_object vo, vhffs_groups vg
		WHERE		vh.object_id = vo.object_id
			AND		vo.owner_gid = vg.gid
		ORDER BY	vg.groupname, vh.servername
		';
		$st = $this->db->query($sql);
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
	
	
	public function get_owner_user_from_httpd_servername($servername) {
		$sql = '
		SELECT		vu.*
		FROM		vhffs_httpd vh, vhffs_object vo, vhffs_users vu
		WHERE		vh.object_id = vo.object_id
			AND		vo.owner_uid = vu.uid
			AND		vh.servername = ' . $this->db->quote($servername) . '
		';
		
		$st = $this->db->query($sql);
		if($this->db->errorCode() != 0) {
			echo '<pre>' . $sql . '</pre>';
			foreach($this->db->errorInfo() as $info) {
				echo $info . '<br>';
			}
			die;
		}
		
		$res = $st->fetch(PDO::FETCH_OBJ);
		return $res;
	}
	
	
	public function get_webrootpath_from_servername($servername) {
		global $conf;
		$md5 = md5($servername);
		
		$res = $conf['webarea_root'] . '/' . substr($md5, 0, 2) . '/' . substr($md5, 2, 2) . '/' . substr($md5, 4, 2) . '/' . $servername . '/' . 'htdocs' . '/';
		return $res;
	}
}

/*

SELECT * FROM public.vhffs_httpd
SELECT * FROM public.vhffs_groups;
SELECT * FROM public.vhffs_object;
SELECT * FROM public.vhffs_user_group;
SELECT * FROM public.vhffs_users;


SELECT	vu.username, vg.groupname
FROM	vhffs_user_group vug, vhffs_users vu, vhffs_groups vg
WHERE	vug.uid = vu.uid
AND	vug.gid = vg.gid


SELECT	vh.servername
FROM	vhffs_httpd vh, vhffs_object vo
WHERE	vh.object_id = vo.object_id


SELECT		vu.username, vg.groupname, vh.servername
FROM		vhffs_users vu, vhffs_groups vg, vhffs_httpd vh, vhffs_object vo
WHERE		vo.owner_uid = vu.uid
AND		vo.owner_gid = vg.gid
AND		vh.object_id = vo.object_id
ORDER BY	vu.username, vg.groupname, vh.servername


SELECT		DISTINCT vu.username, vg.groupname, vh.servername, owner.mail
FROM		vhffs_httpd vh, vhffs_object vo, vhffs_user_group vug, vhffs_users vu, vhffs_groups vg, VHFFS_users owner
WHERE		vh.object_id = vo.object_id
AND		vo.owner_gid = vug.gid
AND		vug.uid = vu.uid
AND		vug.gid = vg.gid
AND		vo.owner_uid = owner.uid
ORDER BY	vh.servername, vu.username, vg.groupname

*/