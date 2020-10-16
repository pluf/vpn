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
 * A VPN key pair
 */
class Vpn_Keypair extends Pluf_Model
{

    /**
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'vpn_keypairs';
        $this->_a['cols'] = array(
            // Identifier
            'id' => array(
                'type' => 'Sequence',
                'is_null' => false,
                'editable' => false
            ),
            // Fields
            'private_pem' => array(
                'type' => 'Text',
                'is_null' => true,
                'editable' => true
            ),
            'public_pem' => array(
                'type' => 'Text',
                'is_null' => true,
                'editable' => true
            ),
            // Foreign keys
            'account_id' => array(
                'type' => 'Foreignkey',
                'model' => 'Vpn_Account',
                'name' => 'account',
                'graphql_name' => 'account',
                'relate_name' => 'keypairs',
                'is_null' => true,
                'editable' => true
            )
        );
    }

    public static function generate(Vpn_Account $account): Vpn_Keypair
    {
        // Create the keypair
        $configargs = [
            // FIXME: client cert configuration
        ];
        $res = openssl_pkey_new($configargs);
        if ($res === false) {
            throw new \Pluf\Exception('Error: Faile to generate keypair.');
        }
        // Get private key
        $privkey = '';
        openssl_pkey_export($res, $privkey);
        // Get public key
        $pubkey = openssl_pkey_get_details($res);
        $pubkey = $pubkey["key"];

        $keypair = new Vpn_Keypair();
        $keypair->private_pem = $privkey;
        $keypair->public_pem = $pubkey;
        $keypair->account_id = $account;
        $kp = $keypair->create();
        if (! $kp) {
            return new \Pluf\Exception('Error: Faile to store keypair.');
        }
        return $kp;
    }

    public static function getAll(Vpn_Account $account): ArrayObject
    {
        $params = [
            'filter' => new Pluf_SQL("account_id=$account->id")
        ];
        $kp = new Vpn_Keypair();
        return $kp->getList($params);
    }

    public static function getOne(Vpn_Account $account): Vpn_Keypair
    {
        $items = self::getAll($account);
        if ($items->count() == 1) {
            return $items[0];
        }
        if ($items->count() == 0) {
            return null;
        }
        throw new \Pluf\Exception(__('Error: More than one keypair found for the account.'));
    }

    public static function getDefaultCaKeypair(): Vpn_Keypair
    {
        // TODO: return default CA keypair
    }
}