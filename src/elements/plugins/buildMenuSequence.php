<?php
/**
 * Rebuild out menu mb_sequence table if change
 *
 * @var modX $modx
 * @var modResource $resource
 * @var modResource $nodesAffected
 */

use \LCI\MODX\MenuBuilder\MenuBuilder;

$menuBuilder = new MenuBuilder($modx);

$eventName = $modx->event->name;

if ( $modx->getOption('menubuilder.logDebug', null, false) ) {
    $menuBuilder->setDebug();
    $modx->log(modX::LOG_LEVEL_ERROR,'[MenuBuilder::Plugin] Called on Event: '.$eventName);
}

switch($eventName) {
    case 'OnCacheUpdate':
        // complete rebuild:
        if ( $modx->getOption('menubuilder.rebuildOnCacheUpdate', null, true) ) {
            $menuBuilder->buildTree();
        }
        break;
    case 'OnResourceSort':
        // @TODO review to brake down to affected branches see: \core\model\modx\processors\resource\sort.class.php
        // menubuilder.fireOnResourceSort
        if ( $modx->getOption('menubuilder.rebuildOnResourceSort', null, true) ) {
            // $nodesAffected ~ array of resources
            $menuBuilder->buildTree();
        }
        break;
    case 'OnDocFormSave':
        // menubuilder.fireOnDocFormSave System Setting:
        if ( $modx->getOption('menubuilder.rebuildOnDocFormSave', null, true) ) {
            $new_parent = $resource->get('parent');
            $new_menuindex = $resource->get('menuindex');

            $sequence = $modx->getObject('MbSequence', array('resource_id' => $resource->get('id')));

            if (!is_object($sequence)) {
                //$menuBuilder->setDebug();
                $menuBuilder->buildTree();
            } else {

                $org_parent = $org_menuindex = null;
                if (is_object($sequence)) {
                    $org_parent = $sequence->get('org_parent');
                    $org_menuindex = $sequence->get('org_menuindex');
                }
                if ($new_parent != $org_parent) {
                    // rebuild all @TODO context_key only
                    $menuBuilder->buildTree();
                } else if ($new_menuindex != $org_menuindex) {
                    // just rebuild the branch with the change:
                    $menuBuilder->buildBranch($new_parent, array('context_key' => $sequence->get('context_key')));
                }
            }
        }

        break;

}
