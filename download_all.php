

<?php
/**
* FlxZipArchive, Extends ZipArchiv.
* Add Dirs with Files and Subdirs.
*
* <code>
*  $archive = new FlxZipArchive;
*  // .....
*  $archive->addDir( 'test/blub', 'blub' );
* </code>
*/
class FlxZipArchive extends ZipArchive {
    /**
     * Add a Dir with Files and Subdirs to the archive
     *
     * @param string $location Real Location
     * @param string $name Name in Archive
     * @author Nicolas Heimann
     * @access private
     **/

    public function addDir($location, $name) {
        $this->addEmptyDir($name);

        $this->addDirDo($location, $name);
     } // EO addDir;

    /**
     * Add Files & Dirs to archive.
     *
     * @param string $location Real Location
     * @param string $name Name in Archive
     * @author Nicolas Heimann
     * @access private
     **/

    private function addDirDo($location, $name) {
        $name .= '/';
        $location .= '/';

        // Read all Files in Dir
        $dir = opendir ($location);
        while ($file = readdir($dir))
        {
            if ($file == '.' || $file == '..') continue;

            // Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->$do($location . $file, $name . $file);
        }
    } // EO addDirDo();
}


$the_folder = '.';
$zip_file_name = './archive.zip';

$za = new FlxZipArchive;

$res = $za->open($zip_file_name, ZipArchive::CREATE);

if($res === TRUE) {
    $za->addDir($the_folder, basename($the_folder));
    $za->close();
	//Get the directory to zip
		// we deliver a zip file
	//header("Content-Type: archive/zip");

		// filename for the browser to save the zip file
	//header("Content-Disposition: attachment; filename=website.zip");
	//echo fpassthru($res);
}
else
    echo 'Could not create a zip archive';





?>