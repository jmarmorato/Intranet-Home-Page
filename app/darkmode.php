<?php

/*
*   This function returns "dark" if darkmode is requested, and "light" otherwise
*/

function dol() {
  if (DARKMODE) {
    return "dark";
  } else {
    return "light";
  }
}

/*
*   This function returns "dark" if darkmode is requested, and an empty string
*   otherwise
*/

function don() {
  if (DARKMODE) {
    return "dark";
  } else {
    return "";
  }
}

function sbdol() {
  if (DARKMODE) {
    return "light";
  } else {
    return "dark";
  }
}

function card_dm() {
  if (DARKMODE) {
    return "bg-dark text-white";
  }
}

function link_dm_andqs() {
  if (DARKMODE) {
    return "&style=dark";
  }
}

?>
