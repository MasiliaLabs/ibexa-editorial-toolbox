<?php

declare(strict_types=1);

namespace Masilia\Bundle\EditorialToolbox\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessAware\Configuration
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('masilia_editorial_toolbox');
        $rootNode = $treeBuilder->getRootNode();

        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->scalarNode('blocks_field')
                ->info('Identifier of the RelationList field that holds the ordered page blocks.')
            ->end()
            ->scalarNode('blocks_folder_suffix')
                ->info('The blocks of a page are made in a folder under it, named after it: "<page name> <suffix>" (the default suffix is "Blocks"). An empty value turns it off: the blocks are made under the page.')
                ->defaultValue('Blocks')
            ->end()
            ->scalarNode('folder_type')
                ->info('Identifier of the content type of that folder (field name).')
                ->defaultValue('folder')
            ->end()
            ->scalarNode('admin_siteaccess')
                ->info('Siteaccess used to build back-office (admin) URLs from the front-end site.')
            ->end();

        return $treeBuilder;
    }
}
