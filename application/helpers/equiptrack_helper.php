<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('et_csrf_field')) {
    function et_csrf_field()
    {
        $CI =& get_instance();
        if (!$CI->config->item('csrf_protection')) {
            return '';
        }
        $name = $CI->security->get_csrf_token_name();
        $hash = $CI->security->get_csrf_hash();
        return '<input type="hidden" name="'.html_escape($name).'" value="'.html_escape($hash).'">';
    }
}

if (!function_exists('et_require_post')) {
    function et_require_post()
    {
        $CI =& get_instance();
        if (strtoupper($CI->input->method(TRUE)) !== 'POST') {
            show_error('Method Not Allowed', 405);
        }
    }
}

if (!function_exists('et_media_url')) {
    /**
     * Build a safe URL for a server-managed upload filename.
     * basename() prevents legacy DB values from traversing outside the media folder.
     */
    function et_media_url($folder, $filename, $fallback)
    {
        $name = basename(trim((string)$filename));
        if ($name === '' || $name === '.' || $name === '..') {
            return base_url(ltrim((string)$fallback, '/'));
        }
        return base_url(trim((string)$folder, '/').'/'.rawurlencode($name));
    }
}


if (!function_exists('et_media_exists')) {
    /** Return TRUE only for a real file inside an allowed project media folder. */
    function et_media_exists($folder, $filename)
    {
        $folder = trim((string)$folder, '/\\');
        $name = basename(trim((string)$filename));
        if ($folder === '' || $name === '' || $name === '.' || $name === '..') return FALSE;
        return is_file(FCPATH.$folder.DIRECTORY_SEPARATOR.$name);
    }
}

if (!function_exists('et_writable_media_dir')) {
    /**
     * Return an absolute writable project-media directory with a trailing
     * directory separator. Uses FCPATH so the same code works on macOS,
     * Windows and Linux without hard-coded XAMPP paths.
     */
    function et_writable_media_dir($folder)
    {
        $folder = trim((string)$folder, '/\\');
        if ($folder === '' || strpos($folder, '..') !== FALSE) {
            return FALSE;
        }

        $path = rtrim(FCPATH, '/\\').DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            @mkdir($path, 0775, TRUE);
        }

        // chmod is useful on macOS/Linux and harmless when unsupported.
        // Do not use OS-specific absolute paths or ownership assumptions.
        if (is_dir($path) && !is_writable($path) && DIRECTORY_SEPARATOR === '/') {
            @chmod($path, 0775);
        }

        return (is_dir($path) && is_writable($path)) ? $path : FALSE;
    }
}

