<?php

if (!function_exists('flash')) {

    /**
     * Translate and replace by placeholder value
     *
     * @param $status
     * @param $message
     * @param null $redirectTo
     * @param string $fallback
     */
    function flash($status, $message, $redirectTo = null, $fallback = 'dashboard')
    {
        get_instance()->session->set_flashdata([
            'status' => $status, 'message' => $message,
        ]);

        if (!empty($redirectTo)) {
            $redirect = str_replace('redirect=', '', get_if_exist($_SERVER, 'REDIRECT_QUERY_STRING', ''));
            if (empty($redirect)) {
                $redirect = get_url_param('redirect');
            }

            if ($redirectTo == '_redirect') {
                redirect(if_empty($redirect, $fallback));
            } elseif ($redirectTo == '_back') {
                redirect(if_empty(get_instance()->agent->referrer(), if_empty($redirect, $fallback)));
            } else {
                redirect(empty($redirect) ? $redirectTo : $redirect);
            }
        }
    }
}