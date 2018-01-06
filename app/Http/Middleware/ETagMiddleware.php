<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ETagMiddleware {
    
    /**
     * Implement HTTP Etag support
     * speed up response transfer time
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    
    public function handle(Request $request, Closure $next) {
        
        // Get response
        /** @var Response $response */
        $response = $next($request);
        
        // If this was a GET request...
        if ($request->isMethod('get')) {
            
            // Generate Etag
            $etag = md5($response->getContent());
            $requestEtag = str_replace('"', '', $request->getETags());
            
            // Check to see if Etag has changed
            if (!empty($requestEtag[0]) AND $requestEtag[0] == $etag) {
                $response->setNotModified();
            }
            
            // Set Cache Header
            $response->setCache(['etag' => $etag, 'max_age' => 86400, 'private' => true]);
        }
        
        // Send response
        return $response;
    }
    
}