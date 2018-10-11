<?php
/**
 * Build out menu, flush DB at first
 */

use LCI\MODX\MenuBuilder\MenuBuilder;

$placeholder = $modx->getOption('placeholder', $scriptProperties, null);

$menuBuilder = new MenuBuilder($modx);

if ( (bool)$modx->getOption('debug', $scriptProperties, false) ) {
    $menuBuilder->setDebug();
}

$branch_parents = $modx->getParentIds($modx->resource->get('id'));
// @TODO set via context_key:
$site_start = (integer) $modx->getOption('site_start', null, 1);
/**
 * Now get user options:
 */
$menuBuilder
    ->setOption('startId', (int)$modx->getOption('startId', $scriptProperties, 0))
    ->setOption('displayStart', (bool)$modx->getOption('displayStart', $scriptProperties, false))
    ->setOption('resourceColumns', $modx->getOption('', $scriptProperties, null))
    ->setOption('viewHidden', (bool)$modx->getOption('viewHidden', $scriptProperties, false))
    ->setOption('viewUnpublished', (bool)$modx->getOption('viewUnpublished', $scriptProperties, false))
    ->setOption('viewDeleted', (bool)$modx->getOption('viewDeleted', $scriptProperties, false))
    ->setOption('templates', $modx->getOption('templates', $scriptProperties, null))
    ->setOption('contexts', $modx->getOption('contexts', $scriptProperties, $modx->context->key))
    ->setOption('limit', $modx->getOption('limit', $scriptProperties, 0))
    ->setOption('offset', $modx->getOption('offset', $scriptProperties, 0))
    ->setOption('scheme', $modx->getOption('scheme', $scriptProperties, $modx->getOption('link_tag_scheme')))
    ->setOption('where', $modx->getOption('where', $scriptProperties, null))
    ->setOption('debugSql', (bool)$modx->getOption('debugSql', $scriptProperties, false))
    ->setOption('rawTvs', $modx->getOption('rawTvs', $scriptProperties, ''))
    ->setOption('processTvs', $modx->getOption('processTvs', $scriptProperties, ''))
    ->setOption('limitLevelItems', $modx->getOption('limitLevelItems', $scriptProperties, ''))
    ->setIteratorType($modx->getOption('iterateType', $scriptProperties, 'getIterator'))
    //->setOption('', $modx->getOption('', $scriptProperties, ''))
    // generated from MODX:
    ->setOption('activeResource', $modx->resource->get('id'))
    ->setOption('siteStart', $site_start)
    ->setOption('branchParents', $branch_parents);

/**
 * TODO
    includeDocs
    excludeDocs
    limitDepthItems
    sortBy
    TVs
 *  Selected columns
 */

/**
 * Now set what chunks will be used:
 */
foreach ( $scriptProperties as $property => $value ) {
    switch ( $property ) {
        case 'chunkWrapper':
            $menuBuilder->setChunk('chunkWrapper', $value);
            continue 2;
            break;
        case 'chunkItem':
            $menuBuilder->setChunk('chunkItem', $value);
            continue 2;
            break;
        // Set Classes:
        case 'activeBranchClass':
            // no break
        case 'hereClass':
            $menuBuilder->setClass('activeBranchClass', $value);
            continue 2;
            break;
        case 'selfClass':
            $menuBuilder->setClass('selfClass', $value);
            continue 2;
            break;

        // Ignore these:
        case 'placeholder':
            // no break
        case 'start_id':
            // no break
        case 'limit':
            // no break
        case 'debug':
            // no break
        case 'displayStart':
            // no break
        case 'viewHidden':
            // no break
        case 'viewUnpublished':
            // no break
        case 'viewDeleted':
            // no break
        case 'templates':
            // no break
        case 'contexts':
            // no break
        case 'limit':
            // no break
        case 'offset':
            // no break
        case 'scheme':
            // no break
        case 'where':
            // no break
        case '':
            continue 2;

    }
    // Resource specific Chunks:
    if ( strpos($property, 'chunkWrapperResource') === 0 ) {
        $d = (int) substr($property, strlen('chunkWrapperResource'));
        $menuBuilder->setChunk('chunkWrapperResource', $value, $d);
    }
    if ( strpos($property, 'chunkItemResource') === 0 ) {
        $d = (int) substr($property, strlen('chunkItemResource'));
        $menuBuilder->setChunk('chunkItemResource', $value, $d);
    }

    // Level specific Chunks:
    // Optional wrapper depth ones:
    if ( strpos($property, 'chunkWrapper') === 0 ) {
        $d = (int) substr($property, strlen('chunkWrapper'));
        $menuBuilder->setChunk('chunkWrapper', $value, $d);
    }

    // Optional item depth ones:
    if ( strpos($property, 'chunkItem') === 0 ) {
        $d = (int) substr($property, strlen('chunkItem'));
        $menuBuilder->setChunk('chunkItem', $value, $d);
    }
}

$output = $menuBuilder->buildMenu(
    (int)$modx->getOption('startId', $scriptProperties, 0),
    (int)$modx->getOption('level', $scriptProperties, 0)
);

if ( !empty($placeholder) ) {
    $modx->setPlaceholder($placeholder, $output);
} else {
    return $output;
}