<?php
require_once "../includes/header.inc.php";
?>

	<form action="" method="POST" name="easyform" class="container">
		<div class="row col-sm-4 col-md-offset-4 text-center form-group-lg">
			<h4 class="form-signin-heading">Please choose a domain from the list :</h4>
	
			<div id="domains_alpha">
				<h4><label for="alpha-domain" class="">domains (alphabetical order)</label></h4>
				<select id="alpha-domain" class="form-control" name="alpha-domain"> </select>
			</div>
			
			<div id="domains_by_project">
				<h4><label for="project-domain" class="">domains (by project)</label></h4>
				<select id="project-domain" class="form-control" name="project-domain">	</select>
			</div>
			
			<label for="grouped_by_project">group domains by project</label>
			<input type="checkbox" id="grouped_by_project" name="grouped_by_project" />
		</div>
	
		
		<div class="row">&nbsp;</div>
		
		<input id="domain" name="domain" value="" style="display:none;">
		
		<div class="row">&nbsp;</div>
		
		<div class="row">&nbsp;</div>
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<button class="btn btn-lg btn-success col-sm-8 col-sm-offset-2" type="submit"> <br/> CREATE <br/> &nbsp; </button>
				</div>
			</div>
		</div>
			
	</form>

<?php
require_once "../includes/footer.inc.php";
