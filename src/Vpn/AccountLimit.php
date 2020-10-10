<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Limitations on an account
 *
 */
class Vpn_AccountLimit extends Pluf_Model
{

    /**
     * 
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'vpn_account_limits';
        $this->_a['cols'] = array(
            // Identifier
            'id' => array(
                'type' => 'Sequence',
                'is_null' => false,
                'editable' => false
            ),
            // Fields
            'key' => array(
                'type' => 'Varchar',
                'is_null' => false,
                'size' => 256,
                'editable' => true
            ),
            'value' => array(
                'type' => 'Varchar',
                'is_null' => true,
                'size' => 256,
                'default' => '',
                'editable' => true
            ),
            // Foreign keys
            'account_id' => array(
                'type' => 'Foreignkey',
                'model' => 'Vpn_Account',
                'name' => 'account',
                'graphql_name' => 'account',
                'relate_name' => 'limits',
                'is_null' => true,
                'editable' => true
            ),
        );
        
        $this->_a['idx'] = array(
            'vpn_account_limit_key_idx' => array(
                'col' => 'key, account_id',
                'type' => 'unique', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            )
        );
        
    }
    
    /**
     * Extract information of a limit and returns it.
     *
     * @param string $key
     * @param int $accountId
     * @return Vpn_AccountLimit
     */
    public static function getLimit($key, $accountId)
    {
        $model = new Vpn_AccountLimit();
        $where = new Pluf_SQL('`key`=%s AND `account_id`=%s', array(
            $model->_toDb($key, 'key'),
            $model->_toDb($accountId, 'account_id')
        ));
        $limit = $model->getList(array(
            'filter' => $where->gen()
        ));
        if ($limit === false or count($limit) !== 1) {
            return false;
        }
        return $limit[0];
    }
    
}