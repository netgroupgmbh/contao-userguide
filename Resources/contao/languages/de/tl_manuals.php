<?php

/**
 * @since       17.07.2025 - 08:32
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
$table = 'tl_manuals';


/**
 * Set Elementname
 */
$element = 'Anleitung';

/**
 * Global Operations
 */
$GLOBALS['TL_LANG'][$table]['categories'] = ['Kategorien bearbeiten', 'Kategorien bearbeiten'];


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['title']    = ['Titel', 'Bitte geben Sie den Titel der Anleitung ein.'];
$GLOBALS['TL_LANG'][$table]['locked']   = ['Gesperrt', 'Ist der Haken gesetzt, kann die Anleitung nicht mehr bearbeitet werden.'];


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['title_legend'] = 'Title';


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$table]['new']        = ['Neue ' . $element, 'Neue ' . $element . ' anlegen'];
$GLOBALS['TL_LANG'][$table]['edit']       = [$element . ' bearbeiten', $element . ' mit der ID %s bearbeiten'];
$GLOBALS['TL_LANG'][$table]['copy']       = [$element . ' kopieren', $element . ' mit der ID %s kopieren'];
$GLOBALS['TL_LANG'][$table]['delete']     = [$element . ' löschen', $element . ' mit der ID %s löschen'];
$GLOBALS['TL_LANG'][$table]['show']       = [$element . ' anzeigen', 'Details der ' . $element . ' mit der ID %s anzeigen'];
