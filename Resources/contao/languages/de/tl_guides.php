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
$GLOBALS['TL_LANG'][$table]['title']    = ['Titel', 'Bitte geben Sie den Titel des Abschnitts ein.'];
$GLOBALS['TL_LANG'][$table]['category'] = ['Kategorie', 'Bitte wählen Sie die Kategorie des Abschnitts aus.'];
$GLOBALS['TL_LANG'][$table]['icon']     = ['Icon', 'Bitte wählen Sie das Icon für den Abschnitt aus. (Icons powered by fontawesome.com)'];
$GLOBALS['TL_LANG'][$table]['content']  = ['Text', 'Bitte geben Sie den Text des Abschnitts ein. Sie können im Text <a href="https://www.markdownguide.org/basic-syntax/" style="text-decoration: underline;" target="_blank">Markdown</a> und <a href="https://docs.contao.org/manual/en/article-management/insert-tags/" style="text-decoration: underline;" target="_blank">InsertTags</a> verwenden.'];
$GLOBALS['TL_LANG'][$table]['locked']   = ['Gesperrt', 'Ist der Haken gesetzt, kann der Abschnitt nicht mehr bearbeitet werden.'];


/**
 * Legends
 */
$GLOBALS['TL_LANG'][$table]['title_legend']     = 'Einstellungen des Abschnitts';
$GLOBALS['TL_LANG'][$table]['content_legend']   = 'Text des Abschnitts';


/**
 * Buttons
 */
$GLOBALS['TL_LANG'][$table]['new']        = ['Neue ' . $element, 'Neue ' . $element . ' anlegen'];
$GLOBALS['TL_LANG'][$table]['edit']       = [$element . ' bearbeiten', $element . ' mit der ID %s bearbeiten'];
$GLOBALS['TL_LANG'][$table]['copy']       = [$element . ' kopieren', $element . ' mit der ID %s kopieren'];
$GLOBALS['TL_LANG'][$table]['delete']     = [$element . ' löschen', $element . ' mit der ID %s löschen'];
$GLOBALS['TL_LANG'][$table]['show']       = [$element . ' anzeigen', 'Details der ' . $element . ' mit der ID %s anzeigen'];
