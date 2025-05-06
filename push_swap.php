<?php

if (PHP_SAPI !== 'cli') {
    exit;
}


$args = array_slice($argv, 1);
if (empty($args)) {
    exit;
}


$la = array_map('intval', $args);
$lb = [];
$operations = [];



function est_trie($arr) {
    for ($i = 0; $i < count($arr) - 1; $i++) {
        if ($arr[$i] > $arr[$i + 1]) {
            return false;
        }
    }
    return true;
}


if (est_trie($la)) {
    echo "\n";
    exit;
}


function sa(&$la, &$operations) {
    if (count($la) >= 2) {
        [$la[0], $la[1]] = [$la[1], $la[0]];
        $operations[] = 'sa';
    }
}


function pb(&$la, &$lb, &$operations) {
    if (!empty($la)) {
        array_unshift($lb, array_shift($la));
        $operations[] = 'pb';
    }
}


function pa(&$la, &$lb, &$operations) {
    if (!empty($lb)) {
        array_unshift($la, array_shift($lb));
        $operations[] = 'pa';
    }
}


function ra(&$la, &$operations) {
    if (count($la) >= 2) {
        array_push($la, array_shift($la));
        $operations[] = 'ra';
    }
}


function rra(&$la, &$operations) {
    if (count($la) >= 2) {
        array_unshift($la, array_pop($la));
        $operations[] = 'rra';
    }
}


function trier_trois(&$la, &$operations) {
    if (est_trie($la)) return;


    $max = max($la);
    $min = min($la);

    if ($la[0] == $max) {
        ra($la, $operations);
        trier_trois($la, $operations);
    } elseif ($la[1] == $max) {
        rra($la, $operations);
        trier_trois($la, $operations);
    } else {
        if ($la[0] > $la[1]) {
            sa($la, $operations);
        }
    }
}


$taille = count($la);
if ($taille == 2) {
    if ($la[0] > $la[1]) {
        sa($la, $operations);
    }
} elseif ($taille == 3) {
    trier_trois($la, $operations);
} else {

    while (count($la) > 3) {
        pb($la, $lb, $operations);
    }
    trier_trois($la, $operations);


    while (!empty($lb)) {
        $element = $lb[0];
        $position = 0;
        $taille_la = count($la);


        while ($position < $taille_la && $la[$position] < $element) {
            $position++;
        }


        $rotations = $position;
        $rotations_inverse = $taille_la - $position;

        if ($rotations <= $rotations_inverse) {
            for ($i = 0; $i < $rotations; $i++) {
                ra($la, $operations);
            }
        } else {
            for ($i = 0; $i < $rotations_inverse; $i++) {
                rra($la, $operations);
            }
        }

        pa($la, $lb, $operations);
    }


    while ($la[0] != min($la)) {
        ra($la, $operations);
    }
}


echo implode(' ', $operations) . "\n";