<?php

namespace App\Enums;

enum Email: int
{
    case Mailtrap = 1;
    case Mailgun = 2;
    case Amazon_SES = 3;
}
