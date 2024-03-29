<?php

$errors = [];

// VALIDATION
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['input_date'] == '') {
        
        $errors[] = "Datum der Beratung angeben!";

    }

    if ($_POST['input_location'] == '') {
        
        $errors[] = "Ort der Beratung angeben!";

    }

    if ($_POST['input_objectLabel'] == '') {
        
        $errors[] = "Objekt-Bezeichnung angeben!";

    }

    if ($_POST['input_objectLocation'] == '') {
        
        $errors[] = "Objekt-Standort angeben!";

    }

    
    if ($_POST['input_hausanschluss'] == '') {
        
        $errors[] = "Hausanschluss angeben!";

    }

    if ($_POST['input_gebäudelast'] == '') {
        
        $errors[] = "Gebäudelast angeben!";

    }

    if ($_POST['input_wirkleistungsfaktor'] == '') {
        
        $errors[] = "Wirkleistungsfaktor angeben!";

    }

    
    if ($_POST['input_ladeleistung'] == '') {
        
        $errors[] = "AC-Ladeleistung angeben!";

    }


    if ($_POST['input_jahresfahrleistung'] == '') {
        
        $errors[] = "Jahresfahrleistung angeben!";

    }

    if ($_POST['input_anzahltage'] == '') {
        
        $errors[] = "Anzahl Tage angeben!";

    }

    if ($_POST['input_verbrauch'] == '') {
        
        $errors[] = "Energieverbrauch angeben!";

    }

    if ($_POST['input_ladeleistungfahrzeug'] == '') {
        
        $errors[] = "Ladeleistung des Fahrzeugs angeben!";

    }

    if ($_POST['input_ladeverlustzeit'] == '') {

        $errors[] = "Ladeverlustzeit angeben!";

    }

    if ($_POST['input_ladezeitraum'] == '') {
        
        $errors[] = "Ladezeitraum angeben!";

    }

    if ($_POST['input_fahrzeugwechselzeit'] == '') {

        $errors[] = "Zeit zum Fahrzeugwechsel angeben!";

    } 

    if ($_POST['input_nutzungsfaktor'] == '') {

        $errors[] = "Nutzungsfaktor angeben!";

    } 

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
    
    $anschlussLeistungLIS = $_POST['input_hausanschluss'] - $_POST['input_gebäudelast'];

    $anzahlStellplätze = $anschlussLeistungLIS *  $_POST['input_wirkleistungsfaktor'] * 1 / ($_POST['input_ladeleistung']);

    $täglicheFahrleistung = $_POST['input_jahresfahrleistung'] / $_POST['input_anzahltage'];

    $täglicherNachladebedarf = $täglicheFahrleistung * $_POST['input_verbrauch'] / 100;

    $täglicherNachladebedarfZeit = ($täglicherNachladebedarf / $_POST['input_ladeleistungfahrzeug']) + $_POST['input_ladeverlustzeit'];

    $anzahlNachladungen = $_POST['input_ladezeitraum'] / ($täglicherNachladebedarfZeit + $_POST['input_fahrzeugwechselzeit']);

    $anzahlStellplätzeLastmanagement = $anzahlNachladungen * $anzahlStellplätze * 1 / $_POST['input_nutzungsfaktor'];

    $results = [
        'anschlussleistungLIS' => $anschlussLeistungLIS,
        'anzahlStellplätze' => $anzahlStellplätze,
        'täglicheFahrleistung' => $täglicheFahrleistung,
        'täglicherNachladebedarf' => $täglicherNachladebedarf,
        'täglicherNachladebedarfZeit' => $täglicherNachladebedarfZeit,
        'anzahlNachladungen' => $anzahlNachladungen,
        'anzahlStellplätzeLastmanagement' => $anzahlStellplätzeLastmanagement
    ];

}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SWG LIS Beratungstool</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>

