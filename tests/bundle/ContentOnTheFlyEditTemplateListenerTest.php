<?php

declare(strict_types=1);

namespace Masilia\Tests\Bundle\EditorialToolbox;

use Ibexa\AdminUi\View\EditContentOnTheFlySuccessView;
use Masilia\Bundle\EditorialToolbox\EventListener\ContentOnTheFlyEditTemplateListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\HttpKernel;

final class ContentOnTheFlyEditTemplateListenerTest extends TestCase
{
    public function testTheSuccessViewAfterPublishUsesTheTemplateThatTellsTheToolbar(): void
    {
        $view = new EditContentOnTheFlySuccessView('@ibexadesign/ui/on_the_fly/content_edit_response.html.twig');
        $event = new ViewEvent($this->createMock(HttpKernel::class), new Request(), HttpKernelInterface::MAIN_REQUEST, $view);

        (new ContentOnTheFlyEditTemplateListener())->onView($event);

        self::assertSame(ContentOnTheFlyEditTemplateListener::SUCCESS_TEMPLATE, $view->getTemplateIdentifier());
    }
}
