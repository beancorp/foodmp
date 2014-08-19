	<div class="menu_tab">
		<a href="/admin/?act=fanfrenzy_grand_list&status=1">Nominees For This Month </a> |
		<a href="/admin/?act=fanfrenzy_grand_list&status=2">Nominees For Grand </a> 
	</div>
	</br>
	</br>
	</br>
	
	 {$status_text} {$total_photo_grand} 
		<table border="1">
		<tr id = "row_{$photo.photo_id}">
				<td>Sort</td>
				<td>Vote</td>
				<td>Date Entered</td>
				<td>Win Month</td>
				<td>Photo</td>
				<td>Consumer</td>
				<td>Email</td>
				<td>Action</td>
			</tr>
		{foreach from=$photos item=photo}
			<tr id="row_{$photo.photo_id}">
				<td>&nbsp;&nbsp;{$photo.sort_number}&nbsp;&nbsp;</td>
				<td>&nbsp;&nbsp;{$photo.fan_count}&nbsp;&nbsp;</td>
				<td>{$photo.time_uploaded}</td>
				<td>{$photo.win_month}</td>
				<td><a class="fan_photo" href="/photo_{$photo.photo_id}.html" target="_blank"><img width="50px" src="/fanpromo/{$photo.thumb}" /></a></td>
				<td>{$photo.consumer}</td>
				<td>{$photo.consumer_email}</td>
				
				
				<td id="send_email_{$photo.photo_id}">
					{if ($photo.is_sent_email == 1)}
						&nbsp;&nbsp;&nbsp;Mail is Sent
					{else}
					&nbsp;&nbsp;&nbsp; <a class="send_email" rel="{$photo.photo_id}" sort_number="{$photo.sort_number}" consumer_email="{$photo.consumer_email}"  consumer_name="{$photo.consumer}" href="javascript:void(0)">Send Email</a> &nbsp;&nbsp;&nbsp;
					{/if}
				</td>
				
			</tr>
		{/foreach}
	</table>
	
	<div id="list_pagination">
			{if $page_previous}
			<a href="/admin/?act=fanfrenzy_grand_list&status={$status}&p={$page_previous}">Previous</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			{/if}
			{if $page_next}
			<a href="/admin/?act=fanfrenzy_grand_list&status={$status}&p={$page_next}">Next</a>
			{/if}
	</div>
	
	
	
	
	
	
<script>
{literal}
		$(function() {
			try {
				$( ".send_email" ).click(function() {
			  		var photo_id = $(this).attr('rel');
			  		var sort_number = $(this).attr('sort_number');
			  		var consumer_name = $(this).attr('consumer_name');
			  		var consumer_email = $(this).attr('consumer_email');
			  		
			  		
			  		if (confirm("Are you sure to sent email to inform this user get prize number " + sort_number + " ?")){ 
				  		$.ajax({
				  			type: "POST",
					  		url: '/admin/fanfrenzy_grand_list.php',
					  		data: {sent_mail : 1, photo_id: photo_id, sort_number:sort_number, consumer_name: consumer_name, consumer_email: consumer_email},
					  		success: function(data, textStatus, jqXHR)
					  	    {
					  	        alert("Email Sent");
					  	        $("#send_email_"+photo_id).html("Mail Sent");
					  	    },
				  		
				  		});
			  		}
				});		
			} catch(err) {
				alert(err.message);
			}
		});
{/literal}
</script>	