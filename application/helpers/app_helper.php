<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('set_active_link')) {
    /**
     * Sets the navigation link as 'active' if the selected link matches the current link.
     * 
     * @param string $selected_link The selected link to check.
     * @param string $current_link The current active link.
     * @return string|null Returns 'active' if the links match, otherwise null.
     */
    function set_active_link($selected_link, $current_link)
    {
        return ($selected_link === $current_link) ? 'active' : null;
    }
}

if (!function_exists('application_module')) {
    /**
     * returns application module name with proper formating
     * 
     * @param string $module module name input.
     * @return string Returns module name with string formating
     */
    function application_module(string $module): string
    {
        return ucwords(str_replace("-", " ", $module));
    }
}



function beautify_array($array, $exit = false)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    if ($exit)
        exit(0);
}

if (! function_exists('uuid_v4')) {
    /**
     * Generate a strictly standards-compliant UUID v4 (RFC 4122)
     *
     * @return string
     */
    function uuid_v4()
    {
        // Generate 16 random bytes (128 bits)
        $data = random_bytes(16);

        // Set the version to 4 (UUID v4)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);

        // Set the variant to RFC 4122
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Format as UUID (xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx)
        return sprintf(
            '%08s-%04s-%04s-%04s-%12s',
            bin2hex(substr($data, 0, 4)),
            bin2hex(substr($data, 4, 2)),
            bin2hex(substr($data, 6, 2)),
            bin2hex(substr($data, 8, 2)),
            bin2hex(substr($data, 10, 6))
        );
    }

    function setDiscountType($value)
    {
        if ($value == '2')
            return 'percentage';
        return 'no discount';
    }
}
