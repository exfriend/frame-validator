<?php


use PHPUnit\Framework\TestCase;

/**
 * @covers Validator
 */
final class ValidatorTest extends TestCase
{

    /** @test */
    public function it_checks_xframe_options()
    {
        $v = \Exfriend\FrameValidator\Validator::make( 'http://fb.com' );
        $this->assertFalse( $v->supportsIframes() );
    }

    /** @test */
    public function it_checks_content_security_policy()
    {
        $v = \Exfriend\FrameValidator\Validator::make( 'https://www.inc.com/' );
        $this->assertFalse( $v->supportsIframes() );
    }

    /** @test */
    public function it_returns_true_when_iframe_is_not_restricted()
    {
        $v = \Exfriend\FrameValidator\Validator::make( 'https://www.stumbleupon.com/' );
        $this->assertTrue( $v->supportsIframes() );
    }

    /**
     * @test
     * @expectedException \Exfriend\FrameValidator\Exceptions\TransferException
     */
    public function it_throws_exceptions_when_unable_to_download_url()
    {
        $v = \Exfriend\FrameValidator\Validator::make( 'https://gibberish.randomstuff.notvalid' . rand( 1, 99 ) . '.com' );
        $v->supportsIframes();
    }
}