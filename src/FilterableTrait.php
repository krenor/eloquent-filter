<?php

/**
 * Created by PhpStorm.
 * User: stanislav.goldmann
 * Date: 08.09.2015
 */

namespace Krenor\EloquentFilter;

trait FilterableTrait
{
    /*
     * Boot the scope globally.
     *
     * @return void
     */
    public static function bootFilterableTrait()
    {
        static::addGlobalScope(new FilterableScope);
    }

    /**
     * Get the array with the columns and aliases for applying the scope.
     *
     * @return array
     */
    public function getFilterableColumns()
    {
        return $this->buildFilterArray($this->filterable);
    }

    private function buildFilterArray(Array $filter)
    {
        $formatted = array();

        foreach($filter as $column => $alias)
        {
            if(is_numeric($column)){
                // If there is a column without an alias it gets indexed
                // Therefore take the 'alias' which is actually the column
                $formatted[] = [ 'column' => $alias, 'alias' => false, 'value_aliases' => false ];
            }
            else {
                // Check if value aliases are set
                if(is_array($alias)){
                    $formatted[] = ['column' => $column, 'alias' => key($alias), 'value_aliases' => reset($alias)];
                }
                else {
                    $formatted[] = ['column' => $column, 'alias' => $alias, 'value_aliases' => false];
                }
            }
        }

        return $formatted;
    }

} // Trait End