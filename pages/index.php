<?php
require_once "../includes/header.inc.php";
?>

<div class="container">
	
	<span class="btn btn-info btn-lg">queue length : <?= get_queue_length() ?></span> <br/>
	<br/>
	
	<a class="btn btn-default btn-lg" href="list_vhffs_domains.php">list VHFFS domains</a> <br/>
	<br/>
	
	<a class="btn btn-default btn-lg" href="missing_domains.php">list missing domains</a> <br/>
	<br/>
	
	<a class="btn btn-default btn-lg" href="create.php">create VHFFS domain</a> <br/>
	<br/>
	
</div>

<?php
require_once "../includes/footer.inc.php";
