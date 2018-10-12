<?php

namespace LCI\MODX\MenuBuilder;

use Iterator;
use PDO;

/**
 * An iteratable representation of an xPDOObject result set.
 *
 * Use an xPDOIterator to loop over large result sets and work with one instance
 * at a time. This greatly reduces memory usage over loading the entire collection
 * of objects into memory at one time. It is also slightly faster.
 *
 * @package menubuilder
 * @extends xpdo xPDOIterator
 */
class MbIterator /* extends xPDOIterator*/ implements Iterator {

    /**
     * @var string|null A settable SQL statement to then run get iterator
     */
    protected $sql_statement = null;

    /**
     * @param string $sql_statement
     *
     * @return $this
     */
    public function setSqlStatement($sql_statement)
    {
        $this->sql_statement = $sql_statement;
        return $this;
    }

    public function rewind() {
        $this->index = 0;
        if (!empty($this->stmt)) {
            $this->stmt->closeCursor();
        }

        if ( !empty($this->sql_statement) ) {
            $tstart = microtime(true);
            $this->stmt = $this->xpdo->query($this->sql_statement);

            if ( !$this->stmt ) {
                // failed:
                print_r($this->xpdo->errorInfo());

            } else {
                $this->xpdo->queryTime += microtime(true) - $tstart;
                $this->xpdo->executedQueries++;
                $this->fetch();
            }
        } else {
            // do the standard xpdo here:
            $this->rewindOriginal();
        }
    }

    /**
     * This is from the xPDOIterator class
     * @TODO private must be protected and would be nice to include the above code to do a SQL statement
     */
    protected $xpdo = null;
    protected $index = 0;
    protected $current = null;
    /** @var null|PDOStatement */
    protected $stmt = null;
    protected $class = null;
    protected $alias = null;
    /** @var null|int|str|array|xPDOQuery */
    protected $criteria = null;
    protected $criteriaType = 'xPDOQuery';
    protected $cacheFlag = false;

    /**
     * Construct a new xPDOIterator instance (do not call directly).
     *
     * @see xPDO::getIterator()
     * @param xPDO &$xpdo A reference to a valid xPDO instance.
     * @param array $options An array of options for the iterator.
     * @return xPDOIterator An xPDOIterator instance.
     */
    function __construct(& $xpdo, array $options= array()) {
        $this->xpdo =& $xpdo;
        if (isset($options['class'])) {
            $this->class = $this->xpdo->loadClass($options['class']);
        }
        if (isset($options['alias'])) {
            $this->alias = $options['alias'];
        } else {
            $this->alias = $this->class;
        }
        if (isset($options['cacheFlag'])) {
            $this->cacheFlag = $options['cacheFlag'];
        }
        if (array_key_exists('criteria', $options) && is_object($options['criteria'])) {
            $this->criteria = $options['criteria'];
        } elseif (!empty($this->class)) {
            $criteria = array_key_exists('criteria', $options) ? $options['criteria'] : null;
            $this->criteria = $this->xpdo->getCriteria($this->class, $criteria, $this->cacheFlag);
        }
        if (!empty($this->criteria)) {
            $this->criteriaType = $this->xpdo->getCriteriaType($this->criteria);
            if ($this->criteriaType === 'xPDOQuery') {
                $this->class = $this->criteria->getClass();
                $this->alias = $this->criteria->getAlias();
            }
        }
    }

    /**
     *
     */
    public function rewindOriginal() {
        $this->index = 0;
        if (!empty($this->stmt)) {
            $this->stmt->closeCursor();
        }
        $this->stmt = $this->criteria->prepare();
        $tstart = microtime(true);
        if ($this->stmt && $this->stmt->execute()) {
            $this->xpdo->queryTime += microtime(true) - $tstart;
            $this->xpdo->executedQueries++;
            $this->fetch();
        } elseif ($this->stmt) {
            $this->xpdo->queryTime += microtime(true) - $tstart;
            $this->xpdo->executedQueries++;
        }
    }

    public function current() {
        return $this->current;
    }

    public function key() {
        return $this->index;
    }

    public function next() {
        $this->fetch();
        if (!$this->valid()) {
            $this->index = null;
        } else {
            $this->index++;
        }
        return $this->current();
    }

    public function valid() {
        return ($this->current !== null);
    }

    /**
     * Fetch the next row from the result set and set it as current.
     *
     * Calls the _loadInstance() method for the specified class, so it properly
     * inherits behavior from xPDOObject derivatives.
     */
    protected function fetch() {
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && !empty($row)) {
            $this->current = $this->xpdo->call($this->class, '_loadInstance', array(& $this->xpdo, $this->class, $this->alias, $row));
        } else {
            $this->current = null;
        }
    }
}