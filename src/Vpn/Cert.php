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
            )
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
        // ----------------------- CSR --------------------
        $dn = array(
            "organizationName" => "VPNex",
            "commonName" => $account->login
        );
        $kp = Vpn_Keypair::getOneKeypair($account);
        $kp = $kp == null ? Vpn_Keypair::generate($account) : $kp;
        $privRes = openssl_pkey_get_private($kp->private_pem);
        $csrConfig = [ // FIXME: client cert configuration
        ];
        $datetime = new DateTime();
        $datetime = array_key_exists('expire', $params) ? //
            $datetime->setTimestamp($params['expire']) : //
            $datetime->add(new DateInterval('PT5M'));
        $csrExtraattribs = [ // 'enddate' => $datetime->format('YYMMDDHHMMSSZ')
        ];
        $csr = openssl_csr_new($dn, $privRes); // , $csrConfig, $csrExtraattribs);
                                               // ----------------------- CERT --------------------
                                               // FIXME: expiry must be replaced with datetime
        $caCert = Vpn_Cert::getDefaultCa();
        $caCertRes = openssl_x509_read($caCert->pem);
        $caKp = Vpn_Keypair::getDefaultCaKeypair();
        $caPrivRes = openssl_pkey_get_private($caKp->private_pem);
        $seconds = $datetime->getTimestamp() - date_timestamp_get(new DateTime());
        $cert = openssl_csr_sign($csr, $caCertRes, $caPrivRes, $seconds);
        if (! $cert) {
            $errMsg = openssl_error_string();
            while ($msg = openssl_error_string()) {
                $errMsg = "$errMsg\n$msg";
            }
            throw new \Pluf\Exception($errMsg);
        }

        // ----------------------- Save --------------------
        $certPem = null;
        openssl_x509_export($cert, $certPem);
        $certObj = new Vpn_Cert();
        $certObj->account_id = $account;
        $certObj->pem = $certPem;
        $certObj->expire_dtime = $datetime->format('Y-m-d H:i:s');
        $certObj->create();
        return $certObj;
    }

    public static function revokeAll(Vpn_Account $account)
    {
        $certList = self::getValidCerts($account);
        foreach ($certList as $c) {
            $c->is_revoked = true;
            $c->update();
        }
    }

    public static function getValidCerts(Vpn_Account $account): ArrayObject
    {
        $params = [
            'filter' => "account_id=$account->id AND expire_dtime > CURRENT_TIMESTAMP() AND is_revoked=false"
        ];
        $cert = new Vpn_Cert();
        return $cert->getList($params);
    }

    public static function getOneValidCert(Vpn_Account $account)
    {
        $items = self::getValidCerts($account);
        if ($items->count() == 1) {
            return $items[0];
        }
        if ($items->count() == 0) {
            return null;
        }
        throw new \Pluf\Exception('Error: More than one certificates found.');
    }

    public static function getDefaultCa()
    {
        // returns default CA
        $filePath = Pluf_Tenant::storagePath() . '/vpn/ca_cert.pem';
        $caPem = Vpn_Util::getFileContent($filePath);
        $fakeCertObj = new Vpn_Cert();
        $fakeCertObj->pem = $caPem;
        return $fakeCertObj;
    }
}