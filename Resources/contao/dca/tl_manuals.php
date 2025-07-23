<?php

/**
 * @since       17.07.2025 - 08:30
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
 * Table tl_manuals
 */
$GLOBALS['TL_DCA'][$table] = [

    // Config
    'config' => [
        'dataContainer'         => \Contao\DC_Table::class,
        'enableVersioning'      => true,
        'sql'                   => ['keys' => ['id' => 'primary']],
        'ctable'                => ['tl_guides', 'tl_manual_categories'],
        'switchToEdit'          => true,
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'              => 1,
            'fields'            => ['title'],
            'panelLayout'       => 'sort,filter;search,limit',
            'flag'              => 1
        ],
        'label' => [
            'fields'            => ['title'],
            'format'            => '%s'
        ],
        'global_operations' => [
            'all' => [
                'label'             => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'              => 'act=select',
				'class'             => 'header_edit_all',
				'attributes'        => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			]
		],
		'operations' => [
            'edit' => [
                'label'             => &$GLOBALS['TL_LANG'][$table]['edit'],
                'href'              => 'table=tl_guides',
                'icon'              => 'children.svg'
            ],
            'editheader' => [
                'label'             => &$GLOBALS['TL_LANG'][$table]['editheader'],
                'href'              => 'act=edit',
                'icon'              => 'edit.svg'
            ],
            'copy' => [
                'label'             => &$GLOBALS['TL_LANG'][$table]['copy'],
                'href'              => 'act=copy',
                'icon'              => 'copy.svg'
            ],
            'delete' => [
                'label'             => &$GLOBALS['TL_LANG'][$table]['delete'],
                'href'              => 'act=delete',
                'icon'              => 'delete.svg',
                'attributes'        => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show' => [
                'label'             => &$GLOBALS['TL_LANG'][$table]['show'],
                'href'              => 'act=show',
                'icon'              => 'show.svg'
            ],
            'categories' => [
                'label'             => &$GLOBALS['TL_LANG'][$table]['categories'],
                'href'              => 'table=tl_manual_categories',
                'icon'              => 'settings.svg'
            ]
        ]
    ],

	// Select
	'select' => [
        'buttons_callback'          => []
    ],

	// Edit
	'edit' => [
        'buttons_callback'          => []
    ],

	// Palettes
	'palettes' => [
        '__selector__'              => [''],
        'default'                   => '{title_legend},title,locked;'
    ],

	// Subpalettes
	'subpalettes' => [
        ''                          => ''
    ],

	// Fields
	'fields' => [
        'id' => [
            'sql'                   => 'int(10) unsigned NOT NULL auto_increment'
        ],
        'tstamp' => [
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
        ],
        'title' => [
            'label'                 => &$GLOBALS['TL_LANG'][$table]['title'],
            'exclude'               => true,
            'inputType'             => 'text',
            'eval'                  => ['mandatory'=>true, 'maxlength'=>255, 'tl_class' => 'w50'],
            'sql'                   => "varchar(255) NOT NULL default ''"
        ],
        'locked' => [
            'label'                 => &$GLOBALS['TL_LANG'][$table]['locked'],
            'exclude'               => true,
            'search'                => true,
            'inputType'             => 'checkbox',
            'eval'                  => ['tl_class'=>'w50 m12'],
            'sql'                   => "char(1) NOT NULL default ''"
        ]
	]
];
