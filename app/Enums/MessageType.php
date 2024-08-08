<?php

namespace App\Enums;

enum MessageType: string
{
    case Text = 'text';
    case Attachment = 'attachment';
}
