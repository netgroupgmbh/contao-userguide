<?php

/**
 * @since       17.07.2025 - 08:47
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
$table = 'tl_guides';


/**
 * Set Elementname
 */
$element = 'Anleitung';


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['title']    = ['Titel', 'Bitte geben Sie den Titel der Anleitung ein.'];
$GLOBALS['TL_LANG'][$table]['category'] = ['Kategorie', 'Bitte wählen Sie die Kategorie der Anleitung aus.'];
$GLOBALS['TL_LANG'][$table]['content']  = ['Text', 'Bitte geben Sie den Text der Anleitung ein.'];


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['title_legend']     = 'Titel der Anleitung';
$GLOBALS['TL_LANG'][$table]['content_legend']   = 'Text der Anleitung';


/**
 * Reference
 *
$GLOBALS['TL_LANG'][$table]['']   = [];


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$table]['new']        = ['Neue ' . $element, 'Neue ' . $element . ' anlegen'];
$GLOBALS['TL_LANG'][$table]['edit']       = [$element . ' bearbeiten', $element . ' mit der ID %s bearbeiten'];
$GLOBALS['TL_LANG'][$table]['copy']       = [$element . ' kopieren', $element . ' mit der ID %s kopieren'];
$GLOBALS['TL_LANG'][$table]['delete']     = [$element . ' löschen', $element . ' mit der ID %s löschen'];
$GLOBALS['TL_LANG'][$table]['show']       = [$element . ' anzeigen', 'Details der ' . $element . ' mit der ID %s anzeigen'];
