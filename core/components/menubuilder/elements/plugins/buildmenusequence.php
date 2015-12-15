<?php
/**
 * Rebuild out menu mb_sequence table if change
 *
 * @var modX $modx
 * @var modResource $resource
 * @var modResource $nodesAffected
 */

$core_path = $modx->getOption('menubuilder.core_path', null, $modx->getOption('core_path').'components/menubuilder/');
require_once $core_path.'model/menubuilder/MenuBuilder.php';

$menuBuilder = new MenuBuilder($modx);

// @TODO System Setting to turn on/off debug:
// $menuBuilder->setDebug();

$eventName = $modx->event->name;
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
                    $menuBuilder->buildBranch($new_parent);
                }
            }
        }

        break;

}
