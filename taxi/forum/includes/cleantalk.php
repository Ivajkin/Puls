<?php

/*

  Process phpBB 3 posts to detect SPAM and offtopic.
  Copyright (C) 2011 Denis Shagimuratov shagimuratov@cleantalk.ru

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

 */

if (!defined('IN_PHPBB')) {
    exit;
}

function ct_error_mail($response) {

    global $config, $user, $phpbb_root_path, $phpEx;

    if (!function_exists('phpbb_mail')) {
        include($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
    }

    $headers[] = 'Reply-To: ' . $config['board_email'];
    $headers[] = 'Return-Path: <' . $config['board_email'] . '>';
    $headers[] = 'Sender: <' . $config['board_email'] . '>';
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'X-Mailer: phpBB3';
    $headers[] = 'X-MimeOLE: phpBB3';
    $headers[] = 'X-phpBB-Origin: phpbb://' . str_replace(array('http://', 'https://'), array('', ''), generate_board_url());
    $headers[] = 'Content-Type: text/plain; charset=UTF-8'; // format=flowed
    $headers[] = 'Content-Transfer-Encoding: 8bit'; // 7bit

    $err_msg = '';
    $err_str = sprintf($user->lang['CT_ERROR'], $response->errstr, $config['ct_server_url']);
    $result = @phpbb_mail($config['board_email'], $config['ct_server_url'], $err_str, $headers, "\n", $err_msg);

    if (!$result) {
        ct_error_mail($err_msg);
        return false;
    }

    return 1;
}


/*
  Get user IP
 */

function ct_session_ip($data_ip) {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $forwarded_for = htmlspecialchars((string) $_SERVER['HTTP_X_FORWARDED_FOR']);

    // 127.0.0.1 usually used at reverse proxy
    $session_ip = ($data_ip == '127.0.0.1' && !empty($forwarded_for)) ? $forwarded_for : $data_ip;

    return $session_ip;
}

?>
