<?php
/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
namespace app\components;

use Yii;
use yii\base\Component;


// REMOVE THIS BLOCK - used for DataTables test environment only!


class GridServer extends Component{
    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    public static function data_output ( $columns, $data )
    {
        $out = array();

        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();

            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];

                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                }
                else {
                    $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
                }
            }

            $out[] = $row;
        }

        return $out;
    }
    
    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    public static function limit ( $request, $columns )
    {
        $limit = '';

        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }

        return $limit;
    }


    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    public static function order ( $request, $columns )
    {
        $order = '';

        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            $dtColumns = self::pluck( $columns, 'dt' );

            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['orderable'] == 'true' ) {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = '`'.$column['db'].'` '.$dir;
                }
            }

            $order = 'ORDER BY '.implode(', ', $orderBy);
        }

        return $order;
    }


    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for \PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    public static function filter ( $request, $columns, &$bindings )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck( $columns, 'dt' );

        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];

            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                if ( $requestColumn['searchable'] == 'true' ) {
                    $binding = self::bind( $bindings, '%'.$str.'%', \PDO::PARAM_STR );
                    $globalSearch[] = "`".$column['db']."` LIKE ".$binding;
                }
            }
        }

        // Individual column filtering
        for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
            $requestColumn = $request['columns'][$i];
            $columnIdx = array_search( $requestColumn['data'], $dtColumns );
            $column = $columns[ $columnIdx ];

            $str = $requestColumn['search']['value'];

            if ( $requestColumn['searchable'] == 'true' &&
             $str != '' ) {
                $binding = self::bind( $bindings, '%'.$str.'%', \PDO::PARAM_STR );
                $columnSearch[] = "`".$column['db']."` LIKE ".$binding;
            }
        }
        
        //自定义参数
        $userParams = array();
        if(isset($request['user']) && count($request['user']) > 0){
            foreach($request['user'] as $key => $value){
                if(!empty($value)){
                    if(is_array($value)){
                        foreach($value as $k => $v){
                            $value[$k] = "'".$v."'";
                        }
                        $userParams[] = "`".$key."` in (".implode(',', $value).")";
                    }else{
                        $userParams[] = "`".$key."` = '".$value."'";
                    }
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }

        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }
        
        if ( count( $userParams ) ) {
            $where = $where === '' ?
            implode(' AND ', $userParams) :
            $where .' AND '. implode(' AND ', $userParams);
        }

        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }
        return $where;
    }


    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|\PDO $conn \PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    public static function simple ( $request, $db, $table, $columns )
    {
        $bindings = array();

        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns );
        $where = self::filter( $request, $columns, $bindings );

        // Main query to actually get the data
        $data = self::sql_exec( $db, $bindings,
            "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
             FROM `$table`
             $where
             $order
             $limit"
        );
        
        // Data set length after filtering
        $resFilterLength = self::sql_exec( $db,
            "SELECT FOUND_ROWS() as filter"
        );
        
        $recordsFiltered = $resFilterLength[0]['filter'];

        // Total data set length
        $resTotalLength = self::sql_exec( $db,
            "SELECT COUNT(*) as total
             FROM   `$table`"
        );
        
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => intval( $request['draw'] ),
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data )
        );
    }


    /**
     * The difference between this method and the `simple` one, is that you can
     * apply additional `where` conditions to the SQL queries. These can be in
     * one of two forms:
     *
     * * 'Result condition' - This is applied to the result set, but not the
     *   overall paging information query - i.e. it will not effect the number
     *   of records that a user sees they can have access to. This should be
     *   used when you want apply a filtering condition that the user has sent.
     * * 'All condition' - This is applied to all queries that are made and
     *   reduces the number of records that the user can access. This should be
     *   used in conditions where you don't want the user to ever have access to
     *   particular records (for example, restricting by a login id).
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|\PDO $conn \PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  string $whereResult WHERE condition to apply to the result set
     *  @param  string $whereAll WHERE condition to apply to all queries
     *  @return array          Server-side processing response array
     */
    public static function complex ( $request, $conn, $table, $columns, $whereResult=null, $whereAll=null )
    {
        $bindings = array();
        $db = self::db( $conn );
        $localWhereResult = array();
        $localWhereAll = array();
        $whereAllSql = '';

        // Build the SQL query string from the request
        $limit = self::limit( $request, $columns );
        $order = self::order( $request, $columns );
        $where = self::filter( $request, $columns, $bindings );

        $whereResult = self::_flatten( $whereResult );
        $whereAll = self::_flatten( $whereAll );

        if ( $whereResult ) {
            $where = $where ?
                $where .' AND '.$whereResult :
                'WHERE '.$whereResult;
        }

        if ( $whereAll ) {
            $where = $where ?
                $where .' AND '.$whereAll :
                'WHERE '.$whereAll;

            $whereAllSql = 'WHERE '.$whereAll;
        }

        // Main query to actually get the data
        $data = self::sql_exec( $db, $bindings,
            "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", self::pluck($columns, 'db'))."`
             FROM `$table`
             $where
             $order
             $limit"
        );

        // Data set length after filtering
        $resFilterLength = self::sql_exec( $db,
            "SELECT FOUND_ROWS() as filter"
        );
        $recordsFiltered = $resFilterLength[0]['filter'];

        // Total data set length
        $resTotalLength = self::sql_exec( $db, $bindings,
//             "SELECT COUNT(`{$primaryKey}`) as total
            "SELECT COUNT(*) as total
             FROM   `$table` ".
            $whereAllSql
        );
        $recordsTotal = $resTotalLength[0]['total'];

        /*
         * Output
         */
        return array(
            "draw"            => intval( $request['draw'] ),
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => self::data_output( $columns, $data )
        );
    }


    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of \PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    public static function sql_exec ( $db, $bindings, $sql=null )
    {
        if ( $sql === null ) {
            $sql = $bindings;
        }
        
        // Execute
        $stmt = $db->createCommand($sql);
        
        if ( is_array( $bindings ) ) {
            for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
                $binding = $bindings[$i];
                $stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
            }
        }

        // Return all
        return $stmt->queryAll();
    }


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */

    /**
     * Create a \PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type \PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    public static function bind ( &$a, $val, $type )
    {
        $key = ':binding_'.count( $a );

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );

        return $key;
    }


    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    public static function pluck ( $a, $prop )
    {
        $out = array();

        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            $out[] = $a[$i][$prop];
        }

        return $out;
    }


    /**
     * Return a string from an array or a string
     *
     * @param  array|string $a Array to join
     * @param  string $join Glue for the concatenation
     * @return string Joined string
     */
    public static function _flatten ( $a, $join = ' AND ' )
    {
        if ( ! $a ) {
            return '';
        }
        else if ( $a && is_array($a) ) {
            return implode( $join, $a );
        }
        return $a;
    }
}

