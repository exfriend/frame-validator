<?php


namespace Exfriend\FrameValidator;


class Headers
{

    protected $headersAsString;

    /**
     * Headers constructor.
     * @param $headersAsString
     */
    public function __construct( $headersAsString )
    {
        $this->headersAsString = strtolower( $headersAsString );
    }

    public function getHeaderValues( $headerName )
    {
        $matched = preg_match_all( '~' . preg_quote( strtolower( $headerName ) ) . ': (.*?)\n~is', $this->headersAsString, $out );

        if ( $matched )
        {
            return $out[ 1 ];
        }

        return [];
    }


}