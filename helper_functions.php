<?php

/*
@function stringType: Try to identify a string type

@param string $string: An unknown GSX-related string
@return string: The identified string type
*/
function stringType($string) {

  switch ($string) {
    case preg_match('/^[G][0-9]{9}$/', $string) == true:
    return 'dispatchId';
    break;

    case preg_match('/^(?!S)[A-Z0-9]{11,12}$/', $string) == true:
    return 'serialNumber';
    break;

    case preg_match('/^([A-Z]{1,2})?(011|076|661|92(2|3))\-[0-9]{4,5}$/', $string) == true:
    return 'partNumber';
    break;

    case preg_match('/^[0-9A-Z]{3}([0-9A-Z]{1})?$/', $string) == true:
    return 'eeeCode';
    break;

    case preg_match('/^[0-9]{7}?$/', $string) == true:
    return 'escalationId';
    break;

    default:
    return 'Unknown type';
    break;
  }

}

?>
