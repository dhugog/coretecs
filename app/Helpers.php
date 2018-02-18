<?php

/**
 * Authorizes the user by their permission level
 * 
 * If their permission level is less then the specified, returns false, else, returns true
 * 
 * @param int $level
 * @return boolean
 */
function authorized($level) {
    if(\Auth::user()->permission < $level) {
        return false;
    } else {
        return true;
    }
}