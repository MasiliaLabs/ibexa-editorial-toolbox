<?php

declare(strict_types=1);

namespace Masilia\Bundle\EditorialToolbox\EventListener;

use Ibexa\Contracts\Core\Repository\PermissionResolver;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Core\MVC\Symfony\Event\PreContentViewEvent;
use Ibexa\Core\MVC\Symfony\MVCEvents;
use Ibexa\Core\MVC\Symfony\View\ContentView;
use Masilia\Contracts\EditorialToolbox\Meta\AdminMetaBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Exposes the `admin_meta` template parameter to users who are allowed to edit content.
 */
class AdminMetaViewListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly PermissionResolver $permissionResolver,
        private readonly AdminMetaBuilderInterface $adminMetaBuilder,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [MVCEvents::PRE_CONTENT_VIEW => 'onPreContentView'];
    }

    public function onPreContentView(PreContentViewEvent $event): void
    {
        $view = $event->getContentView();
        if (!$view instanceof ContentView) {
            return;
        }

        if ($this->permissionResolver->hasAccess('content', 'edit') === false) {
            return;
        }

        // The view the in-context editor renders after a publish has no content: nothing to describe, and nothing to fail on.
        $content = $view->getContent();
        if (!$content instanceof Content) {
            return;
        }

        $view->addParameters(['admin_meta' => $this->adminMetaBuilder->build($content)]);
    }
}
