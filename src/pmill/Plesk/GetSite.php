<?php
namespace pmill\Plesk;

class GetSite extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.7.0">
<site>
	<get>
		<filter>
			<name>{DOMAIN}</name>
		</filter>
		<dataset>
			<hosting/>
		</dataset>
	</get>
</site>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'domain' => null,
    ];

    /**
     * @param $xml
     * @return array
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        $result = $xml->domain->get->result;

        if ((string)$result->status == 'error') {
            throw new ApiRequestException($result);
        }
        if ((string)$result->result->status == 'error') {
            throw new ApiRequestException($result->result);
        }

        $hosting_type = (string)$result->data->gen_info->htype;

        return [
            'id' => (string)$result->id,
            'status' => (string)$result->status,
            'created' => (string)$result->data->gen_info->cr_date,
            'name' => (string)$result->data->gen_info->name,
            'ip' => (string)$result->data->gen_info->dns_ip_address,
            'hosting_type' => $hosting_type,
            'ip_address' => (string)$result->data->hosting->{$hosting_type}->ip_address,
            'www_root' => $this->findHostingProperty($result->data->hosting->{$hosting_type}, 'www_root'),
            'ftp_username' => $this->findHostingProperty($result->data->hosting->{$hosting_type}, 'ftp_login'),
            'ftp_password' => $this->findHostingProperty($result->data->hosting->{$hosting_type}, 'ftp_password'),
        ];
    }

    /**
     * @param $node
     * @param $key
     * @return null|string
     */
    protected function findHostingProperty($node, $key)
    {
        foreach ($node->children() as $property) {
            if ($property->name == $key) {
                return (string)$property->value;
            }
        }
        return null;
    }
}
