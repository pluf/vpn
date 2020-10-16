<?php

class Vpn_Views_Account extends Pluf_Views
{

    public function createAccount($request, $match, $param)
    {
        // Create account
        // TODO: new user must be in active
        if (! isset($param)) {
            $param = [];
        }
        $param['model'] = 'Vpn_Account';
        $account = parent::createObject($request, $match, $param);

        // Genereate account data
        $acceptType = array_key_exists('Accept', $request->HEADERS) ? $request->HEADERS['Accept'] : null;
        if ($acceptType === 'application/ovpn') {
            // return self::downloadOvpnFile($account);
            $ovpnStr = self::generateClientOvpn($account);
            return new Pluf_HTTP_Response_PlainText($ovpnStr, 'application/ovpn');
        } else if ($acceptType === 'application/ikv2') {
            // FIXME: Generate IKEV2 config file
            $ovpnStr = self::generateClientOvpn($account);
            return new Pluf_HTTP_Response_PlainText($ovpnStr, 'application/ovpn');
        }
        return $account;
    }

    public function get($request, $match)
    {
        $account = Vpn_Util::extractAccountOr404($request, $match);
        // Extract accept type
        $acceptType = array_key_exists('Accept', $request->HEADERS) ? $request->HEADERS['Accept'] : null;
        if ($acceptType === 'application/ovpn') {
            // return self::downloadOvpnFile($account);
            $ovpnStr = self::generateClientOvpn($account);
            return new Pluf_HTTP_Response_PlainText($ovpnStr, 'application/ovpn');
        }
        $param = array(
            'model' => 'Vpn_Account'
        );
        $match['modelId'] = $account->id;
        return $this->getObject($request, $match, $param);
    }

//     /**
//      * Download the ovpn file related to give vpn-account
//      *
//      * @param Pluf_HTTP_Request $request
//      * @param array $match
//      * @return Pluf_HTTP_Response_File
//      */
//     private static function downloadOvpnFile($account)
//     {
//         // TODO: genrate ovpn file and return it
//         $filePath = Pluf_Tenant::storagePath() . '/vpn/sample-ovpn';
//         $response = new Pluf_HTTP_Response_File($filePath, 'application/ovpn');
//         // $response->headers['Content-Disposition'] = sprintf('attachment; filename="%s"', $content->file_name);
//         return $response;
//     }
    
    private static function generateClientOvpn($account){
        $filePath = Pluf_Tenant::storagePath() . '/vpn/client-template.ovpn'; 
        $template = Vpn_Util::getFileContent($filePath);
        $context = [
            'key' => Vpn_Keypair::getOne($account)->private_pem,
            'cert' => Vpn_Cert::getOneValidCert($account)->pem,
            'ca' => Vpn_Cert::getDefaultCa()->pem
        ];
        // Replace account specific data
        $m = new Mustache_Engine();
        $ovpnStr = $m->render($template, $context);
        return $ovpnStr;
    }
}
