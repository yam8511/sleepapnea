<?php

if (isset($_COOKIE['session'])) {
    $_auth = json_decode(base64_decode($_COOKIE['session']), true);
}
