<?php

/**
 * Created by PhpStorm.
 * User: stanislav.goldmann
 * Date: 08.09.2015
 */

namespace Krenor\EloquentFilter;

use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;

use Illuminate\Support\Facades\Input;

class FilterableScope implements ScopeInterface{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $filterable = $model->getFilterableColumns();

        foreach($filterable as $key => $filter)
        {
            // Check if the filterable column has name alias
            if( $filter['alias'] )
            {
                // Access the input by that in the URL
                $input = Input::get( $filter['alias'] );

                // Check for value aliases
                if( $filter['value_aliases'] && in_array($input, $filter['value_aliases']) )
                {
                    // Flip the array to access the 'real' value by its alias
                    $input = array_flip( $filter['value_aliases'] )[$input];
                }
            }
            else {
                $input = Input::get( $filter['column'] );
            }

            // Apply query only if the corresponding input has a value.
            // If it's not a valid one, the query returned will be probably empty
            // since there are no matches in the database.
            if($input) $builder->where($filter["column"], '=', $input);
        }

    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function remove(Builder $builder, Model $model)
    {
        $query = $builder->getQuery();
        $filterable = [];

        foreach( $model->getFilterableColumns() as $index => $filter)
        {
            $filterable[] = $filter['column'];
        }

        foreach($query->wheres as $key => $where)
        {
            if(array_search($where['column'], $filterable) ){
                $this->removeWhere($query, $key);
            }
        }

        // Reindex
        $query->wheres = array_values($query->wheres);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param integer $key
     * @return void
     */
    private function removeWhere(BaseBuilder $query, $key)
    {
        unset($query->wheres[$key]);
    }

} // Class End