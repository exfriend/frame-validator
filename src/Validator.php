<?php

namespace Exfriend\FrameValidator;

use Exfriend\FrameValidator\Exceptions\TransferException;


/**
 * Class Validator
 * @package Exfriend\FrameValidator
 */
class Validator
{
    /**
     * URL of embeddable resource
     * @var string
     */
    protected $origin;

    public $curl_options = [
        CURLOPT_TIMEOUT => 25,
        CURLOPT_CONNECTTIMEOUT => 25,
    ];

    /**
     * Validator constructor.
     * @param $origin
     */
    public function __construct( $origin )
    {
        $this->origin = $origin;
    }

    /**
     * Static constructor
     * @param string $url
     * @return static
     */
    public static function make( $url )
    {
        return new static( $url );
    }

    /**
     * Override timeouts here
     * @param array $curl_options
     * @return Validator
     */
    public function withCurlOptions( array $curl_options )
    {
        $this->curl_options = $curl_options;
        return $this;
    }

    /**
     * Run a series of checks to determine if the website supports iframes
     * @return bool
     */
    public function supportsIframes()
    {
        $headers = new Headers( $this->getHeaders( $this->origin ) );

        return $this->xFrameOptionsAllowFrames( $headers ) && $this->contentSecurityPolicyAllowsFrames( $headers );
    }

    /**
     * Make a curl request and grab the response
     * @param $url
     * @return mixed
     * @throws TransferException
     */
    protected function getHeaders( $url )
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HEADER, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_NOBODY, true );

        foreach ( $this->curl_options as $key => $value )
        {
            curl_setopt( $ch, $key, $value );
        }

        $headers = curl_exec( $ch );

        if ( $ce = curl_error( $ch ) )
        {
            throw new TransferException( $ce );
        }

        curl_close( $ch );

        return $headers;

    }

    /**
     * @param Headers $headers
     * @return bool
     */
    protected function xFrameOptionsAllowFrames( Headers $headers )
    {
        $allow = !$headers->getHeaderValues( 'x-frame-options' );
        return $allow;
    }

    /**
     * @param Headers $headers
     * @return bool
     */
    protected function contentSecurityPolicyAllowsFrames( Headers $headers )
    {
        $csp = $headers->getHeaderValues( 'content-security-policy' );
        return $csp ? ( strpos( implode( ' ', $csp ), 'frame-ancestors' ) === false ) : true;
    }

}
