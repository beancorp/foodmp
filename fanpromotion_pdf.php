<?php
ini_set('pcre.backtrack_limit', 1000000);
ini_set('memory_limit', '-1');
@set_time_limit(10000);

include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;
require_once('include/html2ps/config.inc.php');
require_once('include/html2ps/pipeline.factory.class.php');

class MyDestinationFile extends Destination
{
	var $_dest_filename;
	
	function MyDestinationFile($dest_filename)
	{
		$this->_dest_filename = $dest_filename;
	}
	
	function process($tmp_filename, $content_type)
	{
		copy($tmp_filename, $this->_dest_filename);
	}
}

class MyFetcherMemory extends Fetcher
{
	var $base_path;
	var $content;
	
	function MyFetcherMemory($content, $base_path)
	{
		$this->content   = $content;
		$this->base_path = $base_path;
	}
	
	function get_data($url)
	{
		if (!$url)
		{
			return new FetchedDataURL($this->content, array(), "");
		}
		else
		{
			if (substr($url, 0, 8) == 'file:///')
			{
				$url = substr($url,8);
				if (PHP_OS == "WINNT")
				{
					$url = substr($url,1);
				}
			}
			return new FetchedDataURL(@file_get_contents($url), array(), "");
		}
	}
	
	function get_base_url()
	{
		return 'file:///'.$this->base_path.'/dummy.html';
	}
}

function convert_to_pdf($body_html, $path_to_pdf = NULL, $base_path = "") {
	$pipeline = PipelineFactory::create_default_pipeline('', '');
	$pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields();
	$pipeline->fetchers[] = new MyFetcherMemory($body_html, $base_path);
	if ($path_to_pdf)
	{
		$pipeline->destination = new MyDestinationFile($path_to_pdf);
	}
	else
	{
		$pipeline->destination = new DestinationBrowser(null);
	}
	
	$baseurl = '';
	$media =& Media::predefined('A4');
	$media->set_landscape(false);
	$media->set_margins(array('left' => 10,
	'right' => 10,
	'top' => 10,
	'bottom' => 10));
	$media->set_pixels(750);
	
	global $g_config;
	$g_config = array
	(
		'cssmedia'         => 'screen',
		'scalepoints'      => '1',
		'renderimages'     => true,
		'renderlinks'      => true,
		'renderfields'     => true,
		'renderforms'      => false,
		'method'           => 'fpdf',
		'mode'             => 'html',
		'encoding'         => 'utf-8',
		'pdfversion'       => '1.4',
		'draw_page_border' => false,
		'smartpagebreak'   => true,
		'transparency_workaround' => true
	);
	$pipeline->configure($g_config);
	$pipeline->output_driver = new OutputDriverFPDF();
	$pipeline->process_batch(array($baseurl), $media);
}

function download_pdf($path)
{
	// header('Content-type:application/pdf; charset=utf-8');
	// header('Content-Disposition:attachment; filename="'. basename($path) .'"');
	header('Content-type:application/pdf; charset=utf-8');
	header('Content-Disposition: inline; filename="'. basename($path) .'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($path));
	header('Accept-Ranges: bytes');
	readfile($path);
}

$html = file_get_contents(SOC_HTTP_HOST.'foodwine/fanpromotion_print.php?StoreID='.$_SESSION['StoreID'].'&print=0&s=c427b395ea2bab03cbd4a82d153ed778'.(isset($_GET['subAttrib']) ? '&subAttrib='.$_GET['subAttrib'] : ''));
$file	= 'upload/pdf/'.$_SESSION['StoreID'].'_fanpromotion.pdf';

parse_config_file(HTML2PS_DIR .'html2ps.config');
convert_to_pdf($html, $file);
if (!is_null($file)) { download_pdf($file); }

?>