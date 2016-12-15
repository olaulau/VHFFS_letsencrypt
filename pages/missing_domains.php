<?php
require_once "../includes/header.inc.php";
?>

<?php
$tab = VHFFS::get_missing_domains();
?>
<div class="container table-responsive">
	<table class="table table-hover">
	<thead>
		<tr class="table-header">
	    	<th>servername</th> <th>username</th> <th>groupname</th>
	    </tr>
	</thead>
	<tbody>
	    <?php
	    foreach ($tab as $row) {
		    ?>
		    <tr class="">
		    <td><?= $row['servername'] ?></td> <td><?= $row['username'] ?></td> <td><?= $row['groupname'] ?></td>
		    </tr>
		    <?php
		}
	    ?>
	</tbody>
	</table>
</div>

<?php
require_once "../includes/footer.inc.php";
