<?php

namespace A4\App\Views;

/**
 * Description of vtareaver
 *
 * @author linux
 */

use A4\Sys\View;

class vTareaVer extends View{
    public function __construct($dataView = null, $dataTable = null) {
        parent::__construct($dataView, $dataTable);
        $this->output= $this->render('ttarea_ver.php');
    }
}
