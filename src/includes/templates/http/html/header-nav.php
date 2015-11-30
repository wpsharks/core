<?php
/*
 * Merge w/ defaults.
 */
$¤defaults = [
    'brand' => [
        'name' => $this->App->Config->brand['name'],
        'logo' => $this->Utils->Url->toCur($this->App->Config->brand['logo']),
    ],
];
extract(array_replace_recursive($¤defaults, $¤vars));
/*
 * Output template contents.
 */ ?>
 <!-- default header nav -->
