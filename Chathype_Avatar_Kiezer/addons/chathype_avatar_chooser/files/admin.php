<?php
/*
 * CodyChat addon admin-panel entry point.
 * Some CodyChat v11 addon loaders look for the admin view inside files/.
 * Keep the implementation in the addon root so both entry points share it.
 */
require __DIR__ . '/../admin.php';
