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
use NetGroup\UserGuide\Classes\Enums\TableNames;
use NetGroup\UserGuide\Classes\Services\Factories\TemplateFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TemplateHelper
{


    public const string TEMPLATE_NAME_MODULE = 'mod_guide';


    public const string TEMPLATE_NAME_LINK = 'inc_label_tl_guide';


    /**
     * @param TemplateFactory $templateFactory
     * @param ContentHelper   $contentHelper
     * @param LockHelper      $lockHelper
     * @param RequestStack    $requestStack
     */
    public function __construct(
        private readonly TemplateFactory $templateFactory,
        private readonly ContentHelper $contentHelper,
        private readonly LockHelper $lockHelper,
        private readonly RequestStack $requestStack
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
        $template               = $this->templateFactory->createBeackendTemplate(self::TEMPLATE_NAME_MODULE);
        $template->content      = $this->contentHelper->getContent($id);
        $template->manualId     = (int) $request?->get('id');
        $template->guideId      = $id;
        $template->locked       = true;

        if (null !== $table) {
            $template->locked = $this->lockHelper->checkLocked($id, $table);
        }

        return $template;
    }


    /**
     * Gibt das Label für die Datensätze der Tabelle tl_guides zurück.
     *
     * @param array  $row
     * @param string $label
     *
     * @return string
     */
    public function getlabelForTlGuide(array $row, string $label): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request && !empty($row['id'])) {
            $template           = $this->templateFactory->createBeackendTemplate(self::TEMPLATE_NAME_LINK);
            $template->id       = $row['id'];
            $template->url      = $request->getUri();
            $template->label    = $label;
            $template->icon     = !empty($row['icon']) ? $row['icon'] : 'fa-solid fa-circle-info';

            return $template->parse();
        }

        return $label;
    }
}
