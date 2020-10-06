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
            return $this->downloadOvpnFile($account);
        } else if ($acceptType === 'application/ikv2') {
            return $this->downloadOvpnFile($account);
        }
        return $account;
    }

    public function get($request, $match)
    {
        // Extract order id
        $acceptType = array_key_exists('Accept', $request->HEADERS) ? $request->HEADERS['Accept'] : null;
        if ($acceptType === 'application/ovpn') {
            // TODO: return ovpn file
            return $this->downloadOvpnFile(new Vpn_Account($match['modelId']));
        } else {
            $param = array(
                'model' => 'Vpn_Account'
            );
            return $this->getObject($request, $match, $param);
        }
    }

    /**
     * Download the ovpn file related to give vpn-account
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File
     */
    private function downloadOvpnFile($account)
    {
        // TODO: genrate ovpn file and return it
        $filePath = Pluf_Tenant::storagePath() . '/vpn/sample-ovpn';
        $response = new Pluf_HTTP_Response_File($filePath, 'application/ovpn');
        // $response->headers['Content-Disposition'] = sprintf('attachment; filename="%s"', $content->file_name);
        return $response;
    }
}
