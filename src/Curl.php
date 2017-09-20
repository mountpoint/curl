<?php

namespace Mountpoint\Curl;

class Curl
{
    /**
     * @var resource Curl resource
     */
    protected $ch;

    /**
     * @var mixed Output
     */
    protected $output;

    /**
     * @var integer Status code
     */
    protected $statusCode;

    /**
     * Curl initialization
     *
     * @param $url
     *
     * @throws \Exception
     */
    public function __construct($url)
    {
        if (empty($url)) {
            throw new \Exception('Url cannot be empty');
        }

        $this->ch = curl_init($url);
    }

    /**
     * Add curl option
     *
     * @param $option
     * @param $value
     *
     * @return $this
     */
    public function addOption($option, $value)
    {
        curl_setopt($this->ch, $option, $value);

        return $this;
    }

    /**
     * Set post data
     *
     * @param array $fields
     *
     * @return $this
     */
    public function postFields(array $fields)
    {
        $this->addOption(CURLOPT_POSTFIELDS, http_build_query($fields));

        return $this;
    }

    /**
     * Execute curl
     *
     * @return $this
     * @throws \ErrorException
     */
    public function exec()
    {
        $this->setOutput(curl_exec($this->ch));
        $this->setStatusCode(curl_getinfo($this->ch, CURLINFO_HTTP_CODE));

        if (curl_errno($this->ch)) {
            throw new \ErrorException(curl_error($this->ch));
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get curl_exec result
     * Returns true on exec() success or false on failure. However, if the CURLOPT_RETURNTRANSFER
     * option is set, it will return the result on success, false on failure.
     *
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param $output
     *
     * @return $this
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Close curl resource to free up system resources
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }
}
