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
        $configargs = [ // FIXME: client cert configuration
        ];
        $res = openssl_pkey_new($configargs);
        if ($res === false) {
            throw new \Pluf\Exception('Error: Faile to generate keypair.');
        }
        $keypair = self::fromOpensslKey($res);
        $keypair->account_id = $account;
        $kp = $keypair->create();
        if (! $kp) {
            return new \Pluf\Exception('Error: Faile to store keypair.');
        }
        return $keypair;
    }

    public static function getAllKeypairs(Vpn_Account $account): ArrayObject
    {
        $params = [
            'filter' => "account_id=$account->id"
        ];
        $kp = new Vpn_Keypair();
        return $kp->getList($params);
    }

    public static function getOneKeypair(Vpn_Account $account)
    {
        $items = self::getAllKeypairs($account);
        if ($items->count() == 1) {
            return $items[0];
        }
        if ($items->count() == 0) {
            return null;
        }
        throw new \Pluf\Exception('Error: More than one keypair found for the account.');
    }

    public static function getDefaultCaKeypair(): Vpn_Keypair
    {
        $filePath = Pluf_Tenant::storagePath() . '/vpn/ca_key.pem';
        $content = Vpn_Util::getFileContent($filePath);
        $keyRes = openssl_pkey_get_private($content, Tenant_Service::setting('vpn.privateKey.password', '123456'));
        if(!$keyRes){
            $errMsg = openssl_error_string();
            while($msg = openssl_error_string()){
                $errMsg = "$errMsg\n$msg";
            }
            throw new \Pluf\Exception($errMsg);
        }
        $fakeKeyObj = self::fromOpensslKey($keyRes);
        return $fakeKeyObj;
    }

    /**
     * Converts an OpenSSl key resource (a key in the structure generated by openssl library) to Vpn_Keypair
     * 
     * @param unknown $keyRes
     * @return Vpn_Keypair
     */
    public static function fromOpensslKey($keyRes): Vpn_Keypair
    {
        // Get private key
        $privkey = '';
        openssl_pkey_export($keyRes, $privkey);
        // Get public key
        $pubkey = openssl_pkey_get_details($keyRes);
        $pubkey = $pubkey["key"];
        
        $keypair = new Vpn_Keypair();
        $keypair->private_pem = $privkey;
        $keypair->public_pem = $pubkey;
        return $keypair;
    }
}