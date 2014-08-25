	
	<form  method="get">
	<label for="search_text">Search</label>
	<input type="text" placeholder="Unique Id" name="search_text" id="search_text"" class="watermark" autocomplete="off" value={$search_text}>
	
	
	<label for="search_categories">Status</label>
	<select id="search_categories" name="photo_status">
		<option value="3">All</option>
		<option value="0" {if $photo_status === 0} selected{/if}>Pending </option>
		<option value="1" {if $photo_status === 1} selected{/if}>Approved</option>
		<option value="2" {if $photo_status === 2} selected{/if}>Rejected</option>
	</select>
	<input type="hidden" value="photos" name="act">
	<input type="submit" value="Submit">
	</form>
				
	
	<!-- 
	<div class="menu_tab">
		<a href="/admin/?act=photos&photo_status=0">Pending Approval </a> |
		<a href="/admin/?act=photos&photo_status=1">Approved </a> |
		<a href="/admin/?act=photos&photo_status=2">Rejected </a> 
	</div>
	 -->
	</br>
	</br>
	</br>
	
	Total: {$total_photo} {$status_text} photos found
		<table border="1">
		<tr>
				<td>Photo Unique ID</td>
				<td>Date Entered</td>
				<td>Photo</td>
				<td>Username</td>
				<td>Retailer</td>
				<td>Code</td>
				<td>Status</td>
				<td>Action</td>
			</tr>
		{foreach from=$photos item=photo}
			<tr>
				<td>{$photo.unique_id}</td>
				<td>{$photo.time_uploaded}</td>
				<td><a class="fan_photo" href="/photo_{$photo.photo_id}.html" target="_blank"><img width="200px" src="/fanpromo/{$photo.thumb}" /></a></td>
				<td>{$photo.consumer}</td>
				<td>{$photo.retailer}</td>
				<td>{$photo.code}</td>
				
				<td>
					<select id="status_select_{$photo.photo_id}">
   						{html_options values=$photo_status_value output=$photo_status_label selected=$photo.approved}
					</select>
					<a class="change_status_photo" rel="{$photo.photo_id}" href="javascript:void(0)"> Submit </a>		
				</td>
				<td>
					<a class="fan_photo" href="/photo_{$photo.photo_id}.html" target="_blank">View Detail</a>
				</td>
			</tr>
		{/foreach}
	</table>
	
	<div id="list_pagination">
			{if $page_previous}
			<a href="/admin/?act=photos&photo_status={$photo_status}&p={$page_previous}&search_text={$search_text}">Previous</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			{/if}
			{if $page_next}
			<a href="/admin/?act=photos&photo_status={$photo_status}&p={$page_next}&search_text={$search_text}">Next</a>
			{/if}
	</div>
	
	
	
	
	
	
<script>
{literal}
		$(function() {
			try {
				$( ".change_status_photo" ).click(function() {
			  		var photo_id = $(this).attr('rel');
			  		var status_value = $( "#status_select_" + photo_id ).val();
			  		
			  		$.ajax({
			  			type: "POST",
				  		url: '/admin/photos.php',
				  		data: {change_status_photo : 1, photo_id: photo_id, status_value: status_value, },
				  		success: function(data, textStatus, jqXHR)
				  	    {
				  	        alert("status updated");
				  	        location.reload();
				  	    },
			  		
			  		});
			  		
				});		
			} catch(err) {
				alert(err.message);
			}
		});
{/literal}
</script>	