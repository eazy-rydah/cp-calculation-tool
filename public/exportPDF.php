<?php

// var_dump($_POST);

exportPDF();

function exportPDF() {
    
    $pdfAuthor = "Stadtwerke Göttingen AG"; 
    
    $headerLogo1 = '<img src="swg_climate_change.png">';
    $headerLogo2 = '<img src="swg_logo.png">';

    $date = date("d.m.Y");

    $pdfName = "beratungsprotokoll-elektromobilitaet-swg.pdf";
    
    //////////////////////////// Inhalt des PDFs als HTML-Code \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    
    // Erstellung des HTML-Codes. Dieser HTML-Code definiert das Aussehen eures PDFs.
    // tcpdf unterstützt recht viele HTML-Befehle. Die Nutzung von CSS ist allerdings
    // stark eingeschränkt.
    
    $html = '
    <table cellpadding="2" cellspacing="0" style="width: 100%; ">

        <tr>
        <td>'. nl2br(trim($headerLogo1)) .'</td>
        <td style="text-align:right">'. nl2br(trim($headerLogo2)) .'</td>
        </tr>

    </table>
 
    <br><br><br>
    <h2>Protokoll: Ladeinfrastrukturplanung - Elektromobilität</h2>
    <br><br><br>
    

    <table cellpadding="2" cellspacing="0" style="width: 100%; margin-top:100px;">
        <br>
        <tr>
            <td><span style="font-weight: bold">Datum: </span> <span>'. $date .'</span></td>
            <td><span style="font-weight: bold">Ort: </span> <span>'. $_POST['input_location'] .'</span></td>
        </tr>
        <br>
        <tr>
            <td><span style="font-weight: bold">Objekt-Bezeichnung: </span> <span>'. $_POST['input_objectLabel'] .'</span></td>
            <td><span style="font-weight: bold">Objekt-Standort: </span> <span>'. $_POST['input_objectLocation'] .'</span></td>
        </tr>

    </table>
    <br>

    <hr>

    <h4>Gebäude & Ladeinfrastruktur</h4>
    <p>Bei einem verfügbaren Haussanschluss mit '. $_POST['input_hausanschluss'] .' kW und einer Gebäudelast von '. $_POST['input_gebäudelast'] .' kW verbleiben '. $_POST['anschlussLeistungLIS'] .' kW Anschlussleistung für Ladeinfrastruktur.
        <br>Unter Annahme eines Wirkleistungsfaktors von '. $_POST['input_wirkleistungsfaktor'] .', können bei einer verfügbaren AC-Ladeleistung von '. $_POST['input_ladeleistung'] .' kW,  '. floor($_POST['anzahlStellplätze']) .' Stellplätze versorgt werden.</p>
    
    <h4>Fahrzeug & Fahrverhalten</h4>
    <p>Bei einer jährlichen Fahrleistung von '. $_POST['input_jahresfahrleistung'] .' km, verteilt auf '. $_POST['input_anzahltage'] .' Tage (z.B. Werktage), ergibt sich eine tägliche Fahrleistung von '. round($_POST['täglicheFahrleistung'],2) .' km. 
    <br>Unter Annahme eines Energieverbrauchs von '. $_POST['input_verbrauch'] .' kWh/100km ergibt sich daraus ein täglicher Nachladebedarf von '. round($_POST['täglicherNachladebedarf'],2) .' kWh.</p>

    <h4>Ladezeit</h4>
    <p>Unter Anbetracht einer fahrzeugseitig maximalen Ladeleistung von '. $_POST['input_ladeleistungfahrzeug'] .' kW und einer Zusatzzeit für Ladeverstlust von '. $_POST['input_ladeverlustzeit'] .' h, erfolgt die Nachladung des täglichen Bedarfs eines E-PKW in '. round($_POST['täglicherNachladebedarfZeit'],2) .' h.
    <br>Innerhalb eines verfügbaren Zeitraumes von '. $_POST['input_ladezeitraum'] .' h, sind bei einer Zeit zum Fahrzeugwechsel '. $_POST['input_fahrzeugwechselzeit'] .' h demnach '. round($_POST['anzahlNachladungen'], 2) .' Nachladungen möglich.</p>

    <h4>Ergebnis</h4>
    <p>Unter Annahme eines Nutzfaktors von '. $_POST['input_nutzungsfaktor'] .' (z.B. privat 0,7, gewerblich 0,9) können mit der verfürbaren LIS-Anschlussleistung unter Einsatz eines dynamischen Lastmanagements
    '. floor($_POST['anzahlStellplätzeLastmanagement']) .' Stellplätze parallel versorgt werden.</p>
    <br>
    <p><span style="font-weight: bold">Hinweis: </span> Alle Angaben beruhen auf erfahrungsbasierten Annahmen und dienen lediglich zur Orientierung bei der Auslegung von Ladeinfrastruktur.</p>
    <span style="font-weight: bold">Mit freundlichen Grüßen</span><br>Ihre Stadtwerke Göttingen';
    
    //////////////////////////// Erzeugung eures PDF Dokuments \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    
    // TCPDF Library laden
    require_once('TCPDF/tcpdf.php');
    
    // Erstellung des PDF Dokuments
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Dokumenteninformationen
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($pdfAuthor);
    $pdf->SetTitle('Beratungsprotokoll: Ladeinfrastruktur');
    $pdf->SetSubject('Beratungsprotokoll: Ladeinfrastruktur');
    
    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Header und Footer Informationen
    $pdf->setHeaderFont(false);
    $pdf->setFooterFont(false);
    
    // Auswahl des Font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // Auswahl der Margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // Automatisches Autobreak der Seiten
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    // Image Scale 
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // Schriftart
    $pdf->SetFont('dejavusans', '', 10);
    
    // Neue Seite
    $pdf->AddPage();
    
    // Fügt den HTML Code in das PDF Dokument ein
    $pdf->writeHTML($html, true, false, true, false, '');
    
    //Ausgabe der PDF
    
    //Variante 1: PDF direkt an den Benutzer senden:
    $pdf->Output($pdfName, 'I');
    
    //Variante 2: PDF im Verzeichnis abspeichern:
    // $pdf->Output(dirname(__FILE__).'/'.$pdfName, 'F');
    //echo 'PDF herunterladen: <a href="'.$pdfName.'">'.$pdfName.'</a>';
}


