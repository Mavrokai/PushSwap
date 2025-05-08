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

function sb(&$lb, &$operations) {
    if (count($lb) >= 2) {
        [$lb[0], $lb[1]] = [$lb[1], $lb[0]];
        $operations[] = 'sb';
    }
}

function sc(&$la, &$lb, &$operations) {
    if (count($la) >= 2 && count($lb) >= 2) {
        [$la[0], $la[1]] = [$la[1], $la[0]];
        [$lb[0], $lb[1]] = [$lb[1], $lb[0]];
        $operations[] = 'sc';
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

function rb(&$lb, &$operations) {
    if (count($lb) >= 2) {
        array_push($lb, array_shift($lb));
        $operations[] = 'rb';
    }
}

function rr(&$la, &$lb, &$operations) {
    if (count($la) >= 2 && count($lb) >= 2) {
        array_push($la, array_shift($la));
        array_push($lb, array_shift($lb));
        $operations[] = 'rr';
    }
}

function rra(&$la, &$operations) {
    if (count($la) >= 2) {
        array_unshift($la, array_pop($la));
        $operations[] = 'rra';
    }
}

function rrb(&$lb, &$operations) {
    if (count($lb) >= 2) {
        array_unshift($lb, array_pop($lb));
        $operations[] = 'rrb';
    }
}

function rrr(&$la, &$lb, &$operations) {
    if (count($la) >= 2 && count($lb) >= 2) {
        array_unshift($la, array_pop($la));
        array_unshift($lb, array_pop($lb));
        $operations[] = 'rrr';
    }
}

function trier_trois(&$la, &$operations) {
    if (est_trie($la)) return;

    $max = max($la);
    $min = min($la);


    if ($la[0] == $max) {
        ra($la, $operations);
        if ($la[0] > $la[1]) {
            sa($la, $operations);
        }

    } elseif ($la[1] == $max) {
        if ($la[0] > $la[2]) {
            rra($la, $operations);
        } else {
            sa($la, $operations);
            ra($la, $operations);
        }
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


    if ($taille <= 5) {

        $min = min($la);
        $min_pos = array_search($min, $la);
        
        

        if ($min_pos > $taille / 2) {
            for ($i = 0; $i < $taille - $min_pos; $i++) {
                rra($la, $operations);
            }
        } else {
            for ($i = 0; $i < $min_pos; $i++) {
                ra($la, $operations);
            }
        }
        

        for ($i = 0; $i < $taille - 1; $i++) {
            pb($la, $lb, $operations);
        }

        

        while (!empty($lb)) {
            $element = $lb[0];
            $position = 0;
            $taille_la = count($la);
            


            while ($position < $taille_la && $la[$position] < $element) {
                $position++;
            }
            

            if ($position <= $taille_la / 2) {
                for ($i = 0; $i < $position; $i++) {
                    ra($la, $operations);
                }
            } else {
                for ($i = 0; $i < $taille_la - $position; $i++) {
                    rra($la, $operations);
                }
            }
            
            pa($la, $lb, $operations);
        }
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
    }
    




    $min_pos = array_search(min($la), $la);
    $taille_la = count($la);
    if ($min_pos > $taille_la / 2) {
        for ($i = 0; $i < $taille_la - $min_pos; $i++) {
            rra($la, $operations);
        }
    } else {
        for ($i = 0; $i < $min_pos; $i++) {
            ra($la, $operations);
        }
    }
}

echo implode(' ', $operations) . "\n";
