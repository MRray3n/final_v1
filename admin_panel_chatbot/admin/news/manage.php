<style>
	.q-item {
	    border-radius: 50px;
	}


</style>
<?php
if(isset($_GET['id']) && !empty($_GET['id'])){
	$qry = $conn->query("SELECT id, eventn, descr FROM `news` where id = '{$_GET['id']}'");
	foreach($qry->fetch_array() as $k => $v){
		if(!is_numeric($k)){
			$$k = $v;
		}
	}
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($_GET['id']) ? "Update ": "Create New " ?>événement</h3>
	</div>
	<div class="card-body">
		<form action="" id="news-form">			
			<div class="form-group">
				<label for="eventn_field" class="control-label">événement</label>
				<div class="input-group col-lg-6">
				<input type="hidden" id="id" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                <input type="text" id="eventn" name="eventn" class="form-control form-control-sm" data-original-title="" title="" value="<?php echo isset($eventn) ? $eventn : ''; ?>">

                    
                </div>
			</div>
			<div class="form-group">
				<label for="descr" class="control-label">description</label>
				<textarea name="descr" id="descr" cols="30" rows="3" class="form-control" style="resize: none" required><?php echo isset($descr) ? $descr : ''; ?></textarea>
			</div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="news-form">Sauvegarder</button>
		<a class="btn btn-flat btn-default" href="?page=news">Annuler</a>
	</div>
</div>
<script>
	
	$(document).ready(function(){
		$('#news-form').submit(function(e){
			e.preventDefault();
			 $('.err-msg').remove();
			if($('input[name="eventn"]').length <= 0){
				$('#eventn').addClass("border-danger")
				$('#eventn-holder').after("<span class='alert alert-danger err-msg'><i class='fa fa-exclamation-triangle'></i>  You must add atleast one (1) possible prof_name for the response.</span>")
				$('#eventn').focus();
				return false;
			}
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_response",
				method:'POST',
				data:$(this).serialize(),
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(resp == 1){
						location.href = "./?page=news";
					}else{
						alert_toast("An error occured",'error');
						end_loader();
					}
				}
			})
		})
	})
</script>