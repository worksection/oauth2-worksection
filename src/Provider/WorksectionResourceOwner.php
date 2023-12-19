<?php

namespace Worksection\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class WorksectionResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;
    
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;


    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }


    /**
     * Get resource owner id
     *
     * @return string
     */
    public function getId()
    {
		return $this->response['id'] ?? '';
    }


    /**
     * Get resource owner name
     *
     * @return string
     */
    public function getName()
    {
        $firstName = $this->response['first_name'] ?: '';
        $lastName = $this->response['last_name'] ?: '';
        return ($firstName . ($firstName && $lastName ? ' ' : '') . $lastName) ?: '';
    }

	/**
	 * Get resource owner id
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->response['email'] ?: '';
	}


	/**
	 * @return string
	 */
	public function getAccountUrl()
	{
		return $this->response['account_url'] ?: '';
	}


    /**
     * Get resource owner username
     *
     * @return null
     */
    public function getUsername()
    {
        return null;
    }


    /**
     * Get resource owner location
     *
     * @return null
     */
    public function getLocation()
    {
        return null;
    }


    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}