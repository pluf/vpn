<?php
use Pluf\Db\Engine;

Pluf::loadFunction('Pluf_Shortcuts_GetAssociationTableName');
Pluf::loadFunction('Pluf_Shortcuts_GetForeignKeyName');

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
 * VPN Account data model
 *
 * Stores information of an vpn-account.
 */
class Vpn_Account extends Pluf_Model
{

    function init()
    {
        $this->_a['verbose'] = 'vpn-account';
        $this->_a['table'] = 'user_accounts';
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => Engine::SEQUENCE,
                // It is automatically added.
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            'login' => array(
                'type' => Engine::VARCHAR,
                'is_null' => false,
                'unique' => true,
                'size' => 50,
                'editable' => true,
                'readable' => true
            ),
            'date_joined' => array(
                'type' => 'Datetime',
                'is_null' => true,
                'editable' => false
            ),
            'last_login' => array(
                'type' => 'Datetime',
                'is_null' => true,
                'editable' => false
            ),
            'is_active' => array(
                'type' => 'Boolean',
                'is_null' => false,
                'default' => false,
                'editable' => true
            ),
            'is_deleted' => array(
                'type' => 'Boolean',
                'is_null' => false,
                'default' => false,
                'editable' => false
            )
        );
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Model::__toString()
     */
    function __toString()
    {
        return $this->login;
    }

    /**
     * فراخوانی‌های پیش از حذف کاربر
     *
     * پیش از این که کاربر حذف شود یک سیگنال به کل سیستم ارسال شده و حذف کاربر
     * گزارش می‌شود.
     */
    function preDelete()
    {
        /**
         * [signal]
         *
         * User::preDelete
         *
         * [sender]
         *
         * User
         *
         * [description]
         *
         * This signal allows an application to perform special
         * operations at the deletion of a user.
         *
         * [parameters]
         *
         * array('user' => $user)
         */
        $params = array(
            'user' => $this
        );
        Pluf_Signal::send('Vpn_Account::preDelete', 'Vpn_Account', $params);
    }

    /**
     * Extract information of account and returns it.
     *
     * @param string $login
     * @return Vpn_Account account information
     */
    public static function getAccount($login)
    {
        $model = new Vpn_Account();
        $where = new Pluf_SQL('login = %s', array($model->_toDb($login, 'login')));
        $users = $model->getList(array(
            'filter' => $where->gen()
        ));
        if ($users === false or count($users) !== 1) {
            return false;
        }
        return $users[0];
    }

    /**
     * Set the last_login and date_joined before creating.
     */
    function preSave($create = false)
    {
        if (! ($this->id > 0)) {
            $this->last_login = gmdate('Y-m-d H:i:s');
            $this->date_joined = gmdate('Y-m-d H:i:s');
        }
    }

    /**
     * Checks if account is active
     *
     * @return boolean true if account is active else false
     */
    function isActive()
    {
        return $this->is_active;
    }

    function setDeleted($deleted)
    {
        $this->_data['is_deleted'] = $deleted;
        $this->update();
    }
}
