<?php
require ('tfpdf.php');
require('raktarTable.php');
class Pdf extends tFPDF
{
    function Header()
    {
        $this->Image('makeup-png-7204.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, 'Makeup', 0, 0, 'C');
        $this->Ln(20);
    }

    function HeaderTable()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(30, 10, 'Product Name', 1, 0, 'C');
        $this->Cell(30, 10, 'Price', 1, 0, 'C');
        $this->Cell(30, 10, 'Quantity', 1, 1, 'C');
    }

    function ViewTable($data)
    {
        $this->SetFont('Arial', '', 12);
        $fill = false;
        $i = 0;
        foreach ($data as $row) {
            $this->SetX((210 - 30 * 3) / 2);
            $this->Cell(30, 10, $row['name'], 1, 0, 'C', $fill);
            $this->Cell(30, 10, $row['price'], 1, 0, 'C', $fill);
            $this->Cell(30, 10, $row['quantity'], 1, 1, 'C', $fill);

            if ($i % 2 == 0) {
                $this->SetFillColor(217, 56, 171);
            } else {
                $this->SetFillColor(250, 251, 252);
            }
            $fill = !$fill;
            $i++;
        }
    }

    function printPdf($dest, $fileName)
    {
        $raktar = new Raktar();
        $productData = $raktar->getProducts();
        
        $pdf = new Pdf();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX((210 -30 * 3) / 2);
        $pdf->HeaderTable();
        $pdf->ViewTable($productData);
        ob_clean();
        $pdf->Output($dest, $fileName);
    }
}
?>