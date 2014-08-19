<?php

ini_set('max_execution_time', '180');

/**
 * remove all files and folders in the given derectory
 * @return void
 */
function removeDir($dir)
{
	if ($dh = opendir($dir))
	{
		while (($file = readdir($dh)) !== false)
		{
			if ($file != '.' && $file != '..')
			{
				if (is_dir($dir .'/'. $file))
				{
					removeDir($dir .'/'. $file);
					echo (@rmdir($dir .'/'. $file) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'RD '. $dir .'/'. $file .'<br />';
				}
				else
				{
					echo (@unlink($dir .'/'. $file) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'remove '. $dir .'/'. $file .'<br />';
				}
			}
		}
		closedir($dh);
	}
	else
	{
		echo 'Could not open '. $dir .'<br />';
	}
}

/**
 * remove given derectory, including sub files and folders
 * @return void
 */
function removeDir2($dir)
{
	if (is_dir($dir))
	{
		removeDir($dir);
		echo (@rmdir($dir) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'RD '. $dir .'<br />';
	}
	elseif (is_file($dir))
	{
		echo (@unlink($dir) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'remove '. $dir .'<br />';
	}
}

/**
 * remove all files and folders which are created by SVN or Dreamweaver in the given derectory
 * @return void
 */
function removeDreamweaverSVN($dir = '.')
{
	if ($dh = opendir($dir))
	{
		while (($file = readdir($dh)) !== false)
		{
			if ($file != '.' && $file != '..')
			{
				if (is_dir($dir .'/'. $file))
				{
					if ($file == '.svn' || $file == '_notes')
					{
						removeDir($dir .'/'. $file);
						echo (@rmdir($dir .'/'. $file) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'RD '. $dir .'/'. $file .'<br />';
					}
					else
					{
						removeDreamweaverSVN($dir .'/'. $file);
					}
				}
			}
		}
		closedir($dh);
	}
	else
	{
		echo 'Could not open '. $dir .'<br />';
	}
}

/**
 * Set 777 all files and folders in the given folder
 * @return void
 */
function set777All($dir = '.')
{
	if ($dh = opendir($dir))
	{
		while (($file = readdir($dh)) !== false)
		{
			if ($file != '.' && $file != '..')
			{
				echo (@chmod($dir .'/'. $file, 0777) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'Change '. $dir .'/'. $file .'<br />';
				if (is_dir($dir .'/'. $file)) set777All($dir .'/'. $file);
			}
		}
		closedir($dh);
	}
	else
	{
		echo 'Could not open '. $dir .'<br />';
	}
}


/**
 * Set file or directory to 777, including sub files and folders if given a directory
 * @return void
 */
function set777($dir)
{
	if (is_dir($dir))
	{
		set777All($dir);
	}
	
	echo (@chmod($dir, 0777) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'Change '. $dir .'<br />';
}

/**
 * set normal accessing rights: file 644, folder 755
 * @return void
 */
function setNormal($dir = '.')
{
	if ($dh = opendir($dir))
	{
		while (($file = readdir($dh)) !== false)
		{
			if ($file != '.' && $file != '..')
			{				
				if (is_dir($dir .'/'. $file))
				{
					echo (@chmod($dir .'/'. $file, 0755) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'Change '. $dir .'/'. $file .'<br />';
					setNormal($dir .'/'. $file);
				}
				else
				{
					echo (@chmod($dir .'/'. $file, 0644) ? '<span class="success">Successfully</span> ' : '<span class="failed">Failed</span> to ') .'Change '. $dir .'/'. $file .'<br />';
				}
			}
		}
		closedir($dh);
	}
	else
	{
		echo 'Could not open '. $dir .'<br />';
	}
}

/**
 * Make writable all folders which may be added files in of Joomla 1.5
 * @return void
 */
function set777JosFolders($version = '1.5')
{
	$jos10Cats = array
	(
		'administrator/backups',
		'administrator/components',
		'administrator/modules',
		'administrator/templates',
		'components',
		'images',
		'images/banners',
		'images/stories',
		'language',
		'mambots',
		'mambots/content',
		'mambots/editors',
		'mambots/editors-xtd',
		'mambots/search',
		'mambots/system',
		'media',
		'modules',
		'templates',
		'cache'
	);
	
	$jos15Cats = array
	(
		'administrator/backups',
		'administrator/components',
		'administrator/language',
		'administrator/language/en-GB',
		'administrator/modules',
		'administrator/templates',
		'components',
		'images',
		'images/banners',
		'images/stories',
		'language',
		'language/en-GB',
		'language/pdf_fonts',
		'media',
		'modules',
		'plugins',
		'plugins/content',
		'plugins/editors',
		'plugins/editors-xtd',
		'plugins/search',
		'plugins/system',
		'plugins/user',
		'plugins/xmlrpc',
		'tmp',
		'templates',
		'cache',
		'administrator/cache'
	);
	
	$josCats = ($version == '1.5') ? $jos15Cats : $jos10Cats;
	
	foreach ($josCats as $cat)
	{
		echo @chmod($cat, 0777) ? ('777 '. $cat .' <span class="success">Successfully</span>.<br />') : ('<span class="failed">Failed</span> to 777 '. $cat .'<br />');
	}
}

$act = $_POST['act'];
$dir = ($_POST['dir'] != '') ? $_POST['dir'] : ($act != 'removeDir' ? '.' : '');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" >
<title>Usual File Commands</title>
<head>
<style type="text/css">
<!--
body { background:#000000; color:#FFF; font-family:Arial, Helvetica, sans-serif; font-size:14px; }

.button { font-family:Arial, Helvetica, sans-serif; }
.desc { font-style:italic; font-size:12px; }
.inputbox { padding:0 2px; width:300px; }

.success { color:#00FF00; }
.failed { color:#FF0000; }

.acts { margin-bottom:15px; }
.acts p { margin-bottom:5px; }
.acts ul { margin:0; padding:0; }
.acts ul li { list-style:none; margin:0; }

.dir { margin-bottom:15px; }
.dir label { display:block; margin-bottom:5px; }
-->
</style>
</head>

<body>
<?php
if (!empty($act))
{
	ob_start();
	
	switch ($act)
	{
		case 'removeDir':            removeDir2($dir);          break;
		case 'removeDreamweaverSVN': removeDreamweaverSVN('.'); break;
		case 'set777All':            set777($dir);              break;
		case 'setNormal':            setNormal($dir);           break;
		case 'set777J10Folders':     set777JosFolders('1.0');   break;
		case 'set777J15Folders':     set777JosFolders();        break;
	}
	
	$html = ob_get_contents();
	ob_end_clean();
	
	echo $html;
	?>

<br />

<input type="button" value="Back" onclick="location.href='fileCommands.php'" class="button" />

<?php } else { ?>

<form method="post">
<div class="acts">
	<p>Select an active:</p>
	<ul>
		<li><label for="act_5"><input type="radio" id="act_5" name="act" value="set777J10Folders" onclick="document.getElementById('dir_frame').style.display='none';" /> Make all folders writable, in which files may be added or edited (for Joomla 1.0)</label></li>
		<li><label for="act_6"><input type="radio" id="act_6" name="act" value="set777J15Folders" onclick="document.getElementById('dir_frame').style.display='none';" /> Make all folders writable, in which files may be added or edited (for Joomla 1.5)</label></li>
		<li><label for="act_2"><input type="radio" id="act_2" name="act" value="removeDreamweaverSVN" onclick="document.getElementById('dir_frame').style.display='none';" /> Remove files and folders created by SVN or Dreamweaver</label></li>
		<li><label for="act_1"><input type="radio" id="act_1" name="act" value="removeDir" onclick="document.getElementById('dir_frame').style.display='block'; document.getElementById('dir').value=''" /> Remove file or folder</label></li>
		<li><label for="act_3"><input type="radio" id="act_3" name="act" value="set777All" onclick="document.getElementById('dir_frame').style.display='block';" /> Set file or directory to 777, including sub files and folders if given a directory</label></li>
		<li><label for="act_4"><input type="radio" id="act_4" name="act" value="setNormal" onclick="document.getElementById('dir_frame').style.display='block';" /> Set files to 644, folders to 755 in the given directory</label></li>
	</ul>
</div>
<div id="dir_frame" class="dir">
	<label for="dir">Directory <span class="desc">(Enter path of a file or folder without the ending `/`)</span>:</label>
	<input type="text" id="dir" name="dir" value="" class="inputbox" />
</div>
<div>
	<input type="submit" name="submit" value="Submit" class="button" />
</div>
</form>

<?php } ?>
</body>
</html>