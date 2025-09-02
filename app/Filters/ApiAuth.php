<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiAuth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION') ?? $request->getHeaderLine('Authorization');
        $apiKeyHeader = null;

        if ($authHeader) {
            if (stripos($authHeader, 'Bearer ') === 0) {
                $apiKeyHeader = trim(substr($authHeader, 7));
            } else {
                $apiKeyHeader = trim($authHeader);
            }
        } else {
            $apiKeyHeader = $request->getHeaderLine('X-API-KEY');
        }

        $expected = env('API_KEY');

        if (empty($expected) || !$apiKeyHeader || $apiKeyHeader !== $expected) {
            $response = service('response');
            return $response->setStatusCode(401)
                            ->setJSON(['error' => 'Sem autorização', 'message' => 'Senha incorreta!']);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
