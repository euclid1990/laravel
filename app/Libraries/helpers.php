<?php

if (!function_exists('filter')) {
    /**
     * Filter special characters
     *
     * @codeCoverageIgnore
     * @param  array $input
     * @param  array $keys
     * @return array
     */
    function filter($input, $keys)
    {
        if (!empty($keys)) {
            foreach ($keys as $key) {
                if (isset($input[$key]) && !is_null($input[$key])) {
                    $input[$key] = str_replace(
                        ['\\', '%', '\'', '\n', '\r', '\\0', '\\x1a', '_'],
                        ['\\\\', '\%', '\\\'', '\\\n', '\\\r', '\\\0', '\\\x1a', '\_'],
                        $input[$key]
                    );
                }
            }
        }
        return $input;
    }
    /**
     * Check An array is number array
     *
     * @codeCoverageIgnore
     * @param  array $array
     * @return bool
     */
    function is_array_number($array): bool
    {
        if (is_numeric($array[0])) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Check An array is string array
     *
     * @codeCoverageIgnore
     * @param  array $array
     * @return bool
     */
    function is_array_string($array): bool
    {
        if (is_string($array[0])) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Generate an HTTP signature.
 * @param  string $key The resource key.
 */
if (!function_exists('generateSignature')) {
    function generateSignature($key)
    {
        return md5(env('FILE_SIGNKEY', '').':'.ltrim($key, '/'));
    }
}
