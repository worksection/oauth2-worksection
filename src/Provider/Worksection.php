<?php

namespace Worksection\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class Worksection extends AbstractProvider
{
    use BearerAuthorizationTrait;


	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		if (defined('LOC')) {
			$result = 'https://promo-local.worksection.com/';
		} elseif (defined('DEV')) {
			$result = 'https://promo-dev.worksection.com/';
		} else {
			$result = 'https://worksection.com/';
		}

		return $result;
	}


    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getUrl() . 'oauth2/authorize';
    }


    /**
     * Get access token url to retrieve token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
	{
        return $this->getUrl() . 'oauth2/token';
    }


    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getUrl() . 'oauth2/resource';
    }


    /**
     * Get the default scopes used by this provider.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }


    /**
     * Check a provider response for errors.
     *
     * @param  ResponseInterface $response
     * @param  array|string $data
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (isset($data['error'])) {
            if (isset($data['error_description'])) {
                $message = $data['error_description'];
            } else {
                $message = $data['errorDescription'];
            }
            throw new IdentityProviderException($message, $response->getStatusCode(), $response);
        }
    }


    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new WorksectionResourceOwner($response);
    }


    /**
     * Returns a prepared request for requesting an access token.
     *
     * @param array $params
     * @return RequestInterface
     */
    protected function getAccessTokenRequest(array $params)
    {
        $request = parent::getAccessTokenRequest($params);
        $uri = $request->getUri()
            ->withUserInfo($this->clientId, $this->clientSecret);
        return $request->withUri($uri);
    }
}