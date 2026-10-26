CHATHYPE AVATAR STUDIO — CODYCHAT V11
====================================

Zelfstandige addon voor leden om een eigen cartoonavatar samen te stellen en op te slaan als CodyChat-profielfoto. Eigen ontwerp geïnspireerd op avatar-personalisatie; bevat geen Snapchat/Bitmoji-assets.

Installatie:
1. Maak een back-up van bestanden en database.
2. Upload addons/chathype_avatar_creator naar de map addons/.
3. Registreer de addon via CodyChat-addonbeheer.
4. PHP moet kunnen schrijven naar de bestaande hoofdmap /avatar.
5. Open de editor via de CodyChat-addonloader. Voeg zo nodig een menu-link toe met de standaard loadpage-functie van jouw installatie.
6. Test eerst met een testaccount.

Bevat editor, live canvas-preview, huid-/haar-/oog-/kledingkleuren, kapsels, expressies, accessoires, opslaan, admin aan/uit en Nederlandse documentatie.

Vereisten: CodyChat 11, PHP 7.4+, MySQLi. De avatar-save endpoint controleert PNG-signatuur en 360x360 afmetingen, beperkt de uploadgrootte en gebruikt een prepared statement. Controleer op de doelinstallatie dat boom_users.user_tumb bestaat en dat avatar-URL's de /avatar/-map gebruiken.

Teststatus: PHP-syntax en ZIP-integriteit zijn lokaal gecontroleerd. Geen live Hostinger-test geclaimd; integratie met de specifieke CodyChat-addonloader moet op de doelinstallatie worden bevestigd.
