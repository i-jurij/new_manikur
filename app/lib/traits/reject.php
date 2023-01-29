<?php
namespace App\Lib\Traits;

trait Reject
{
    public function reject_login() {
        $session = new \App\Lib\Session();
        $session->start();
        if( !$session->has('counter') ) { $session->set('counter', 0); }
        $counter = $session->get('counter');
        $session->set('counter', @$counter+1);

        //print $counter;
        if ($counter > 3) {
            header('HTTP/1.0 403 Forbidden');
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            echo '<html xmlns="http://www.w3.org/1999/xhtml">';
            echo '<head>';
            echo '<title>403 Forbidden</title>';
            echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
            echo '</head>';
            echo '<body>';
            echo '<h1 style="text-align:center">403 Forbidden</h1>';
            echo '<p style="background:#ccc;border:solid 1px #aaa;margin:30px auto;padding:20px;text-align:center;max-width:700px">';
            echo 'К сожалению, Вы временно заблокированы, из-за частого запроса этой страницы.<br />';
            echo 'Вам придется подождать. Через некоторое время Вы будете автоматически разблокированы.';
            echo '</p>';
            echo '</body>';
            echo '</html>';
            //$session->destroy('counter'); $session->destroyAll();
            exit;
        }
    }
}