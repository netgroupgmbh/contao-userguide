<?php

/**
 * @since       23.07.2025 - 12:58
 *
 * @author      Patrick Froch <info@netgroup.de>
 *
 * @see         http://www.netgroup.de
 *
 * @copyright   NetGroup GmbH 2025
 */

declare(strict_types=1);

namespace NetGroup\UserGuide\Classes\Services\Helper;

use Contao\BackendTemplate;
use Doctrine\DBAL\Exception as DBALException;
use League\CommonMark\Exception\CommonMarkException;
use NetGroup\UserGuide\Classes\Contao\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Factories\TemplateFactory;
use Symfony\Component\HttpFoundation\Request;

class TemplateHelper
{


    public const string TEMPLATE_NAME = 'mod_guide';


    /**
     * @param TemplateFactory $templateFactory
     * @param ContentHelper   $contentHelper
     * @param LockHelper      $lockHelper
     */
    public function __construct(
        private readonly TemplateFactory $templateFactory,
        private readonly ContentHelper $contentHelper,
        private readonly LockHelper $lockHelper
    ) {
    }


    /**
     * Befüllt das Template mit den Daten.
     *
     * @param int             $id
     * @param TableNames|null $table
     * @param Request|null    $request
     *
     * @return BackendTemplate
     *
     * @throws DBALException
     * @throws CommonMarkException
     */
    public function getTemplateWithData(int $id, ?TableNames $table, ?Request $request): BackendTemplate
    {
        $template               = $this->templateFactory->createBeackendTemplate(self::TEMPLATE_NAME);
        $template->content      = $this->contentHelper->getContent($id);
        $template->manualId     = (int) $request?->get('id');
        $template->guideId      = $id;
        $template->locked       = true;

        if (null !== $table) {
            $template->locked = $this->lockHelper->checkLocked($id, $table);
        }

        return $template;
    }
}
