<?php

/*
 * This file is part of the arkste/hetzner-cloud-client-php package.
 *
 * (c) Arkadius Stefanski <arkste@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HetznerCloud\HttpClient\Plugin;

use HetznerCloud\Exception;
use HetznerCloud\HttpClient\Message\ResponseMediator;
use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * ExceptionThrower.
 *
 * @author Arkadius Stefanski <arkste@gmail.com>
 */
class ExceptionThrower implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        return $next($request)->then(
            function (ResponseInterface $response) {
                $status = $response->getStatusCode();
                if ($status < 400 || $status > 600) {
                    return $response;
                }

                $remaining = ResponseMediator::getHeader($response, 'RateLimit-Remaining');
                if (null != $remaining && 1 > $remaining) {
                    $limit = ResponseMediator::getHeader($response, 'RateLimit-Limit');
                    $reset = ResponseMediator::getHeader($response, 'RateLimit-Reset');

                    throw new Exception\RateLimitExceededException($limit, $reset);
                }

                $content = ResponseMediator::getContent($response);
                if (!empty($content['code']) && !empty($content['message'])) {
                    $this->handleError($content['code'], $content['message']);
                }

                throw new Exception\RuntimeException();
            }
        );
    }

    private function handleError($code, $message)
    {
        switch ($code) {
            case 'forbidden':
                throw new Exception\ForbiddenException($message);

            case 'invalid_input':
                throw new Exception\InvalidInputException($message);

            case 'json_error':
                throw new Exception\JsonErrorException($message);

            case 'locked':
                throw new Exception\LockedException($message);

            case 'not_found':
                throw new Exception\NotFoundException($message);

            case 'resource_limit_exceeded':
                throw new Exception\ResourceLimitExceededException($message);

            case 'resource_unavailable':
                throw new Exception\ResourceUnavailableException($message);

            case 'service_error':
                throw new Exception\ServiceErrorException($message);

            case 'uniqueness_error':
                throw new Exception\UniquenessErrorException($message);

            case 'protected':
                throw new Exception\ProtectedException($message);

            case 'maintenance':
                throw new Exception\MaintenanceException($message);

            default:
                throw new Exception\RuntimeException();
        }
    }
}
