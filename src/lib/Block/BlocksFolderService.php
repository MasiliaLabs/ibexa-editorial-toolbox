<?php

declare(strict_types=1);

namespace Masilia\EditorialToolbox\Block;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\Repository;
use Throwable;

/**
 * Where the blocks of a page are made: in a folder that is a child of that page, named after it ("About Blocks"), found by the
 * remote id of the page and "_blocks" (so renaming the page changes nothing) and made when it is missing. Blocks that exist
 * elsewhere are not moved: pages made before keep theirs where they are. Compatible with Ibexa 4.6 and 5.0.
 *
 * The same rules as masilia/ibexa-blocks (Masilia\IbexaBlocks\Content\BlocksFolder), written again here so that this package
 * needs none of the others.
 */
class BlocksFolderService
{
    public const REMOTE_ID_SUFFIX = '_blocks';

    public function __construct(
        private readonly Repository $repository,
    ) {
    }

    /**
     * The location the blocks of a page go under: the folder of its blocks, or the page itself when it cannot have one (it is not a
     * container, the editor may not create a folder, or the feature is off with an empty suffix).
     */
    public function targetFor(int $pageLocationId, string $languageCode, string $suffix, string $folderType): int
    {
        if (trim($suffix) === '') {
            return $pageLocationId;
        }

        try {
            return $this->ensure($pageLocationId, $languageCode, trim($suffix), $folderType) ?? $pageLocationId;
        } catch (Throwable) {
            return $pageLocationId;
        }
    }

    private function ensure(int $pageLocationId, string $languageCode, string $suffix, string $folderType): ?int
    {
        $locations = $this->repository->getLocationService();
        $contents = $this->repository->getContentService();
        $page = $locations->loadLocation($pageLocationId);
        $pageInfo = $page->contentInfo;
        $types = $this->repository->getContentTypeService();
        $pageType = $types->loadContentType($pageInfo->contentTypeId);
        if (!$pageType->isContainer()) {
            return null;
        }

        $remoteId = $pageInfo->remoteId . self::REMOTE_ID_SUFFIX;
        try {
            $existing = $contents->loadContentInfoByRemoteId($remoteId)->mainLocationId;
            if ($existing !== null) {
                return (int) $existing;
            }
        } catch (NotFoundException) {
        }

        // A folder somebody made by hand, named like one.
        foreach ($locations->loadLocationChildren($page, 0, 100)->locations as $child) {
            if ($types->loadContentType($child->contentInfo->contentTypeId)->identifier === $folderType && self::nameMatches((string) $child->contentInfo->name, $suffix)) {
                return (int) $child->id;
            }
        }

        $struct = $contents->newContentCreateStruct($types->loadContentTypeByIdentifier($folderType), $languageCode);
        $struct->remoteId = $remoteId;
        $struct->setField('name', self::name((string) $pageInfo->name, $suffix), $languageCode);
        $published = $contents->publishVersion($contents->createContent($struct, [$locations->newLocationCreateStruct($page->id)])->getVersionInfo());

        return $published->contentInfo->mainLocationId === null ? null : (int) $published->contentInfo->mainLocationId;
    }

    public static function name(string $pageName, string $suffix): string
    {
        $pageName = trim($pageName);

        return $pageName === '' ? $suffix : $pageName . ' ' . $suffix;
    }

    public static function nameMatches(string $folderName, string $suffix): bool
    {
        $folderName = trim($folderName);

        return strcasecmp($folderName, $suffix) === 0 || str_ends_with(strtolower($folderName), ' ' . strtolower($suffix));
    }
}
