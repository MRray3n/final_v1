<style>
	.q-item {
	    border-radius: 50px;
	}


</style>
<?php
if(isset($_GET['id']) && !empty($_GET['id'])){
	$qry = $conn->query("SELECT id, niv_sp,fullname  FROM `major` where id = '{$_GET['id']}'");
	foreach($qry->fetch_array() as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($_GET['id']) ? "Update ": "Create New " ?>major de promotion</h3>
	</div>
	<div class="card-body">
		<form action="" id="major-form">			
			<div class="form-group">
				<label for="fullname_field" class="control-label" >nom et prénom de l'étudiant</label>
				<div class="input-group col-lg-6">
				<input type="hidden" id="id" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                    <input type="text" id="fullname" name="fullname"  class="form-control form-control-sm" data-original-title="" title="" value="<?php echo isset($fullname) ? $fullname : ''; ?>">

                    
                </div>
			</div>
			<div class="form-group">
				<label for="niv_sp" class="control-label"> spécialité et niveau</label>
				<textarea name="niv_sp" id="niv_sp" cols="30" readonly rows="3" class="form-control" style="resize: none" required><?php echo isset($niv_sp) ? $niv_sp : ''; ?></textarea>
			</div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="major-form"/> Sauvegarder</button>
		<a class="btn btn-flat btn-default" href="?page=major">Annuler</a>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#major-form').submit(function(e){
			e.preventDefault();
			 $('.err-msg').remove();
			if($('input[name="fullname"]').length <= 0){
				$('#fullname').addClass("border-danger")
				$('#fullname-holder').after("<span class='alert alert-danger err-msg'><i class='fa fa-exclamation-triangle'></i>  You must add atleast one (1) possible prof_name for the response.</span>")
				$('#fullname').focus();
				return false;
			}
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=update_major",
				method:'POST',
				data:$(this).serialize(),
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(resp == 1){
						location.href = "./?page=major";
					}else{
						alert_toast("An error occured",'error');
						end_loader();
					}
				}
			})
		})
	})
</script>