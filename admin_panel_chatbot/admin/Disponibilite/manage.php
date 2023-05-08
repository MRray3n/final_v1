<style>
	.q-item {
	    border-radius: 50px;
	}


</style>
<?php
if(isset($_GET['id']) && !empty($_GET['id'])){
	$qry = $conn->query("SELECT id, name, disponibilite FROM `disponibilite_prof2` where id = '{$_GET['id']}'");
	foreach($qry->fetch_array() as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($_GET['id']) ? "Update ": "Create New " ?>Disponibilite</h3>
	</div>
	<div class="card-body">
		<form action="" id="Disponibilite-form">			
			<div class="form-group">
				<label for="prof_name_field" class="control-label">nom et prénom de professeur</label>
				<div class="input-group col-lg-6">
				<input type="hidden" id="id" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                    <input type="text" id="prof_name" name="prof_name" class="form-control form-control-sm" data-original-title="" title="" value="<?php echo isset($name) ? $name : ''; ?>">

                    
                </div>
			</div>
			<div class="form-group">
				<label for="Disponibilite" class="control-label">Disponibilite</label>
				<textarea name="Disponibilite" id="Disponibilite" cols="30" rows="3" class="form-control" style="resize: none" required><?php echo isset($disponibilite) ? $disponibilite : ''; ?></textarea>
			</div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="Disponibilite-form"/> Sauvegarder</button>
		<a class="btn btn-flat btn-default" href="?page=Disponibilite">Annuler</a>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#Disponibilite-form').submit(function(e){
			e.preventDefault();
			 $('.err-msg').remove();
			if($('input[name="prof_name"]').length <= 0){
				$('#prof_name').addClass("border-danger")
				$('#prof_name-holder').after("<span class='alert alert-danger err-msg'><i class='fa fa-exclamation-triangle'></i>  You must add atleast one (1) possible prof_name for the response.</span>")
				$('#prof_name').focus();
				return false;
			}
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_disponibilite",
				method:'POST',
				data:$(this).serialize(),
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(resp == 1){
						location.href = "./?page=Disponibilite";
					}else{
						alert_toast("An error occured",'error');
						end_loader();
					}
				}
			})
		})
	})
</script>