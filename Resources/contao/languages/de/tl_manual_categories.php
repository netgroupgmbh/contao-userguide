<?php

/**
 * @since       17.07.2025 - 09:47
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

/**
 * Set Tablename
 */
$table = 'tl_manual_categories';


/**
 * Set Elementname
 */
$element = 'Katrgorie';


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['title'] = ['Titel', 'Bitte geben Sie den Titel ein'];


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['title_legend'] = 'Titel';


/**
 * Reference
 *
$GLOBALS['TL_LANG'][$table][''] = [];


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$table]['new']        = ['Neue ' . $element, 'Neue ' . $element . ' anlegen'];
$GLOBALS['TL_LANG'][$table]['edit']       = [$element . ' bearbeiten', $element . ' mit der ID %s bearbeiten'];
$GLOBALS['TL_LANG'][$table]['copy']       = [$element . ' kopieren', $element . ' mit der ID %s kopieren'];
$GLOBALS['TL_LANG'][$table]['delete']     = [$element . ' löschen', $element . ' mit der ID %s löschen'];
$GLOBALS['TL_LANG'][$table]['show']       = [$element . ' anzeigen', 'Details der ' . $element . ' mit der ID %s anzeigen'];
