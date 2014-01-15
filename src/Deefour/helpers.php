<?php

/**
 * Returns the first non-null value from the set of `$values` provided
 *
 * @param  $values  array
 * @return mixed
 */
function getset($values) {
  foreach ($values as $value) {
    if ($value !== null) {
      return $value;
    }
  }
}