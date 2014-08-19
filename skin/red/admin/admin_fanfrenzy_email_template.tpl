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
    width: 300px;
    height: 300px;
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
    <select id="content_pages" size="20">
        {$select_options}
    </select>
</div>

<div id="content_page">
	<div class="processing">
		<img src="/images/loading.gif" alt='Loading.......'>
	</div>	
	
	<textarea rows="4" cols="50" id="variables" class="input_data">
	 
	</textarea>
	
	<br>
	<br>
	<br>

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
        
        
        function fetch_content(text_id) {
        		$( ".input_data" ).hide();
        		$( ".processing" ).show();
                $.ajax({
                    url: 'index.php?act=fanfrenzy_template_email',
                    type: 'post',
                    data: {text_id: text_id},
                    dataType: 'json',
                    success: function(response) {
                        $( "#tinyeditor_content" ).show();
                        $( "#variables" ).show();

                        $( "#variables" ).html(response.variables);
                        
                        editor = get_editor(response.content);
                        $( ".processing" ).hide();
                    }
                });
        }
        
        $(document).ready(function() {
            $("#content_pages").val($("#content_pages option:first").val());
            fetch_content($("#content_pages option:first").val());    

            $('#save_button').click(function() {
                
                var text_id = $('#content_pages').val();                

                 
				editor.post();
				var content_page = editor.t.value;				
                
                $.ajax({
                    url: 'index.php?act=fanfrenzy_template_email',
                    type: 'post',
                    data: {text_id: text_id, content_page: content_page},
                    dataType: 'json',
                    success: function(response) {
                        $('#error_or_success_message').html(response.message);
                        $('#error_or_success_message').show();
                    }
                });    
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