<?php
$pls = "";
$counter = 0;
foreach ($data['plastics'] as $plastic) {
    if (count($data['plastics']) - 1 == $counter) {
        $pls = $pls . '"' . $plastic['time'] . '"';
    } else {
        $counter++;
        $pls = $pls . '"' . $plastic['time'] . '", ';
    }
};
$plsQuantity = "";
$counter = 0;
foreach ($data['plastics'] as $plastic) {
    if (count($data['plastics']) - 1 == $counter) {
        $plsQuantity = $plsQuantity . $plastic['quantity'];
    } else {
        $counter++;
        $plsQuantity = $plsQuantity .  $plastic['quantity'] . ',';
    }
}


$pap = "";
$counter = 0;
foreach ($data['papers'] as $paper) {
    if (count($data['papers']) - 1 == $counter) {
        $pap = $pap . '"' . $paper['time'] . '"';
    } else {
        $counter++;
        $pap = $pap . '"' . $paper['time'] . '", ';
    }
};
$papQuantity = "";
$counter = 0;
foreach ($data['papers'] as $paper) {
    if (count($data['papers']) - 1 == $counter) {
        $papQuantity = $papQuantity . $paper['quantity'];
    } else {
        $counter++;
        $papQuantity = $papQuantity .  $paper['quantity'] . ',';
    }
}

$gls = "";
$counter = 0;
foreach ($data['glasses'] as $glass) {
    if (count($data['glasses']) - 1 == $counter) {
        $gls = $gls . '"' . $glass['time'] . '"';
    } else {
        $counter++;
        $gls = $gls . '"' . $glass['time'] . '", ';
    }
};
$glsQuantity = "";
$counter = 0;
foreach ($data['glasses'] as $glass) {
    if (count($data['glasses']) - 1 == $counter) {
        $glsQuantity = $glsQuantity . $glass['quantity'];
    } else {
        $counter++;
        $glsQuantity = $glsQuantity .  $glass['quantity'] . ',';
    }
}

$mtl = "";
$counter = 0;
foreach ($data['metals'] as $metal) {
    if (count($data['metals']) - 1 == $counter) {
        $mtl = $mtl . '"' . $metal['time'] . '"';
    } else {
        $counter++;
        $mtl = $mtl . '"' . $metal['time'] . '", ';
    }
};
$mtlQuantity = "";
$counter = 0;
foreach ($data['metals'] as $metal) {
    if (count($data['metals']) - 1 == $counter) {
        $mtlQuantity = $mtlQuantity . $metal['quantity'];
    } else {
        $counter++;
        $mtlQuantity = $mtlQuantity .  $metal['quantity'] . ',';
    }
}
?>