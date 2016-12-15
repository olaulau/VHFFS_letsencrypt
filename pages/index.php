<?php
require_once "../includes/header.inc.php";
?>

<?php
$tab = VHFFS::get_all_domains_info();
?>
<div class="container table-responsive">
	<table class="table table-hover">
	<thead>
		<tr class="table-header">
	    	<th>servername</th> <th>certificate_date</th> <th>error_log</th> <th>username</th> <th>groupname</th>
	    </tr>
	</thead>
	<tbody>
	    <?php
	    foreach ($tab as $row) {
		    ?>
		    <tr class="<?= $row['status'] ?>">
		    <td><?= $row['servername'] ?></td> <td><?= $row['certificate_date'] ?></td> <td><?= $row['error_log'] ?></td> <td><?= $row['username'] ?></td> <td><?= $row['groupname'] ?></td>
		    </tr>
		    <?php
		}
	    ?>
	    
	    <tr class="active">
	    	<td>...</td> <td>...</td> <td>...</td> <td>...</td> <td>...</td>
	    </tr>
		<tr class="success">
			<td>...</td> <td>...</td> <td>...</td> <td>...</td> <td>...</td>
		</tr>
		<tr class="warning">
			<td>...</td> <td>...</td> <td>...</td> <td>...</td> <td>...</td>
		</tr>
		<tr class="danger">
			<td>...</td> <td>...</td> <td>...</td> <td>...</td> <td>...</td>
		</tr>
		<tr class="info">
			<td>...</td> <td>...</td> <td>...</td> <td>...</td> <td>...</td>
		</tr>
	</tbody>
	</table>
</div>

<?php
require_once "../includes/footer.inc.php";
