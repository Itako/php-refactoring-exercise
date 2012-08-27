<?php

namespace PRE;

class Validator
{
    public static function validate($mandatary_fields, $fields)
    {
        $errors = array();

        foreach ($mandatary_fields as $field)
        {
            if ($fields[$field] == '')
            {
                $errors[] = 'The ' . $field . ' field is mandatory';
            }
        }

        return $errors;
    }

}
