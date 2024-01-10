<?php

namespace KiniCRM\ValueObjects\Enum;

enum ObjectScope: string {
    case Contact = "Contact";
    case Organisation = "Organisation";
    case Task = "Task";
    case User = "User";
    case Department = "Department";
}
