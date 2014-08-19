<?php

define('FEATURE_ENCRYPTION_COPY', 1);
define('FEATURE_ENCRYPTION_PRINT', 2); 
define('FEATURE_ENCRYPTION_MODIFY', 4);
define('FEATURE_ENCRYPTION_MODIFY_ANNOTATIONS', 8);

class FeatureEncryption {
  var $_mode = 0;

  function FeatureEncryption() {
  }

  function install(&$pipeline, $params) {
    $dispatcher =& $pipeline->get_dispatcher();
    $dispatcher->add_observer('after-driver-init', array(&$this, 'handle_driver_init'));

    $this->_mode = $params['mode'];
  }

  function handle_driver_init($params) {
    $pipeline =& $params['pipeline'];

    require_once(HTML2PS_DIR.'pdf.fpdf.encryption.php');

    if (is_a($pipeline->output_driver, 'OutputDriverFPDF')) {
      $old_pdf =& $pipeline->output_driver->pdf;

      $pipeline->output_driver->pdf =& new FPDF_Protection('P', 
                                                           'pt', 
                                                           array($old_pdf->fw,
                                                                 $old_pdf->fh));
      $pipeline->output_driver->pdf->SetProtection($this->make_fpdf_protection_mode());
    };
  }

  function make_fpdf_protection_mode() {
    $result = array();

    if ($this->_mode & FEATURE_ENCRYPTION_COPY) { 
      $result[] = 'copy';
    };

    if ($this->_mode & FEATURE_ENCRYPTION_PRINT) { 
      $result[] = 'print';
    };

    if ($this->_mode & FEATURE_ENCRYPTION_MODIFY) { 
      $result[] = 'modify';
    };

    if ($this->_mode & FEATURE_ENCRYPTION_MODIFY_ANNOTATIONS) { 
      $result[] = 'annot-forms';
    };

    return $result;
  }
}

?>