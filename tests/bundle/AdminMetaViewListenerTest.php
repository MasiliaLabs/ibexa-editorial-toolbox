<?php

declare(strict_types=1);

namespace Masilia\Tests\Bundle\EditorialToolbox;

use Ibexa\Contracts\Core\Repository\PermissionResolver;
use Ibexa\Core\MVC\Symfony\Event\PreContentViewEvent;
use Ibexa\Core\MVC\Symfony\View\ContentView;
use Masilia\Bundle\EditorialToolbox\EventListener\AdminMetaViewListener;
use Masilia\Contracts\EditorialToolbox\Meta\AdminMetaBuilderInterface;
use PHPUnit\Framework\TestCase;

final class AdminMetaViewListenerTest extends TestCase
{
    public function testAViewWithNoContentIsLeftAloneInsteadOfFailing(): void
    {
        // What the in-context editor renders after a publish: a content view that has no content.
        $permissions = $this->createStub(PermissionResolver::class);
        $permissions->method('hasAccess')->willReturn(true);
        $builder = $this->createMock(AdminMetaBuilderInterface::class);
        $builder->expects(self::never())->method('build');
        $view = new ContentView();

        (new AdminMetaViewListener($permissions, $builder))->onPreContentView(new PreContentViewEvent($view));

        self::assertArrayNotHasKey('admin_meta', $view->getParameters());
    }
}
