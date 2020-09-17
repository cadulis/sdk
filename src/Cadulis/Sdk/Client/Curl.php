<?php

namespace Cadulis\Sdk\Client;

class Curl
{

    use \Bixev\LightLogger\LoggerTrait;

    // cURL hex representation of version 7.30.0
    const NO_QUIRK_VERSION = 0x071E00;

    const MAX_REDIRECT = 10;

    const METHOD_POST   = 'POST';
    const METHOD_GET    = 'GET';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    protected $_knownMethods = [
        self::METHOD_POST,
        self::METHOD_GET,
        self::METHOD_PUT,
        self::METHOD_DELETE,
    ];

    private static $CONNECTION_ESTABLISHED_HEADERS = [
        "HTTP/1.0 200 Connection established\r\n\r\n",
        "HTTP/1.1 200 Connection established\r\n\r\n",
    ];

    /**
     * @var resource
     */
    protected $_curlHandler;

    protected $_url;
    protected $_method        = self::METHOD_GET;
    protected $_headers       = [];
    protected $_postFields    = [];
    protected $_httpResponseCode;
    protected $_allowLocalIP  = false;
    protected $_redirectCount = 0;
    protected $_host;
    protected $_port;
    protected $_ip;
    protected $_responseHeaders;
    protected $_responseBody;

    /**
     * @param                                         $url
     * @param string                                  $method
     * @param array                                   $headers
     * @param array                                   $postFields
     * @param \Bixev\LightLogger\LoggerInterface|null $logger
     * @param bool                                    $allowLocalIp
     */
    public function __construct(
        $url,
        $method = self::METHOD_GET,
        array $headers = [],
        array $postFields = [],
        \Bixev\LightLogger\LoggerInterface $logger = null,
        bool $allowLocalIp = false
    ) {
        $this->_logger = $logger;
        $this->_allowLocalIP = $allowLocalIp;
        $this->setVars($url, $method, $headers, $postFields);
    }

