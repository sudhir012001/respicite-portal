<?php
    $pagecount = $pdf->setSourceFile('report_template/Counsellor Remarks.pdf');
    $tpl = $pdf->importPage(1);
    $pdf->AddPage();
    $pdf->useTemplate($tpl, ['adjustPageSize'=>true]);
    $pdf->SetFontSize('10','B');
    $pdf->SetXY(40,169);
    $pdf->MultiCell(50,6,$remark_date);
    
    $pdf->SetFontSize('12');
    $pdf->SetXY(35,60);
    $pdf->MultiCell(150,8,$c_remark);
    
    $pdf->SetXY(105, 169);
    $pdf->SetFont('arial');
    $pdf->Cell(0,20,$pdf->Image($signature,$pdf->GetX(), $pdf->GetY(),30), 0, 0,'R',false);
    ?>