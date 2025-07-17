<?php

/**
 * @since       17.07.2025 - 08:33
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
 * Table tl_guides
 */
$GLOBALS['TL_DCA'][$table] = [

    // Config
    'config' => [
        'dataContainer'         => \Contao\DC_Table::class,
        'enableVersioning'      => true,
        'sql'                   => ['keys' => ['id' => 'primary']],
        'ptable'                 => 'tl_manuals'
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'              => 3,
            'fields'            => ['title'],
            'panelLayout'       => 'sort,filter;search,limit',
            'flag'              => 1
        ],
        'label' => [
            'fields'            => ['title', 'title'],
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
        'default'                   => '{title_legend},title,category;{content_legend},content;'
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
        'pid' => [
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
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
        'category' => [
            'label'                 => &$GLOBALS['TL_LANG'][$table]['category'],
            'exclude'               => true,
            'inputType'             => 'select',
            'eval'                  => ['maxlength'=>255, 'tl_class' => 'w50', 'includeBlankOption' => true],
            'sql'                   => "varchar(255) NOT NULL default ''"
        ],
        'content' => [
            'label'                 => &$GLOBALS['TL_LANG'][$table]['content'],
            'exclude'               => true,
            'inputType'             => 'textarea',
            'eval'                  => ['mandatory'=>true, 'rte'=>'ace|markdown', 'tl_class'=>'clr', 'allowHtml'=>true, 'class'=>'monospace', 'helpwizard'=>true],
            'sql'                   => "text NULL"
        ]
	]
];