    protected function setVars($url, $method = self::METHOD_GET, array $headers = [], array $postFields = [])
    {

        $url = trim($url);
        if ($url == '') {
            throw new Exception('url cannot be empty');
        }

        /* security part #1 from https://github.com/xavierleune/demo-forum-php/blob/master/src/Extractor/UrlCrawler6.php*/
        $this->_host = parse_url($url, PHP_URL_HOST);
        $url = str_replace($this->_host, idn_to_ascii($this->_host, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46), $url);
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array($scheme, ['http', 'https'])) {
            throw new Exception('Wrong URL (allowed protocols are : http/https)');
        }
        // Looks like an ip
        if (
            preg_match('/^((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?1)){3}\z/', $this->_host)
            ||
            preg_match(
                '/^(((?=(?>.*?(::))(?!.+\3)))\3?|([\dA-F]{1,4}(\3|:(?!$)|$)|\2))(?4){5}((?4){2}|((2[0-4]|1\d|[1-9])?\d|25[0-5])(\.(?7)){3})\z/i',
                $this->_host
            )
        ) {
            if (!$this->_allowLocalIP &&
                filter_var($this->_host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ===
                false
            ) {
                throw new Exception('Wrong ip: probably local !');
            }
        } else {
            // Looks like an hostname
            if (filter_var($this->_host, FILTER_VALIDATE_DOMAIN) === false) {
                throw new Exception('Wrong host');
            }
            $this->_ip = gethostbyname($this->_host);
            if (!$this->_allowLocalIP &&
                filter_var($this->_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ===
                false
            ) {
                throw new Exception('Domains resolves to local IP');
            }
            // Force IP
            $this->_port = ['http' => 80, 'https' => 443][$scheme]; // This syntax is ugly, but I actually like it
            $this->_port = parse_url($url, PHP_URL_PORT) ?? $this->_port; // If there is an explicit port, use it
        }
        /* end security part #1*/

        $method = trim($method);
        if ($method == '') {
            throw new Exception('method cannot be empty');
        }
        if (array_search($method, $this->_knownMethods) === false) {
            throw new Exception('Unknown method : "' . $method . '"');
        }

        $this->_url = $url;
        $this->_method = $method;
        $this->_headers = $headers;
        $this->_postFields = $postFields;
    }

    protected function init($ch = null)
    {
        if ($ch !== null) {
            $this->_curlHandler = $ch;
        } else {
            $this->_curlHandler = curl_init();
        }
    }

    /**
     * @return resource
     */
    public function getCurlHandler()
    {
        return $this->_curlHandler;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->_responseHeaders;
    }

    public function prepare($ch = null)
    {

        $this->init($ch);

        curl_setopt($this->_curlHandler, CURLOPT_CUSTOMREQUEST, $this->_method);

        // we need to manually follow redirections to check host each time
        curl_setopt($this->_curlHandler, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->_curlHandler, CURLOPT_SSL_VERIFYPEER, true);
        // 1 is CURL_SSLVERSION_TLSv1, which is not always defined in PHP.
        curl_setopt($this->_curlHandler, CURLOPT_SSLVERSION, 1);
        curl_setopt($this->_curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_curlHandler, CURLOPT_HEADER, true);
        curl_setopt($this->_curlHandler, CURLOPT_ENCODING, 'gzip,deflate');

        $postFields = http_build_query($this->_postFields, '', '&');
        $url = $this->_url;
        if ($this->_method == static::METHOD_GET) {
            if ($postFields != '') {
                $url .= strpos($this->_url, '?') === false ? '?' : '&';
                $url .= $postFields;
            }
        } else {
            curl_setopt($this->_curlHandler, CURLOPT_POSTFIELDS, $postFields);
        }
        curl_setopt($this->_curlHandler, CURLOPT_URL, $url);
        $this->_headers[] = 'app-platform: api-client';
        curl_setopt($this->_curlHandler, CURLOPT_HTTPHEADER, $this->_headers);

        $this->log(
            [
                'cURL request' => [
                    'url'     => $url,
                    'method'  => $this->_method,
                    'headers' => $this->_headers,
                    'body'    => $this->_postFields,
                ],
            ]
        );
    }

    public function processResponse($responseBody)
    {
        $responseArray = json_decode($responseBody, true);
        if ($responseArray !== null) {
            return $responseArray;
        }

        return $responseBody;
    }

    /**
     * @param bool $process with json_decode post-processing
     *
     * @return mixed
     */
    public function exec($process = true)
    {

        $this->prepare();

        return $this->process($process);
    }

    protected function curlExec()
    {
        $response = curl_exec($this->_curlHandler);
        $this->_httpResponseCode = curl_getinfo($this->_curlHandler, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error = curl_error($this->_curlHandler);
            $code = curl_errno($this->_curlHandler);
            throw new Exception($error, $code);
        }

        $headerSize = curl_getinfo($this->_curlHandler, CURLINFO_HEADER_SIZE);
        list($this->_responseHeaders, $this->_responseBody) = $this->parseHttpResponse($response, $headerSize);
        $this->_httpResponseCode = curl_getinfo($this->_curlHandler, CURLINFO_HTTP_CODE);

        return $response;
    }

    /**
     * @param bool $process with json_decode post-processing
     *
     * @return mixed
     */
    public function process($process = true)
    {
        $this->curlExec();
        $callCount = 1;

        /* security part #2 from from https://github.com/xavierleune/demo-forum-php/blob/master/src/Extractor/UrlCrawler6.php*/
        while (
            $this->_httpResponseCode > 300
            && $this->_httpResponseCode < 310
            && $this->_httpResponseCode !== 304
            && $this->_redirectCount <= self::MAX_REDIRECT
        ) {
            // This is a redirect, we want to check everything
            $this->_redirectCount++;
            curl_setopt(
                $this->_curlHandler,
                CURLOPT_RESOLVE,
                [
                    sprintf('%s:%d:%s', $this->_host, $this->_port, $this->_ip) // HOST : PORT : IP
                ]
            );
            $previousUrl = $this->_url;
            $this->setVars(
                curl_getinfo($this->_curlHandler, CURLINFO_REDIRECT_URL),
                $this->_method,
                $this->_headers,
                $this->_postFields
            );
            $this->log(
                [
                    'httpResponseCode' => $this->_httpResponseCode,
                    'url'              => $this->_url,
                ]
            );
            if ($previousUrl == $this->_url) {
                usleep(200000);
            }
            $this->curlExec();
            $callCount++;
        }
        /* end of security part #2 */

        curl_close($this->_curlHandler);

        $this->log(
            [
                'cURL response' => [
                    'code'    => $this->_httpResponseCode,
                    'headers' => $this->_responseHeaders,
                    'body'    => $this->_responseBody,
                ],
            ]
        );

        if ($this->_httpResponseCode >= 300) {
            $responseArray = json_decode($this->_responseBody, true);
            if ($responseArray !== null) {
                $errMsg = 'Error while executing request : ';
                if (isset($responseArray['message'])) {
                    $errMsg .= json_encode($responseArray['message']);
                }
                if (isset($responseArray['error'])) {
                    $errMsg .= json_encode($responseArray['error']);
                }
                if (isset($responseArray['details'])) {
                    $errMsg .= ' (' . json_encode($responseArray['details']) . ')';
                }
            } else {
                $errMsg = 'Error after ' . $callCount . ' call(s) while executing request : ' . $this->_responseBody;
            }
            throw new Exception($errMsg, $this->_httpResponseCode);
        }

        if ($process) {
            $this->_responseBody = $this->processResponse($this->_responseBody);
        }

        return $this->_responseBody;
    }

    /**
     * Used by the IO lib and also the batch processing.
     *
     * @param $respData
     * @param $headerSize
     *
     * @return array
     */
    public function parseHttpResponse($respData, $headerSize)
    {
        // check proxy header
        foreach (self::$CONNECTION_ESTABLISHED_HEADERS as $established_header) {
            if (stripos($respData, $established_header) !== false) {
                // existed, remove it
                $respData = str_ireplace($established_header, '', $respData);
                // Subtract the proxy header size unless the cURL bug prior to 7.30.0
                // is present which prevented the proxy header size from being taken into
                // account.
                if (!$this->needsQuirk()) {
                    $headerSize -= strlen($established_header);
                }
                break;
            }
        }

        if ($headerSize) {
            $responseBody = substr($respData, $headerSize);
            $responseHeaders = substr($respData, 0, $headerSize);
        } else {
            $responseSegments = explode("\r\n\r\n", $respData, 2);
            $responseHeaders = $responseSegments[0];
            $responseBody = isset($responseSegments[1])
                ? $responseSegments[1]
                :
                null;
        }

        $responseHeaders = $this->getHttpResponseHeaders($responseHeaders);

        return [$responseHeaders, $responseBody];
    }

    /**
     * Parse out headers from raw headers
     *
     * @param mixed array or string
     *
     * @return array
     */
    public function getHttpResponseHeaders($rawHeaders)
    {
        if (is_array($rawHeaders)) {
            return $this->parseArrayHeaders($rawHeaders);
        } else {
            return $this->parseStringHeaders($rawHeaders);
        }
    }

    private function parseStringHeaders($rawHeaders)
    {
        $headers = [];
        $responseHeaderLines = explode("\r\n", $rawHeaders);
        foreach ($responseHeaderLines as $headerLine) {
            if ($headerLine && strpos($headerLine, ':') !== false) {
                list($header, $value) = explode(': ', $headerLine, 2);
                $header = strtolower($header);
                if (isset($headers[$header])) {
                    $headers[$header] .= "\n" . $value;
                } else {
                    $headers[$header] = $value;
                }
            }
        }

        return $headers;
    }

    private function parseArrayHeaders($rawHeaders)
    {
        $header_count = count($rawHeaders);
        $headers = [];

        for ($i = 0; $i < $header_count; $i++) {
            $header = $rawHeaders[$i];
            // Times will have colons in - so we just want the first match.
            $header_parts = explode(': ', $header, 2);
            if (count($header_parts) == 2) {
                $headers[strtolower($header_parts[0])] = $header_parts[1];
            }
        }

        return $headers;
    }

    /**
     * Test for the presence of a cURL header processing bug
     *
     * {@inheritDoc}
     *
     * @return boolean
     */
    protected function needsQuirk()
    {
        $ver = curl_version();
        $versionNum = $ver['version_number'];

        return $versionNum < static::NO_QUIRK_VERSION;
    }

    public function getHttpResponseCode()
    {
        return $this->_httpResponseCode;
    }
}
