# Chathype Avatar Kiezer — CodyChat 11

Deze addon volgt de normale CodyChat addonstructuur, zoals Avatar Frame BLK.

- `system/install.php` levert de metadata waarmee CodyChat de addon registreert en in Admin → Addons toont.
- `system/config.php` is het beheerscherm in de ingebouwde Addons-manager.
- `system/action.php` slaat de aan/uit-instelling op in `boom_addons.custom1` en verwerkt avatarselecties.
- `system/settings.php` leest de instelling.
- `files/avatar_picker.php` toont de 40 avatars.

## Installatie
1. Upload `addons/chathype_avatar_chooser` naar je CodyChat-installatie.
2. Ga naar Admin → Addons en gebruik de normale addon-installatie. De addon-slug is `chathype_avatar_chooser`.
3. Integreer de knop uit `profile_menu_insert.php.txt` in `system/box/profile.php`, of gebruik de meegeleverde profielintegratie in de ZIP.
4. Zorg dat de map `/avatar` schrijfbaar is voor PHP.
5. Open Admin → Addons → Chathype Avatar Kiezer en schakel de functie in.

De broncode is syntax-gecontroleerd, maar niet live getest op de Hostinger-installatie.