<div class="container">

    <h1>SWG LIS Kalkulator</h1>

    <?php 

        echo "<ul>";

        foreach ($errors as $error) {
            echo "<li>". $error ."</li>";
        }

        echo "</ul>";

    ?>

    <form action="" method="post">

        <div class="row">

            <div class="col-md-6">

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="date">Datum</span>
            </div>
            <input type="date" name="input_date" id="date" value="<?= $_POST['input_date']; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6">

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="location">Ort</span>
            </div>
            <input type="text" name="input_location" id="location" value="<?php echo isset($_POST['input_location']) ?  $_POST['input_location'] :  '' ; ?>" class="form-control"> 
            </div>

            </div>
        
        </div>

        <div class="row">

            <div class="col-md-6">

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="date">Bezeichnung</span>
            </div>
            <input type="text" name="input_objectLabel" id="objectLabel" value="<?php echo isset($_POST['input_objectLabel']) ?  $_POST['input_objectLabel'] :  '' ; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="location">Standort</span>
                </div>
                <input type="text" name="input_objectLocation" id="objectLocation" value="<?php echo isset($_POST['input_objectLocation']) ?  $_POST['input_objectLocation'] :  '' ; ?>" class="form-control"> 
            </div>

            </div>

        </div>

        <hr>

        <h2>Gebäude & Ladeinfrastruktur</h2>

        <div class="row">

            <div class="col-md-6">

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="input_hausanschluss">Hausanschluss in kW</span>
            </div>
            <input type="number" name="input_hausanschluss" id="input_hausanschluss" value="<?= $_POST['input_hausanschluss']; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6">

            <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="input_gebäudelast">Gebäudelast in kW</span>
            </div>
            <input type="number" name="input_gebäudelast" id="input_gebäudelast" value="<?= $_POST['input_gebäudelast']; ?>" class="form-control">
            </div>

            </div>

            <div class="col">
            Ergibt: <?php echo isset($anschlussLeistungLIS) ? "<strong>". $anschlussLeistungLIS . "</strong>" :  '___';  ?> kW verbleibende Anschlussleistung für Ladeinfrastruktur.
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">

            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_wirkleistungsfaktor">Wirkleistungsfaktor</span>
                </div>
                <input type="number" step="0.01" name="input_wirkleistungsfaktor" id="input_wirkleistungsfaktor" value="<?= $_POST['input_wirkleistungsfaktor']; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6">

            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_ladeleistung">AC-Ladeleistung in kW</span>
                </div>
                <input type="number" name="input_ladeleistung" id="input_ladeleistung" value="<?= $_POST['input_ladeleistung']; ?>" class="form-control">
            </div>

            </div>

            <div class="col">
            Ergibt: <?php echo isset($anzahlStellplätze) ? "<strong>". round($anzahlStellplätze, 2, PHP_ROUND_HALF_DOWN) . "</strong>" :  '___';  ?> Stellplätze mit statischem Lastmanagement.
            </div>

        </div>

        <br>

        <h2>Fahrzeug & Fahrverhalten</h2>
        
        <div class="row">

            <div class="col-md-6">

            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_jahresfahrleistung">Jährliche Fahrleistung in km</span>
                </div>
                <input type="number" step="0.01" name="input_jahresfahrleistung" id="input_jahresfahrleistung" value="<?= $_POST['input_jahresfahrleistung']; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6">

            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_anzahltage">verteilt auf Tage (z.B. Werktage)</span>
                </div>
                <input type="number" name="input_anzahltage" id="input_anzahltage" value="<?= $_POST['input_anzahltage']; ?>" class="form-control">
            </div>

            </div>

            <div class="col">
            Ergibt: <?php echo isset($täglicheFahrleistung) ? "<strong>". round($täglicheFahrleistung, 2, PHP_ROUND_HALF_DOWN) . "</strong>" :  '___';  ?> tägliche Fahrleistung in km.
            </div>

        </div>

        <br>

        <div class="row">

            <div class="col-md-6">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_verbrauch">Energieverbrauch in kWh/100km</span>
                </div>
                <input type="number" step="0.01" name="input_verbrauch" id="input_verbrauch" value="<?= $_POST['input_verbrauch']; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6"></div>

            <div class="col">
            Ergibt: <?php echo isset($täglicherNachladebedarf) ? "<strong>". round($täglicherNachladebedarf, 2, PHP_ROUND_HALF_DOWN) . "</strong>" :  '___';  ?> kW täglichen Nachladebedarf.
            </div>

        </div>

        <br>

        <h2>Ladezeit</h2>

        <div class="row">

            <div class="col-md-6">

            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_ladeleistungfahrzeug">Fahrzeugseitige Ladeleistung in kW</span>
                </div>
                <input type="number" step="0.01" name="input_ladeleistungfahrzeug" id="input_ladeleistungfahrzeug" value="<?= $_POST['input_ladeleistungfahrzeug']; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6">

            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_ladeverlustzeit">Zusatzzeit für Ladeverluste in h</span>
                </div>
                <input type="number" step="0.01" name="input_ladeverlustzeit" id="input_ladeverlustzeit" value="<?= $_POST['input_ladeverlustzeit']; ?>" class="form-control">
            </div>

            </div>

            <div class="col">
            Ergibt: <?php echo isset($täglicherNachladebedarfZeit) ? "<strong>". round($täglicherNachladebedarfZeit, 2, PHP_ROUND_HALF_DOWN) . "</strong>" :  '___';  ?> h zur Nachladung des täglichen Bedarfs.
            </div>

        </div>

        <br>

        <div class="row">

        <div class="col-md-6">

        <div class="input-group my-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="input_ladezeitraum">Verfügbarer Ladezeitraum in h</span>
            </div>
            <input type="number" step="0.01" name="input_ladezeitraum" id="input_ladezeitraum" value="<?= $_POST['input_ladezeitraum']; ?>" class="form-control">
        </div>

        </div>

        <div class="col-md-6">

        <div class="input-group my-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="input_fahrzeugwechselzeit">Zeit zum Fahrzeugwechsel in h</span>
            </div>
            <input type="number" step="0.01" name="input_fahrzeugwechselzeit" id="input_fahrzeugwechselzeit" value="<?= $_POST['input_fahrzeugwechselzeit']; ?>" class="form-control">
        </div>

        </div>

        <div class="col">
        Ergibt: <?php echo isset($anzahlNachladungen) ? "<strong>". round($anzahlNachladungen, 2, PHP_ROUND_HALF_DOWN) . "</strong>" :  '___';  ?> mögliche Nachladungen.
        </div>

        </div>
        <br>
        <h2>Ergebnis</h2>
      
        <div class="row">

            <div class="col-md-6">
        
            <div class="input-group my-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="input_nutzungsfaktor">Nutzungsfaktor (z.B. 0.6 privat)</span>
                </div>
                <input type="number" step="0.01" name="input_nutzungsfaktor" id="input_nutzungsfaktor" value="<?= $_POST['input_nutzungsfaktor']; ?>" class="form-control">
            </div>

            </div>

            <div class="col-md-6"></div>

            <div class="col">
            Ergibt: <?php echo isset($anzahlStellplätzeLastmanagement) ? "<strong>". round($anzahlStellplätzeLastmanagement, 2, PHP_ROUND_HALF_DOWN) . "</strong>" :  '___';  ?> Stellplätze bei dynamischen Lastmanagement.
            </div>

        </div>

        <br>
     
        <button type="submit" class="btn btn-primary">Berechnen</button>
     
    </form>

