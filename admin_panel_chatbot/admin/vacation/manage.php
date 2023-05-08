<style>
	.q-item {
	    border-radius: 50px;
	}


</style>
<?php
if(isset($_GET['id']) && !empty($_GET['id'])){
	$qry = $conn->query("SELECT id, vaction_name, vaction_name FROM `vacation` where id = '{$_GET['id']}'");
	foreach($qry->fetch_array() as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($_GET['id']) ? "Update ": "Create New " ?>vacance</h3>
	</div>
	<div class="card-body">
	<form action="" id="vacation-form">			
			<div class="form-group">
				<label for="date_field" class="control-label" >date</label>
				<div class="input-group col-lg-6">
				<input type="hidden" id="id" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                    <input type="text" id="vacation_date" name="vacation_date"  class="form-control form-control-sm" data-original-title="" title="" value="<?php echo isset($vacation_date) ? $vacation_date : ''; ?>">

                    
                </div>
			</div>
			<div class="form-group">
				<label for="vacation_name" class="control-label"> vacation_name</label>
				<textarea name="vacation_name" id="vacation_name" cols="30" rows="3" class="form-control" style="resize: none" required><?php echo isset($vacation_name) ? $vacation_name : ''; ?></textarea>
			</div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="vacation-form"/> Sauvegarder</button>
		<a class="btn btn-flat btn-default" href="?page=vacation">Annuler</a>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#vacation-form').submit(function(e){
			e.preventDefault();
			 $('.err-msg').remove();
			if($('input[name="vacation_date"]').length <= 0){
				$('#vacation_date').addClass("border-danger")
				$('#vacation_date-holder').after("<span class='alert alert-danger err-msg'><i class='fa fa-exclamation-triangle'></i>  You must add atleast one (1) possible prof_name for the response.</span>")
				$('#vacation_date').focus();
				return false;
			}
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_vacation",
				method:'POST',
				data:$(this).serialize(),
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(resp == 1){
						location.href = "./?page=vacation";
					}else{
						alert_toast("An error occured",'error');
						end_loader();
					}
				}
			})
		})
	})
</script>