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
 * A VPN certificate
 */
class Vpn_Cert extends Pluf_Model
{

    /**
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'vpn_certs';
        $this->_a['cols'] = array(
            // Identifier
            'id' => array(
                'type' => 'Sequence',
                'is_null' => false,
                'editable' => false
            ),
            // Fields
            'pem' => array(
                'type' => 'Text',
                'is_null' => true,
                'editable' => true
            ),
            'is_revoked' => array(
                'type' => 'Boolean',
                'is_null' => false,
                'default' => false,
                'editable' => false
            ),
            'expire_dtime' => array(
                'type' => 'Datetime',
                'blank' => true,
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            // Foreign keys
            'account_id' => array(
                'type' => 'Foreignkey',
                'model' => 'Vpn_Account',
                'name' => 'account',
                'graphql_name' => 'account',
                'relate_name' => 'certs',
                'is_null' => true,
                'editable' => true
            ),
        );

        $this->_a['idx'] = array(
            'vpn_cert_is_revoked_idx' => array(
                'col' => 'is_revoked',
                'type' => 'normal', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            )
        );
    }

    public static function generate(Vpn_Account $account, array $params): Vpn_Cert
    {
        $dn = array(
            "organizationName" => "VPNex",
            "commonName" => $account->login
        );
        $datetime = new DateTime();
        $datetime->add(new DateInterval('PT5M'));
        $kp = Vpn_Keypair::getOne($account);
        $caCert = Vpn_Cert::getDefaultCa();
        $caCertRes = openssl_x509_read($caCert->pem);
        $privRes = openssl_pkey_get_private($kp->private_pem);
        $csr = openssl_csr_new($dn, $privRes, null, ['enddate' => $datetime->format('YYMMDDHHMMSSZ')]);
        $cert = openssl_csr_sign($csr, $caCertRes, $privRes, null);
        $certPem = null;
        openssl_x509_export($cert, $certPem);
        
        $certObj = new Vpn_Cert();
        $certObj->account_id = $account->id;
        $certObj->pem = $certPem;
        $certObj->expire_dtime = $datetime->format('Y-m-d H:i:s');
        $certObj->create();
        return $certObj;
    }

    public static function revokeAll(Vpn_Account $account, array $param): bool
    {
        $certList = self::getValidCerts($account);
        foreach ($certList as $c){
            $c->is_revoked = true;
            $c->update();
        }
    }
    
    public static function getValidCerts(Vpn_Account $account) : ArrayObject{
        $params = [
            'filter' => new Pluf_SQL("account_id=$account->id AND exipre_dtime > CURRENT_TIMESTAMP()")
        ];
        $cert = new Vpn_Cert();
        return $cert->getList($params);
    }
    
    public static function getOneValidCert(Vpn_Account $account) : Vpn_Cert{
        $items = self::getOneValidCert($account);
        if ($items->count() == 1) {
            return $items[0];
        }
        if ($items->count() == 0) {
            return null;
        }
        throw new \Pluf\Exception(__('Error: More than one certificates found.'));
    }
    
    public static function getDefaultCa(){
        // returns default CA 
    }
    
}