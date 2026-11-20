<?php

declare(strict_types=1);

namespace Masilia\Tests\EditorialToolbox\Lib;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Core\Repository\Values\Content\Location;
use Ibexa\Contracts\Core\Repository\Values\Content\LocationList;
use Masilia\EditorialToolbox\Block\BlocksFolderService;
use PHPUnit\Framework\TestCase;

final class BlocksFolderServiceTest extends TestCase
{
    public function testAnEmptySuffixTurnsItOff(): void
    {
        $this->assertSame(10, (new BlocksFolderService($this->createStub(Repository::class)))->targetFor(10, 'eng-GB', '', 'folder'));
    }

    public function testThePageItselfWhenItIsNotAContainer(): void
    {
        $service = new BlocksFolderService($this->repository(container: false));

        $this->assertSame(10, $service->targetFor(10, 'eng-GB', 'Blocks', 'folder'));
    }

    public function testTheFolderOfThePageIsFoundByRemoteId(): void
    {
        $contents = $this->createStub(ContentService::class);
        $contents->method('loadContentInfoByRemoteId')->willReturnCallback(function (string $remoteId): ContentInfo {
            $this->assertSame('about_page_blocks', $remoteId);

            return new ContentInfo(['id' => 5, 'mainLocationId' => 55]);
        });

        $this->assertSame(55, (new BlocksFolderService($this->repository(true, $contents)))->targetFor(10, 'eng-GB', 'Blocks', 'folder'));
    }

    public function testWhenTheFolderCannotBeMadeTheBlocksGoUnderThePage(): void
    {
        $contents = $this->createStub(ContentService::class);
        $contents->method('loadContentInfoByRemoteId')->willThrowException(new NotFoundException('content', 'x'));
        $contents->method('newContentCreateStruct')->willThrowException(new \RuntimeException('no right to create a folder'));

        $this->assertSame(10, (new BlocksFolderService($this->repository(true, $contents)))->targetFor(10, 'eng-GB', 'Blocks', 'folder'));
    }

    public function testTheNameStartsWithThePage(): void
    {
        $this->assertSame('About Blocks', BlocksFolderService::name('About', 'Blocks'));
        $this->assertTrue(BlocksFolderService::nameMatches('Home Blocks', 'Blocks'));
        $this->assertFalse(BlocksFolderService::nameMatches('Media', 'Blocks'));
    }

    private function repository(bool $container, ?ContentService $contents = null): Repository
    {
        $page = new Location(['id' => 10, 'contentInfo' => new ContentInfo(['id' => 1, 'remoteId' => 'about_page', 'name' => 'About', 'contentTypeId' => 3])]);
        $locations = $this->createStub(LocationService::class);
        $locations->method('loadLocation')->willReturn($page);
        $locations->method('loadLocationChildren')->willReturn(new LocationList(['locations' => [], 'totalCount' => 0]));
        $types = $this->createStub(ContentTypeService::class);
        $types->method('loadContentType')->willReturn(new \Ibexa\Core\Repository\Values\ContentType\ContentType(['identifier' => 'page', 'isContainer' => $container]));
        $repository = $this->createStub(Repository::class);
        $repository->method('getLocationService')->willReturn($locations);
        $repository->method('getContentTypeService')->willReturn($types);
        $repository->method('getContentService')->willReturn($contents ?? $this->createStub(ContentService::class));

        return $repository;
    }
}
