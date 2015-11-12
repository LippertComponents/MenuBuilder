<?php
/**
 * Install table
 */

// add package
$core_path = $modx->getOption('menubuilder.core_path', null, $this->modx->getOption('core_path').'components/menubuilder/');
$modx->addPackage('menubuilder', $core_path . 'model/');

$m = $modx->getManager();
// the class table object name
$m->createObjectContainer('MbSequence');

return 'MbSequence table created.';