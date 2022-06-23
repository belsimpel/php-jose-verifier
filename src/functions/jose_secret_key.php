<?php

declare(strict_types=1);

namespace Facile\JoseVerifier;

use Base64Url\Base64Url;
use Jose\Component\Core\JWK;
use function preg_match;

/**
 * @internal
 */
function jose_secret_key(string $secret, ?string $alg = null): JWK
{
    if (null !== $alg && (bool) preg_match('/^A(\d{3})(?:GCM)?KW$/', $alg, $matches)) {
        return derived_key($secret, (int) $matches[1]);
    }

    if (null !== $alg && (bool) preg_match('/^A(\d{3})(?:GCM|CBC-HS(\d{3}))$/', $alg, $matches)) {
        return derived_key($secret, (int) ($matches[2] ?? $matches[1]));
    }

    $key = new JWK([
        'k' => Base64Url::encode($secret),
        'kty' => 'oct',
    ]);

    return $key;
}
