<?php
namespace pmill\Plesk;

class SwitchDNS extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<dns>
        <disable>
                <filter>
                        <site-id>{SITE_ID}</site-id>
                </filter>
        </disable>
</dns>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'site_id' => null,
    ];

    /**
     * @param array $config
     * @param array $params
     * @throws ApiRequestException
     */
    public function __construct($config, $params)
    {
        if (isset($params['domain'])) {
            $request = new GetSite($config, ['domain' => $params['domain']]);
            $info = $request->process();

            $params['site_id'] = $info['id'];
        }

        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        if ($xml->dns->result->status == 'error') {
            throw new ApiRequestException($xml->dns->result->errtext);
        }

        return true;
    }
}