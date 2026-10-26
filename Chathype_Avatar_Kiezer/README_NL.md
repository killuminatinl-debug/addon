# Chathype Avatar Kiezer — CodyChat 11 / PHP 7.4+

De addon biedt een avatar-editor waarin leden hun eigen avatar samenstellen en opslaan. De instelling wordt opgeslagen in `boom_addons.custom1` (1 = aan, 0 = uit).

## Belangrijke bestanden

- `addons/chathype_avatar_chooser/system/config.php` — instellingenpagina in de CodyChat Addons-manager.
- `addons/chathype_avatar_chooser/system/action.php` — opslaan van de instelling en de eigen avatar.
- `addons/chathype_avatar_chooser/system/settings.php` — leest de ingeschakelde status.
- `addons/chathype_avatar_chooser/files/avatar_picker.php` — avatar-editor.
- `addons/chathype_avatar_chooser/admin.php` — losse beheerpagina; gebruikt dezelfde `boom_addons.custom1`-instelling.
- `addons/chathype_avatar_chooser/profile_menu_insert.php.txt` — correcte profielknop- en modalintegratie.

## Installatie

1. Maak een backup van je chatbestanden en database.
2. Upload de map `addons/chathype_avatar_chooser` naar de `addons`-map van CodyChat.
3. Installeer de addon via **Admin → Addons** zodat de `boom_addons`-registratie wordt aangemaakt.
4. Integreer de knop en het script uit `profile_menu_insert.php.txt` in `system/box/profile.php`. De knop moet het id `chathype_open_avatar_picker` hebben. Het script definieert `window.chathypeOpenAvatarPicker` en opent de editor.
5. Zorg dat de projectmap `/avatar` schrijfbaar is voor PHP.
6. Open **Admin → Addons → Chathype Avatar Kiezer** en sla de gewenste instelling op.

## Reparaties in deze versie

- De avatar-editor geeft alleen de inhoud terug; de profielintegratie opent de modal. Zo wordt niet onbedoeld een modal in een tweede modal gewrapt.
- De profielintegratie definieert de globale opener die de profielknop aanroept en ondersteunt muis- en toetsenbordactivering.
- De beheeractie slaat de instelling op in `boom_addons.custom1` en gebruikt CodyChat-addonbeheerrechten met een rankfallback.
- De losse `admin.php` gebruikt niet langer de niet-bestaande `chathypeAvatarTableReady()` of een niet-bestaande instellingen-tabel.

## Teststatus

De wijzigingen zijn naar GitHub gepusht. Ze zijn niet live getest op jouw eigen XAMPP/hostinginstallatie. Test eerst op een backup en controleer de browserconsole en PHP-errorlog als jouw CodyChat-build afwijkende modalhelpers gebruikt.
