<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\HttpClient\Message;

use HetznerCloud\Exception\RateLimitExceededException;
use Psr\Http\Message\ResponseInterface;

/**
 * ResponseMediator.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ResponseMediator
{
    /**
     * @param ResponseInterface $response
     *
     * @return array|string
     */
    public static function getContent(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            $content = json_decode($body, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                return current($content);
            }
        }

        return $body;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array|null
     */
    public static function getPagination(ResponseInterface $response)
    {
        $header = self::getHeader($response, 'Link');
        $pagination = [];
        foreach (explode(',', $header) as $link) {
            preg_match('/<(.*)>; rel=(.*)/i', trim($link, ','), $match);

            if (3 === count($match)) {
                $pagination[$match[2]] = $match[1];
            }
        }

        return $pagination;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return null|string
     */
    public static function getApiLimit(ResponseInterface $response)
    {
        $remainingCalls = self::getHeader($response, 'RateLimit-Remaining');

        if (null !== $remainingCalls && 1 > $remainingCalls) {
            throw new RateLimitExceededException($remainingCalls);
        }

        return $remainingCalls;
    }

    /**
     * Get the value for a single header.
     *
     * @param ResponseInterface $response
     * @param string $name
     *
     * @return string|null
     */
    public static function getHeader(ResponseInterface $response, $name)
    {
        $headers = $response->getHeader($name);

        return array_shift($headers);
    }
}
