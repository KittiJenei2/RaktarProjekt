<?php

require 'pdf.php';

if(isset($_POST['pdf']))
{
    $pdf = new Pdf();
    $pdf->printPdf('D', 'Makeup_storage.pdf');
}