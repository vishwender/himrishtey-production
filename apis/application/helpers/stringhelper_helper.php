<?php
function replaceNullWithEmptyString($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = replaceNullWithEmptyString($value);  // Recursively handle array values
        }
    } elseif (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = replaceNullWithEmptyString($value);  // Recursively handle object properties
        }
    } elseif (is_null($data)) {
        return "";  // Replace null with empty string
    }
    return $data;
}
?>