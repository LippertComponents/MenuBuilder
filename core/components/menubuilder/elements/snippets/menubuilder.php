<?php
/**
 * Build out menu, flush DB at first
 */

$core_path = $modx->getOption('menubuilder.core_path', null, $modx->getOption('core_path').'components/menubuilder/');
require_once $core_path.'model/menubuilder/MenuBuilder.php';

$placeholder = $modx->getOption('placeholder', $scriptProperties, null);

$menuBuilder = new MenuBuilder($modx);

if ( (bool)$modx->getOption('debug', $scriptProperties, false) ) {
    $menuBuilder->setDebug();
}
/**
 * Now get user options:
 */
$menuBuilder
    ->setOption('displayStart', (bool)$modx->getOption('displayStart', $scriptProperties, false))
    ->setOption('viewHidden', (bool)$modx->getOption('viewHidden', $scriptProperties, false))
    ->setOption('viewUnpublished', (bool)$modx->getOption('viewUnpublished', $scriptProperties, false))
    ->setOption('viewDeleted', (bool)$modx->getOption('viewDeleted', $scriptProperties, false))
    ->setOption('templates', $modx->getOption('templates', $scriptProperties, null))
    ->setOption('contexts', $modx->getOption('contexts', $scriptProperties, null))
    ->setOption('limit', $modx->getOption('limit', $scriptProperties, 0))
    ->setOption('offset', $modx->getOption('offset', $scriptProperties, 0))
    ->setOption('scheme', $modx->getOption('scheme', $scriptProperties, $modx->getOption('link_tag_scheme')))
    ->setOption('where', $modx->getOption('where', $scriptProperties, null));
    //->setOption('', $modx->getOption('', $scriptProperties, ''));
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
    // Optional wrapper depth ones:
    if ( strpos($property, 'chunkWrapper') ) {
        $d = (int) substr($property, strlen('chunkWrapper'));
        $menuBuilder->setChunk('chunkWrapper', $value, $d);
    }

    // Optional wrapper depth ones:
    if ( strpos($property, 'chunkItem') ) {
        $d = (int) substr($property, strlen('chunkItem'));
        $menuBuilder->setChunk('chunkItem', $value, $d);
    }
}

$output = $menuBuilder->buildMenu(
    (int)$modx->getOption('start_id', $scriptProperties, 0),
    (int)$modx->getOption('level', $scriptProperties, 0)
);

if ( !empty($placeholder) ) {
    $modx->setPlaceholder($placeholder, $output);
} else {
    return $output;
}