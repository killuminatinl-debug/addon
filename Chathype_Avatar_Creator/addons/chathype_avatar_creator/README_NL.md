# Chathype Avatar Creator — CodyChat 11

Deze addon gebruikt de native CodyChat 11-addonstructuur:
- `system/install.php`: registreert de addon in Admin → Addons.
- `system/config.php`: beheerpagina met aan/uit-schakelaar.
- `system/action.php`: slaat de beheerinstelling veilig op in `boom_addons.custom1`.
- `files/avatar_creator.php`: avatar-editor voor ingelogde leden.
- `system/save_avatar.php`: controleert en slaat de PNG op en koppelt deze aan `boom_users.user_tumb`.

## Installatie
1. Maak een back-up van bestanden en database.
2. Upload de map `addons/chathype_avatar_creator` naar de `addons`-map van CodyChat.
3. Gebruik Admin → Addons om de addon op de normale CodyChat-manier te installeren.
4. Open Admin → Addons → Chathype Avatar Creator en schakel hem in.
5. Voeg de knopintegratie uit `profile_menu_insert.php.txt` toe aan `system/box/profile.php`.
6. Zorg dat de CodyChat-map `avatar/` schrijfbaar is voor PHP. PHP GD is vereist.

De bronbestanden zijn geschreven voor PHP 7.4+. Dit is niet live getest op jouw Hostinger/XAMPP-installatie; controleer eerst op een back-up/testinstallatie.
