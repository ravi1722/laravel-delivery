<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        //Server கொடுத்த Content-Type-ஐ மட்டும் follow செய். நீயாக guess செய்யாதே. X-Content-Type-Options: nosniff prevents browsers from MIME-sniffing the response content type.
        $response->headers->set('X-Frame-Options', 'DENY');
        //Clickjacking prevent. Attacker தனது website-ல் invisible iframe-ல் உங்கள் website-ஐ load செய்கிறான்.ஆனால் underlying-ல் உங்கள் website-ல் ஒரு sensitive button click ஆகலாம்.
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        // 1 → Enable, mode=block → suspicious content detected என்றால் page-ஐ block
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        //User வேறு website-க்கு செல்கிறார், Browser சில நேரங்களில் previous URL-ஐ Referer header-ல் அனுப்பும். URL-ல் sensitive information இருந்தால் அது leak ஆகலாம்..
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        //இந்த website camera, microphone, geolocation features-ஐ பயன்படுத்தக்கூடாது. உங்கள் application-க்கு location தேவையில்லை என்றால்:geolocation=() பயன்படுத்தலாம்.

        if (app()->isProduction()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }
        //HSTS - Strict-Transport-Security => உங்கள் website https://.. என்று HTTPS-ல் இருக்கிறது. User: http://.. என்று type செய்தால், browser HTTP-ல் செல்லாமல் HTTPS-ஐ பயன்படுத்த வேண்டும் என்று browser-க்கு சொல்லுகிறது.

        // Remove server info
        $response->headers->remove('X-Powered-By');
        // Attacker:இந்த server PHP பயன்படுத்துகிறது என்று தெரிந்து கொள்ளலாம். அதனால் unnecessary technology information expose செய்யாமல் remove செய்கிறோம்.
        $response->headers->remove('Server');
        //Header remove செய்வது server-ஐ secure ஆக்கிவிடாது. இது attacker-க்கு கிடைக்கும் information-ஐ மட்டும் குறைக்கும்.

        return $response;
    }
}
