<?php
setcookie('session','',time()-1);
header("Location: /");
