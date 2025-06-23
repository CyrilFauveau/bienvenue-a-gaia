<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

class ParametersService
{
    /**
     * @var array|ParameterBag
     */
    private $paramsInRequest;

    /**
     * @var ParameterBag
     */
    private $paramsInQuery;

    /**
     * @var FileBag
     */
    private $files;

    public function __construct(RequestStack $requestStack)
    {
        $currentRequest = $requestStack->getCurrentRequest();
        $request = $currentRequest->request;
        $jsonDecoded = json_decode($currentRequest->getContent(), true);

        $this->paramsInRequest = $jsonDecoded ? $jsonDecoded : ($request ? $request : []);
        $this->paramsInQuery = $currentRequest->query;
        $this->files = $currentRequest->files;
    }

    /**
     * Get a parameter in the body
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getParameterInRequest(string $key, $defaultValue = null)
    {
        if (is_array($this->paramsInRequest) && array_key_exists($key, $this->paramsInRequest)) {
            return $this->paramsInRequest[$key];
        } else if (is_object($this->paramsInRequest)) {
            return $this->paramsInRequest->get($key);
        } else {
            return $defaultValue;
        }
    }

    /**
     * Get a parameter in the query
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getParameterInQuery(string $key, $defaultValue = null)
    {
        return $this->paramsInQuery->get($key, $defaultValue);
    }

    /**
     * Get a file in the request
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getFile($key, $defaultValue = null)
    {
        return $this->files->get($key, $defaultValue);
    }
}
