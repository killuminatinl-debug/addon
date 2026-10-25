# CodyChat 11 — Avatar Frame BLK repair

This repository contains a PHP 8-compatible repair/rebuild of the Avatar Frame BLK addon for CodyChat 11.

## Status and limitations

- Source targets PHP 8.x and CodyChat's addon bootstrap (`system/config_addons.php`).
- The addon lets admins upload/manage frame images and lets members select a frame.
- Frame files are not bundled in this repository; add PNG, JPG, GIF, or WebP images through the admin panel after installation.
- A live test against your exact CodyChat 11 database/build is still required before claiming production-ready compatibility. Wallet charging is deliberately not guessed because CodyChat installations differ in wallet columns and transaction helpers.

## Install

1. Back up your chat and database.
2. Copy `Avatar_Frame-BLK/` into your CodyChat `addons/` directory.
3. Ensure `addons/Avatar_Frame-BLK/files/frames/` is writable by PHP.
4. Run the install script from the addon/admin installation mechanism.
5. Open the addon admin page, upload a frame, then test selecting/removing it as a normal member.

See `Avatar_Frame-BLK/INSTALL_CODYCHAT11_NL.txt` for the Dutch checklist.