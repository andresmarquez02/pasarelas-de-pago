<?php

namespace App\Services;

use App\Traits\ConsumerExternalServices;
use Illuminate\Http\Request;

class CurrencyConversionService {
    use ConsumerExternalServices;

    protected $baseUri;

    protected $apiKey;

    public function __construct()
    {
        $this->baseUri = config("services.currency_conversion.base_uri");
        $this->apiKey = config("services.currency_conversion.api_key");
    }

    public function resolveAuthorizacion(&$queryParams,&$formParams,&$headers)
    {
        $queryParams['apiKey'] = $this->resolveAccessToken();
    }

    public function resolveAccessToken(){
        return $this->apiKey;
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function convertCurrency($from, $to){
        $response = $this->makeRequest(
            'GET',
            '/api/v7/convert',
            [
                'q' => "{$from}_{$to}",
                "compact" => "ultra",
            ]
        );
        return $response->{strtoupper("{$from}_{$to}")};
    }
}
