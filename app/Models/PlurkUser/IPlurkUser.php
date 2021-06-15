<?php

namespace App\Models\PlurkUser;

interface IPlurkUser
{
    function getUserToken();
    function getUserTokenSecret();
    function isAuthorized();
    function setOAuthUserToken(string $token, string $tokenSecret);
    function setPermanentUserToken(string $token, string $tokenSecret);
    function clearUserToken();
}