<!-- PDF Export -->
<?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)):  ?>

    <form action="exportPDF.php" method="post">
        <div>

        <!-- Standartangaben -->
        <input type="hidden" name="input_date" value="<?= $_POST['input_date'] ;?>">
        <input type="hidden" name="input_location" value="<?= $_POST['input_location'] ;?>">
        <input type="hidden" name="input_objectLabel" value="<?= $_POST['input_objectLabel'] ;?>">
        <input type="hidden" name="input_objectLocation" value="<?= $_POST['input_objectLocation'] ;?>">


        <!-- Gebäude & Ladeinfrastruktur -->
        <input type="hidden" name="input_hausanschluss" value="<?= $_POST['input_hausanschluss'] ;?>">
        <input type="hidden" name="input_gebäudelast" value="<?= $_POST['input_gebäudelast'] ;?>">
        <input type="hidden" name="anschlussLeistungLIS" value="<?= $anschlussLeistungLIS ;?>">

        <input type="hidden" name="input_wirkleistungsfaktor" value="<?= $_POST['input_wirkleistungsfaktor'] ;?>">
        <input type="hidden" name="input_ladeleistung" value="<?= $_POST['input_ladeleistung'];?>">
        <input type="hidden" name="anzahlStellplätze" value="<?= $anzahlStellplätze ;?>">


        <!-- Fahrzeug & Fahrverhalten -->
        <input type="hidden" name="input_jahresfahrleistung" value="<?= $_POST['input_jahresfahrleistung'] ;?>">
        <input type="hidden" name="input_anzahltage" value="<?= $_POST['input_anzahltage'] ;?>">
        <input type="hidden" name="täglicheFahrleistung" value="<?= $täglicheFahrleistung ;?>">

        <input type="hidden" name="input_verbrauch" value="<?= $_POST['input_verbrauch'] ;?>">
        <input type="hidden" name="täglicherNachladebedarf" value="<?= $täglicherNachladebedarf ;?>">
       
        <!-- Ladezeit -->
        <input type="hidden" name="input_ladeleistungfahrzeug" value="<?= $_POST['input_ladeleistungfahrzeug'] ;?>">
        <input type="hidden" name="input_ladeverlustzeit" value="<?= $_POST['input_ladeverlustzeit'];?>">
        <input type="hidden" name="täglicherNachladebedarfZeit" value="<?= $täglicherNachladebedarfZeit ;?>">
        <input type="hidden" name="input_ladezeitraum" value="<?= $_POST['input_ladezeitraum'] ;?>">
        <input type="hidden" name="input_fahrzeugwechselzeit" value="<?= $_POST['input_fahrzeugwechselzeit'] ;?>">
        <input type="hidden" name="anzahlNachladungen" value="<?= $anzahlNachladungen ;?>">
        
        <!-- Ergebnis -->
        <input type="hidden" name="input_nutzungsfaktor" value="<?= $_POST['input_nutzungsfaktor'] ;?>">
        <input type="hidden" name="anzahlStellplätzeLastmanagement" value="<?= $anzahlStellplätzeLastmanagement ;?>">
        <br>
        
        <button type="submit" class="btn btn-warning">PDF Export</button>

        </div>
    </form>

<?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
