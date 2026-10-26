# Chathype Avatar Creator — CodyChat 11

Nieuwe editor om een eigen cartoonavatar samen te stellen. Geschikt als addon-basis voor PHP 7.4+ en MySQL/MariaDB.

## Inhoud
- `avatar_creator.php`: editor met live canvas-preview, kleuren, kapsel, gezichtsuitdrukking en accessoires.
- `system/save_avatar.php`: valideert een 360x360 PNG, slaat die op per ingelogd lid en werkt `boom_users.user_tumb` bij.
- `system/saved/`: wordt automatisch aangemaakt bij de eerste opslag.

## Installatie
1. Upload de map `Chathype_Avatar_Creator` naar de map `addons/` van CodyChat.
2. Controleer dat PHP GD is ingeschakeld en dat de map `addons/Chathype_Avatar_Creator/system/` schrijfbaar is voor PHP.
3. Open de editor vanuit een ingelogde CodyChat-sessie. Voorbeeld:
   `$.post('addons/Chathype_Avatar_Creator/avatar_creator.php', {}, function(html){ overModal(html); });`
4. Voeg in het ledenmenu en/of eigen profiel een link toe die deze editor opent. De precieze hook verschilt per CodyChat-installatie.
5. De save-endpoint gebruikt de bestaande `boom_users.user_tumb`-kolom. Maak vooraf een databasebackup.

## Belangrijke integratienotities
- Deze addon is bedoeld voor CodyChat 11; het laden van de editor moet via een ingelogde CodyChat-sessie gebeuren.
- De editor zelf kan worden geopend via modal of een addonpagina. Menu- en profielknoppen moeten in de juiste templates van de specifieke installatie worden toegevoegd.
- De server schrijft PNG-bestanden naar de addonmap. Zorg dat de server geen PHP-bestanden in de map `system/saved/` uitvoert; upload alleen de PNG's die de endpoint genereert.
- Vereist PHP GD voor PNG-verwerking en MySQLi.
- Na opslag kan de chat een cache-busting refresh van de profielfoto nodig hebben.
- Controleer de feitelijke kolomnamen en de manier waarop jouw CodyChat-installatie avatar-URL's rendert vóór productiegebruik.

## Teststatus
PHP-bestanden moeten op de doelserver met de daadwerkelijke CodyChat-configuratie worden getest. Er is geen claim van live Hostinger/XAMPP-test opgenomen.
