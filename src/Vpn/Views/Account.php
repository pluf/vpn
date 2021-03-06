<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

class Vpn_Views_Account extends Pluf_Views
{

    public function get($request, $match)
    {
        // Extract order id
        $contentType = array_key_exists('Content-Type', $request->HEADERS) ? $request->HEADERS['Content-Type'] : null;
        if ($contentType === 'application/ovpn') {
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
        $filePath = Pluf::f('upload_path') . '/' . $this->tenant->id . '/vpn/sample-ovpn';
        $response = new Pluf_HTTP_Response_File($filePath, 'application/ovpn');
        // $response->headers['Content-Disposition'] = sprintf('attachment; filename="%s"', $content->file_name);
        return $response;
    }
}
