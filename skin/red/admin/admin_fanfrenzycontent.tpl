<link rel="stylesheet" href="/skin/red/admin/tinyeditor.css">
<script src="/skin/red/admin/tiny.editor.packed.js"></script>


<style>
{literal}
#save_button {
    padding: 10px;
    margin-left: auto;
    margin-right: auto;
    width: 60px;
    height: 40px;
    margin-top: 10px;
    cursor: pointer;
}
#content_page {
    margin-left: auto;
    margin-right: auto;
    width: 600px;
}
#content_pages_left {
    float: left;
    margin-right: 10px;
}
#content_page {
    float: left;
}
#content_pages {
    padding: 10px;
    width: 200px;
    height: 200px;
}
#error_or_success_message {
    clear: both;
    color: #00ff00;
    font-size: 14px;
    font-weight: bold;
    height: 25px;
    margin-left: 225px;
    width: 300px;
}

#datepicker, .processing{
	display:none;
}
{/literal}
</style>

<div id="error_or_success_message"></div>
    
<div id="content_pages_left">
    <select id="content_pages" size="5">
        {$select_options}
    </select>
</div>

<div id="content_page">
	<div class="processing">
		<img src="/images/loading.gif" alt='Loading.......'>
	</div>	
	
	
	
	<input  class="type_var" id="type_var" type="hidden"/>
	<input  class="key_name" id="key_name" type="hidden"/>		
	<br>
	<br>
	<br>
	<textarea  class="input_data" id="guide"  rows="4" cols="50"/></textarea>
	<br>

	<input  class="input_data" id="datepicker" type="text"/>	
	<input class="input_data" type="checkbox" id="checkbox"><br>


    <div class="input_data"    id="tinyeditor_content">
    
    </div>
    <script>
    {literal}
        
    
    
        function get_editor(content) {
        
            $('#tinyeditor_content').empty();
            $('#tinyeditor_content').html('<textarea id="tinyeditor" style="width: 400px; height: 200px"></textarea>');
            
            return new TINY.editor.edit('editor', {
                id: 'tinyeditor',
                width: 800,
                height: 600,
                content: content,
                cssclass: 'tinyeditor',
                controlclass: 'tinyeditor-control',
                rowclass: 'tinyeditor-header',
                dividerclass: 'tinyeditor-divider',
                controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'subscript', 'superscript', '|',
                    'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign',
                    'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n',
                    'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'],
                footer: true,
                fonts: ['Verdana','Arial','Georgia','Trebuchet MS'],
                xhtml: true,
                cssfile: 'custom.css',
                bodyid: 'editor',
                footerclass: 'tinyeditor-footer',
                toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
                resize: {cssclass: 'resize'}
            });
        }
    
    
        var editor = get_editor('');
        
        //fetch content
        function fetch_content(text_id) {
        		$( ".input_data" ).hide();
        		$( ".processing" ).show();
        		
            
                $.ajax({
                    url: 'index.php?act=fanfrenzycontent',
                    type: 'post',
                    data: {text_id: text_id},
                    dataType: 'json',
                    success: function(response) {
                        if (response.type_var == "date"){
                        	$( "#datepicker" ).val(response.content);
                        	$( "#datepicker" ).show();
                        }else if (response.type_var == "checkbox"){
                        	$( "#checkbox" ).show();
                        	if (response.content == 1)
                        		$('#checkbox').attr('Checked',true);
                        	else
                        		$('#checkbox').attr('Checked',false);
                        }else{
                        	$( "#tinyeditor_content" ).show();
                        	editor = get_editor(response.content);
                        }                        
                        $( "#type_var" ).val(response.type_var);
                        $( "#key_name" ).val(response.key_name);
                        $( "#guide" ).show();
                        $( "#guide" ).val(response.guide);
                        $( ".processing" ).hide();
                    }
                });
        }
        
        $(document).ready(function() {
        	$( "#datepicker" ).datepicker();

            
            $("#content_pages").val($("#content_pages option:first").val());
            fetch_content($("#content_pages option:first").val());    

            $('#save_button').click(function() {
                
                var text_id = $('#content_pages').val();

                var type_var = $( "#type_var" ).val();

                var guide = $( "#guide" ).val();

                if (type_var == "date"){ 
                	var content_page = $( "#datepicker" ).val();
                }else if(type_var == "checkbox"){
                	if ($('#checkbox').is(":checked"))
                		var content_page = 1;
                	else
                		var content_page = 0;            		
                }else{ 
                	editor.post();
                	var content_page = editor.t.value;
                }
                var key_name = $( "#key_name" ).val();

                $('#error_or_success_message').html("<img src='/images/loading.gif' alt='Loading.......'>");
				
                if (key_name == "grand-trigger"  && content_page  == 1){
					if (confirm("Are you sure to turn on this. This will reset all vote and start Grand")){
						$.ajax({
		                    url: 'index.php?act=fanfrenzycontent',
		                    type: 'post',
		                    data: {text_id: text_id, content_page: content_page, type_var: type_var, key_name: key_name, guide: guide},
		                    dataType: 'json',
		                    success: function(response) {
		                        $('#error_or_success_message').html(response.message);
		                        $('#error_or_success_message').show();
		                    }
		                });
					}
				}else{
	                $.ajax({
	                    url: 'index.php?act=fanfrenzycontent',
	                    type: 'post',
	                    data: {text_id: text_id, content_page: content_page, type_var: type_var, key_name: key_name, guide: guide},
	                    dataType: 'json',
	                    success: function(response) {
	                        $('#error_or_success_message').html(response.message);
	                        $('#error_or_success_message').show();
	                    }
	                });   
				} 
				 
            });
            
            $("#content_pages").change(function() {
                fetch_content($(this).val());
                $('#error_or_success_message').hide();
            });

            
        });
        

        
    {/literal}
    </script>
    <input type="button" id="save_button" value="Save" />
</div>