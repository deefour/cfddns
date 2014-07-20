<?php

/**
 * Returns the first non-null value from the set of `$values` provided
 *
 * @param  $values  array
 * @return mixed
 */
function getset($values) {
  foreach ($values as $value) {
    // if an array is passed, check the key's existence (array position 1)
    // in the source (array position 0) to avoid PHP notices.
    if (is_array($value)) {
      list($source, $key)  = $value;

      if ( ! array_key_exists($key, $source)) {
        continue;
      }

      return $source[$key];
    }

    if ($value !== null) {
      return $value;
    }
  }
}