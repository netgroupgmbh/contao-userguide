<?php

/**
 * @since       29.07.2025 - 14:13
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Tests\Contao\Callbacks;

use Contao\DataContainer;
use NetGroup\UserGuide\Classes\Contao\Callbacks\TlGuides;
use NetGroup\UserGuide\Classes\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Helper\AssetHelper;
use NetGroup\UserGuide\Classes\Services\Helper\FontAewsomeHelper;
use NetGroup\UserGuide\Classes\Services\Helper\LockHelper;
use NetGroup\UserGuide\Classes\Services\Helper\QueryHelper;
use NetGroup\UserGuide\Classes\Services\Helper\TemplateHelper;
use NetGroup\UserGuide\NetGroupTestCase;

class TlGuidesTest extends NetGroupTestCase
{


    /**
     * @var FontAewsomeHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private FontAewsomeHelper $fontAwesomeHelperMock;


    /**
     * @var AssetHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private AssetHelper $assetHelperMock;


    /**
     * @var QueryHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private QueryHelper $queryHelperMock;


    /**
     * @var LockHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private LockHelper $lockHelperMock;


    /**
     * @var TemplateHelper&\PHPUnit\Framework\MockObject\MockObject
     */
    private TemplateHelper $templateHelperMock;


    /**
     * @var DataContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    private DataContainer $dc;


    /**
     * @var TlGuides
     */
    private TlGuides $tlGuides;


    protected function setUp(): void
    {
        $this->fontAwesomeHelperMock	= $this->createMock(FontAewsomeHelper::class);
        $this->assetHelperMock			= $this->createMock(AssetHelper::class);
        $this->queryHelperMock			= $this->createMock(QueryHelper::class);
        $this->lockHelperMock			= $this->createMock(LockHelper::class);
        $this->templateHelperMock		= $this->createMock(TemplateHelper::class);
        $this->dc						= $this->createMock(DataContainer::class);

        $this->tlGuides = new TlGuides(
            $this->fontAwesomeHelperMock,
            $this->assetHelperMock,
            $this->queryHelperMock,
            $this->lockHelperMock,
            $this->templateHelperMock
        );
    }


    /**
     * Prüft, ob keine Optionen zurückgegeben werden, wenn die Daten leer sind.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadCategoriesReturnsEmptyArrayWhenNoRows(): void
    {
        $pid = 12;

        $this->dc->expects($this->once())
                 ->method('__get')
                 ->with('currentPid')
                 ->willReturn($pid);

        $this->queryHelperMock
            ->method('loadCategoriesFromPid')
            ->with($pid)
            ->willReturn([]);

        $result = $this->tlGuides->loadCategories($this->dc);

        $this->assertSame([], $result);
    }


    /**
     * Prüft, ob Kategorien korrekt aus den Zeilen extrahiert werden.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testLoadCategoriesReturnsValidOptions(): void
    {
        $pid = 12;

        $this->dc->expects($this->once())
                 ->method('__get')
                 ->with('currentPid')
                 ->willReturn($pid);

        $this->queryHelperMock
            ->method('loadCategoriesFromPid')
            ->with($pid)
            ->willReturn([
                ['id' => 1, 'title' => 'Kategorie A'],
                ['id' => 2, 'title' => 'Kategorie B'],
                ['id' => null, 'title' => 'Ignoriert'],
                ['id' => 3, 'title' => ''],
            ]);

        $result = $this->tlGuides->loadCategories($this->dc);

        $this->assertSame([
            1 => 'Kategorie A',
            2 => 'Kategorie B',
        ], $result);
    }


    /**
     * Prüft, ob generateFontAwesomeOptions das Ergebnis aus dem Helper zurückgibt.
     */
    public function testGenerateFontAwesomeOptionsReturnsHelperResult(): void
    {
        $this->fontAwesomeHelperMock
            ->method('createOpteions')
            ->willReturn(['icon1' => 'Icon 1']);

        $result = $this->tlGuides->generateFontAwesomeOptions(null);

        $this->assertSame(['icon1' => 'Icon 1'], $result);
    }


    /**
     * Prüft, ob createLabel das Ergebnis aus TemplateHelper zurückgibt.
     */
    public function testCreateLabelReturnsTemplateHelperValue(): void
    {
        $row    = ['id' => 5];
        $label  = 'Test';

        $this->templateHelperMock
            ->method('getlabelForTlGuide')
            ->with($row, $label)
            ->willReturn('HTML Label');

        $result = $this->tlGuides->createLabel($row, $label, $this->dc);

        $this->assertSame('HTML Label', $result);
    }


    /**
     * Prüft, ob addAssets die CSS- und JavaScript-Methoden aufruft.
     */
    public function testAddAssetsIncludesCssAndJs(): void
    {
        $this->assetHelperMock
            ->expects($this->once())
            ->method('incldueCss');

        $this->assetHelperMock
            ->expects($this->once())
            ->method('includeJavaScript');

        $this->tlGuides->addAssets(null);
    }


    /**
     * Prüft, ob checkPermissions globale Operationen entfernt, wenn Lock aktiv ist.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckPermissionsRemovesGlobalsWhenLocked(): void
    {
        $GLOBALS['TL_DCA'] = [
            'tl_guides' => [
                'list' => [
                    'global_operations' => ['editAll' => []],
                    'operations'        => ['edit' => []],
                ],
                'config' => [
                    'closed'       => false,
                    'notDeletable' => false,
                    'notEditable'  => false,
                    'notCopyable'  => false,
                ],
            ],
        ];

        $pid    = 12;
        $table  = TableNames::tl_guides->name;

        $this->dc->expects($this->exactly(2))
                 ->method('__get')
                 ->with(...$this->consecutiveParams(
                     ['table'],
                     ['currentPid']
                 ))
                 ->willReturnOnConsecutiveCalls(
                     $table,
                     $pid
                 );

        $this->lockHelperMock
            ->method('checkLocked')
            ->with($pid, TableNames::tl_manuals)
            ->willReturn(true);

        $this->tlGuides->checkPermissions($this->dc);

        $this->assertSame([], $GLOBALS['TL_DCA']['tl_guides']['list']['global_operations']);
        $this->assertSame([], $GLOBALS['TL_DCA']['tl_guides']['list']['operations']);
        $this->assertTrue($GLOBALS['TL_DCA']['tl_guides']['config']['closed']);
        $this->assertTrue($GLOBALS['TL_DCA']['tl_guides']['config']['notDeletable']);
        $this->assertTrue($GLOBALS['TL_DCA']['tl_guides']['config']['notEditable']);
        $this->assertTrue($GLOBALS['TL_DCA']['tl_guides']['config']['notCopyable']);
    }


    /**
     * Prüft, ob checkPermissions nichts verändert, wenn kein Lock vorliegt.
     *
     * @return void
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCheckPermissionsDoesNothingWhenNotLocked(): void
    {
        $GLOBALS['TL_DCA'] = [
            'tl_guides' => [
                'list' => [
                    'global_operations' => ['editAll' => []],
                    'operations'        => ['edit' => []],
                ],
                'config' => [
                    'closed'       => false,
                    'notDeletable' => false,
                    'notEditable'  => false,
                    'notCopyable'  => false,
                ],
            ],
        ];



        $pid    = 12;
        $table  = TableNames::tl_guides->name;

        $this->dc->expects($this->exactly(2))
                 ->method('__get')
                 ->with(...$this->consecutiveParams(
                     ['table'],
                     ['currentPid']
                 ))
                 ->willReturnOnConsecutiveCalls(
                     $table,
                     $pid
                 );

        $this->lockHelperMock
            ->method('checkLocked')
            ->with($pid, TableNames::tl_manuals)
            ->willReturn(false);

        $this->tlGuides->checkPermissions($this->dc);

        $this->assertFalse($GLOBALS['TL_DCA']['tl_guides']['config']['closed']);
        $this->assertFalse($GLOBALS['TL_DCA']['tl_guides']['config']['notDeletable']);
        $this->assertFalse($GLOBALS['TL_DCA']['tl_guides']['config']['notEditable']);
        $this->assertFalse($GLOBALS['TL_DCA']['tl_guides']['config']['notCopyable']);
    }
}
