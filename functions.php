<?php

/**
 * Validate form
 *
 * @param array $mandatary_fields
 * @param array $fields
 * @return array
 */
function validate($mandatary_fields, $fields)
{
  $errors = array();
  
  foreach ($mandatary_fields as $field)
  {
    if($fields[$field] == '')
    {
      $errors[] = 'The ' . $field . ' field is mandatory';
    } 
  }
  
  return $errors;
}