<!DOCTYPE html>
<html>
<head>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<title></title>
</head>
<body>
	<?php 
	

	?>
	<div class="container ">
		<h3>Chat Application</h3>
		<div class="table-responsive text-center">
			<h4>Online Users</h4>
			<p align="right"> Hi - <?php print_r($this->session->userdata('username')) ?> <a href="<?php base_url()?>destroy">Logout</a> </p>
			<div id='user_details'></div>
			<div id="user_model_details"></div>

		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){

			
			fetch_user();

			setInterval(function(){
				update_last_activity();
				fetch_user();
				update_chat_history_data();
				console.log("Ravindra");
			}, 50);


			function fetch_user()
			{
				$.ajax({
					url:"<?php echo base_url()?>Chat/fetch_user",
					method:"POST",
					success:function(data){
						$('#user_details').html(data);
					}
				})
				// console.log("jhkjsdgf");
			}


			function update_last_activity()
			{
				$.ajax({
					url:"<?php echo base_url()?>Chat/update",
					success:function()
					{

					}
				})
			}

			function make_chat_dialog_box(to_user_id, to_user_name)
			{
				var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have chat with '+to_user_name+'">';
				modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
				modal_content += fetch_user_chat_history(to_user_id);
				modal_content += '</div>';
				modal_content += '<div class="form-group">';
				modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
				modal_content += '</div><div class="form-group" align="right">';
				modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
				$('#user_model_details').html(modal_content);
			}

			$(document).on('click', '.start_chat', function(){
				var to_user_id = $(this).data('touserid');
				var to_user_name = $(this).data('tousername');
				make_chat_dialog_box(to_user_id, to_user_name);
				$("#user_dialog_"+to_user_id).dialog({
					autoOpen:false,
					width:400
				});
				$('#user_dialog_'+to_user_id).dialog('open');
			});



			$(document).on('click', '.send_chat', function(){
				var to_user_id = $(this).attr('id');
				var chat_message = $('#chat_message_'+to_user_id).val();
				$.ajax({
					url:"<?php echo base_url()?>Chat/sendChat",
					method:"POST",
					data:{to_user_id:to_user_id, chat_message:chat_message},
					success:function(data)
					{
						$('#chat_message_'+to_user_id).val('');
						$('#chat_history_'+to_user_id).html(data);
					}
				})
			});


			function fetch_user_chat_history(to_user_id)
			{
				$.ajax({
					url:"<?php echo base_url()?>Chat/fetch_user_chat_history",
					method:"POST",
					data:{to_user_id:to_user_id},
					success:function(data){
						$('#chat_history_'+to_user_id).html(data);
					}
				})
			}
			
			function update_chat_history_data()
			{
				$('.chat_history').each(function(){
					var to_user_id = $(this).data('touserid');
					fetch_user_chat_history(to_user_id);
				});
			}

		}); 
	</script>
</body>
</html>