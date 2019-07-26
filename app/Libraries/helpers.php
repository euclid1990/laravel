<?php
/**
 * Filter special characters
 *
 * @codeCoverageIgnore
 * @param  array $input
 * @param  array $keys
 * @return array
 */
if (!function_exists('filter')) {
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
}
/**
 * Check An array is number array
 *
 * @codeCoverageIgnore
 * @param  array $array
 * @return bool
 */
if (!function_exists('is_array_number')) {
    function is_array_number($array): bool
    {
        if (is_numeric($array[0])) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Check An array is string array
 *
 * @codeCoverageIgnore
 * @param  array $array
 * @return bool
 */
if (!function_exists('is_array_string')) {
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
if (!function_exists('generate_signature')) {
    function generate_signature($key)
    {
        return md5(env('FILE_SIGNKEY', '').':'.ltrim($key, '/'));
    }
}

/**
 * Generate an string from data and template.
 * @param  mixed $data.
 * @param  string $template.
 */
if (!function_exists('generate_message')) {
    function generate_message($template, $data)
    {
        return view($template, ['data' => $data])->render();
    }
}
