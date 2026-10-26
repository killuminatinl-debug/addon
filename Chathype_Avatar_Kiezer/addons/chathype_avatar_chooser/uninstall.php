<?php
/*
 * Compatibility entry point for CodyChat addon managers that look for
 * uninstall.php in the addon's root directory.
 * Keep the implementation in system/uninstall.php so there is only one
 * restoration routine.
 */
require_once __DIR__ . '/system/uninstall.php';
