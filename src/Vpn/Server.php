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
 * A VPN server
 *
 */
class Vpn_Server extends Pluf_Model
{

    /**
     * 
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'vpn_servers';
        $this->_a['cols'] = array(
            // Identifier
            'id' => array(
                'type' => 'Sequence',
                'is_null' => false,
                'editable' => false
            ),
            // Fields
            'title' => array(
                'type' => 'Varchar',
                'is_null' => true,
                'size' => 256,
                'editable' => true
            ),
            'domain' => array(
                'type' => 'Varchar',
                'is_null' => false,
                'size' => 256,
                'default' => '',
                'editable' => true
            ),
            'port' => array(
                'type' => 'Integer',
                'is_null' => false,
                'default' => 0,
                'editable' => true
            ),
            'protocol' => array(
                'type' => 'Varchar',
                'is_null' => false,
                'size' => 16,
                'default' => '',
                'editable' => true
            ),
            'type' => array(
                'type' => 'Varchar',
                'is_null' => false,
                'size' => 64,
                'default' => '',
                'editable' => true
            ),
            'country' => array(
                'type' => 'Varchar',
                'is_null' => true,
                'size' => 64,
                'default' => '',
                'editable' => true
            ),
            // Foreign keys
        );
        
//         $this->_a['idx'] = array(
//             'vpn_server_uniqueness_idx' => array(
//                 'col' => 'domain, port, protocol',
//                 'type' => 'unique', // normal, unique, fulltext, spatial
//                 'index_type' => '', // hash, btree
//                 'index_option' => '',
//                 'algorithm_option' => '',
//                 'lock_option' => ''
//             )
//         );
        
    }
}