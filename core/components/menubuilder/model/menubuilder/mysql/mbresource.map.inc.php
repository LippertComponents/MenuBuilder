<?php
$xpdo_meta_map['MbResource']= array (
  'package' => 'menubuilder',
  'version' => '1.1',
  'extends' => 'modResource',
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'Sequence' => 
    array (
      'local' => 'id',
      'class' => 'MbSequence',
      'foreign' => 'resource_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
);
