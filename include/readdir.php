<?php
//Open images directory
$dir = dir("..");

//List files in images directory
while (($file = $dir->read()) !== false)
{
echo "filename: " . $file . "<br />";
}

$dir->close();
?> 