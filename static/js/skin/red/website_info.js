		function add_row(group_id, row_id, category, item, price) {
			var html = '<div class="group_row" id="' + group_id + 'row_' + row_id + '">' +
						'	<div class="remove"><span class="remove_btn">-</div>' +
						'	<div class="group_row_field">' +
						'		<label>Category:</label><input type="text" name="' + group_id + '[' + row_id + '][category]" value="' + category + '" />' +
						'	</div>' +
						'	<div class="group_row_field">' +
						'		<label>Item:</label><input type="text" name="' + group_id + '[' + row_id + '][item]" value="' + item + '" />' +
						'	</div>' +
						'	<div class="group_row_field">' +
						'		<label>Price:</label><input type="text" name="' + group_id + '[' + row_id + '][price]" value="' + price + '" />' +
						'	</div>' +
						'</div>';
			$('#' + group_id).append(html);
		}
		
		var specials_index = 0;
		var stock_index = 0;
		
		function add_special(category, item, price) {
			specials_index++;
			add_row('specials', specials_index, category, item, price);	
		}
		
		function add_stock(category, item, price) {
			stock_index++;
			add_row('stock', stock_index, specials_index, category, item, price);		
		}
		
		$(document).ready(function() {
			$('#example_form').hide();
			$('.question_mark').click(function() {
				$('#example_form').dialog({
					width: 310
				});
			});
		
			$('#add_specials').click(function() {
				add_special('','','');		
			});
			
			$('#add_stock').click(function() {
				add_stock('','','');	
			});
			
			$('.remove_btn').live('click', function() {
				var row_id = $(this).parent().parent().attr("id");
				$('#' + row_id).remove();
			});
			
			$("#confirmation_dialog").dialog({
			   autoOpen: false,
			   modal: true,
			   buttons : {
				"Confirm" : function() {
					var url = "soc.php?cp=cancel_subscription";    
					$(location).attr('href',url);
				},
				"Cancel" : function() {
				  $(this).dialog("close");
				}
			  }
			});

			$("#cancel_button").on("click", function(e) {
				e.preventDefault();
				$("#confirmation_dialog").dialog("open");
			});
		});