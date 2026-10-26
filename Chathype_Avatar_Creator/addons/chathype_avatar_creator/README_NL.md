# Chathype Avatar Creator — CodyChat 11

Deze addon gebruikt de native CodyChat 11-addonstructuur:
- `system/install.php`: registreert de addon in Admin → Addons.
- `system/config.php`: beheerpagina met aan/uit-schakelaar, gebaseerd op het werkende patroon van Chathype Avatar Kiezer.
- `system/action.php`: slaat de beheerinstelling op in `boom_addons.custom1`.
- `files/avatar_creator.php`: editor voor ingelogde leden.
- `system/save_avatar.php`: valideert de PNG, slaat deze op in `avatar/` en koppelt die aan `boom_users.user_tumb`.

## Installatie

1. Maak een back-up van je chatbestanden en database.
2. Kopieer de map `Chathype_Avatar_Creator/addons/chathype_avatar_creator` uit deze repository naar de `addons/chathype_avatar_creator`-map in de CodyChat-root.
3. Installeer de addon via Admin → Addons.
4. Open Admin → Addons → Chathype Avatar Creator en zet de addon aan.
5. Volg `profile_menu_insert.php.txt` om de oude Avatar Kiezer-profielknop te vervangen door de nieuwe Avatar Creator-knop. Laat de rest van `system/box/profile.php` intact.
6. Controleer dat PHP GD actief is en dat de CodyChat-map `avatar/` schrijfbaar is.

## Let op

De oude Avatar Kiezer en de nieuwe Avatar Creator zijn afzonderlijke addons. Gebruik in het profiel de Creator-settings en Creator-endpoint; laat niet per ongeluk de oude `chathype_avatar_chooser`-include of picker-JavaScript staan.

De code is bedoeld voor PHP 7.4+. De installatie is niet live getest op jouw Hostinger/XAMPP-server; test op een back-up voordat je dit op de livechat toepast.
