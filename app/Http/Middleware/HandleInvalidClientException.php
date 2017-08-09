<?php

namespace App\Http\Middleware;


class HandleInvalidClientException
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, $next)
    {
        $response = $next($request);

        /** @var \Symfony\Component\HttpFoundation\Response $response */
        if ($response->getStatusCode() == 401 && $request->is('*oauth/token*')) {
            $data = json_decode($response->getContent(), true);
            if (array_get($data, 'error') === 'invalid_client' && $response->headers->has('WWW-Authenticate')) {
                $response->headers->remove('WWW-Authenticate');
            }
        }

        return $response;
    }
}
