<?php


namespace LCI\MODX\MenuBuilder\Helpers;


trait Nesting
{
    /**
     * @param string $item_id_column - column name to read the current record_id, e.g. 'id'
     */
    protected static $item_id_column = 'id';

    /**
     * @param string $item_parent_column - column name to read the related parent_id, e.g. 'parent'
     */
    protected static $item_parent_column = 'parent';

    /**
     * @param string $children_key - name of the array key property to place children, e.g. 'children'
     */
    protected static $children_key = 'children';

    /**
     * @var callable|null $format_callback
     */
    protected static $callable_format_item = null;

    /**
     * @see https://stackoverflow.com/a/44013573
     * Nesting an array of records using a parent and id property to match and create a valid Tree
     *
     * Convert this:
     * [
     *   'id' => 1,
     *   'parent'=> null
     * ],
     * [
     *   'id' => 2,
     *   'parent'=> 1
     * ]
     *
     * Into this:
     * [
     *   'id' => 1,
     *   'parent'=> null
     *   'children' => [
     *     'id' => 2
     *     'parent' => 1,
     *     'children' => []
     *    ]
     * ]
     *
     * @param array  $items - array of records to apply the nesting
     * @param string $parent_id - filter to filter by parent
     * @param bool $use_path_index
     * @return array
     */
    public function nest(&$items, $parent_id = null, $use_path_index=true)
    {
        $nestedRecords = [];
        foreach ($items as $index => $children) {
            if (isset($children[static::$item_parent_column]) && $children[static::$item_parent_column] == $parent_id) {
                // format:
                if (!empty(static::$callable_format_item)) {
                    call_user_func_array(static::$callable_format_item, [&$children]);
                }

                unset($items[$index]);
                $children[static::$children_key] = $this->nest($items, $children[static::$item_id_column]);

                if ($use_path_index && isset($children['path'])) {
                    $parts = explode('.', $children['path']);

                    $nestedRecords[$parts[count($parts) - 1]] = $children;

                } else {
                    $nestedRecords[] = $children;
                }
            }
        }

        return $nestedRecords;
    }
}
