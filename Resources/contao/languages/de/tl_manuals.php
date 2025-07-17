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
$element = 'Handbuch';

/**
 * Global Operations
 */
$GLOBALS['TL_LANG'][$table]['categories'] = ['Kategorien bearbeiten', 'Kategorien bearbeiten'];


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['title']   = ['Titel', 'Bitte geben Sie den Titel des Handbuchs ein.'];


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['title_legend']   = 'Title';


/**
 * Reference
 *
$GLOBALS['TL_LANG'][$table]['']   = [];


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$table]['new']        = ['Neues ' . $element, 'Neues ' . $element . ' anlegen'];
$GLOBALS['TL_LANG'][$table]['edit']       = [$element . ' bearbeiten', $element . ' mit der ID %s bearbeiten'];
$GLOBALS['TL_LANG'][$table]['copy']       = [$element . ' kopieren', $element . ' mit der ID %s kopieren'];
$GLOBALS['TL_LANG'][$table]['delete']     = [$element . ' löschen', $element . ' mit der ID %s löschen'];
$GLOBALS['TL_LANG'][$table]['show']       = [$element . ' anzeigen', 'Details des ' . $element . 's mit der ID %s anzeigen'];
