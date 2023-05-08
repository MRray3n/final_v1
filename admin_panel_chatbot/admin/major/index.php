<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Liste des major de promotion</h3>
		<div class="card-tools">
			<a href="?page=major de promotion/manage" class="btn btn-flat btn-info"><span class="fas fa-plus"></span>  Créer un nouveau</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="60%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>spécialité et niveau</th>
						<th> nom et prénom de l'étudiant</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT id,niv_sp, fullname FROM `major` ORDER BY fullname ASC");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['niv_sp'] ?></td>
							<td><?php echo $row['fullname'] ?></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                <span class="sr-only">Basculer la liste déroulante</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="?page=major/manage_update&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Modifier</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Supprimer</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p id="confirmationMessage"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.delete_data').click(function() {
			var id = $(this).attr('data-id');
			var confirmationMessage = "Are you sure to delete this data?";
			showConfirmationDialog(confirmationMessage, function() {
				delete_major(id);
			});
		});

		$('.table').DataTable();
	});

	function showConfirmationDialog(message, callback) {
		$('#confirmationMessage').text(message);
		$('#confirmDeleteButton').off('click').on('click', callback);
		$('#confirmationModal').modal('show');
	}
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this data?","delete_major",[$(this).attr('data-id')])
		})
		$('.table').dataTable();
	})
	function delete_major($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_major",
			method:"POST",
			data:{id:$id},
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(resp == 1){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>