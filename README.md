# nG-Apache-Firewall-for-Contao
Just a little collection how to configure the nG firewall (made by Jeff Starr) in Contao CMS. 
Eine kleine Sammlung wie die xG firewall (von Jeff Starr) in Contao CMS konfiguriert wird.

## Ziel
diverse Bots/Crawler scannen das Internet regelmäßig nach Schwachstellen oder auf der Suche nach Inhalten (z.B. Mailadressen). Auch die eigene Webseite ist davon regelmäßig betroffen. Diverse "Hausmittelchen" werden durch die Webseite ausprobiert, um solche Bots/Crawler auf Abstand zu halten.

Warum nicht diese Bots/Crawler bereits beim Betreten der Webseite abhalten?

## nG Apache Firewall/Blacklist
Jeff Starr (https://perishablepress.com/) stellt seit mehreren Jahren die "[nG Firewall](https://perishablepress.com/tag/ng/)"  kostenlos bereit, die sehr einfach zu konfigurieren ist.
* [6G Firewall](https://perishablepress.com/6g/)
* [7G Firewall](https://perishablepress.com/7g-firewall/) (Stable Release Januar 2020)
### Lizenz
Open Source und frei nutzbar für alle ("open source and free for all to use"). Nur eine kleine Referenz in der .htaccess muss vorhanden bleiben.

## Technische Voraussetzungen
* Server: Apache 2.0+
* Zugriff auf die .htaccess-Datei
* die Anleitung bezieht sich auf die Arbeit mit Contao CMS (https://contao.org)

## Anleitung für 7G Firewall
### Allgemeines
* Alle hier genannten Schritte erfolgen auf eigene Gefahr. Alle Angaben ohne Gewähr.
* Es ist jedoch nicht schwer und bedarf an sich nur Grundwissen.
* Die Originalanleitung findet sich auf Englisch: https://perishablepress.com/7g-firewall/
### Backup nicht vergessen
Wie immer: ein Backup hilft immer. Besonders wird hier die .htaccess bearbeitet, die unbedingt vorher (!) gesichert werden musst.
### Schritt 1: Download
* Download der Datei auf https://perishablepress.com/7g-firewall/#download (Version 1.2, 11KB, Stand: August 2020, Text-Datei: 7G-Firewall-v1.2.txt)
### Schritt 2: .htaccess Datei bearbeiten
* Contao 4.9: /web/.htaccess
* Backup machen (sicher ist sicher!)
* den Inhalt aus der 7G Downloaddatei am ANFANG der .htaccess-Datei einfügen (nichts von dem vorherigen Inhalt darf überschrieben werden)
* speichern und auf den Webserver an gleicher Stelle speichern
### Schritt 3: Testen
* Aufruf der Webseite mit z.B. "/vbulletin/" in der Adresse (z.B.: https://domain.tld/vbulletin/)
* es ist ein kurzer Text auf weißen Hintergrund zu sehen "Goodbye"
* der Server antwortet mit einem 403 Fehler (Forbidden)
### Zugabe: Wissen was geblockt wurde
Diese Firewall kann ggf. zu viel blocken. 
Dafür gibt es eine Möglichkeit ein Log zu erstellen. Die ausführliche englische Beschreibung: https://perishablepress.com/7g-firewall-log-blocked-requests/
Bitte nutzt die ["Issue"-Funktion](https://github.com/mathContao/xG-Apache-Firewall-for-Contao/issues) hier bei Github, um eure Erfahrungen zu teilen
### Schritt 5: Logging-Funktionen vorbereiten
* Download: https://perishablepress.com/7g-firewall-log-blocked-requests/#download (Version 1.1, 2KB, Stand: August 2020, ZIP-Datei: 7G-Log-Blocked-Requests-v1.1.zip)
* die ZIP-Datei entpacken
* es werden 2 Dateien benötigt:
** 7g_log.php (hier ist das Tool enthalten, dass das von der .htaccess aufgerufen wird und in die Log-Dateien die Daten schreibt)
** 7g_log.txt (Log-Datei)
* beide Dateien auf den Server, direkt neben die .htaccess-Datei (in Contao 4.9: /web/) hochladen
* ggf. die Dateirechte anpassen (Es werden benötigt: CHMOD 644)
### Schritt 6: Log schützen
* in die .htaccess Datei am Anfang einfügen:
Wenn der Apache Server Version 2.3 und kleiner ist:
```
<IfModule !mod_authz_core.c>
	<Files ~ "7g_log\.txt">
		Deny from all
	</Files>
</IfModule>
```
ab Apache Server 2.4:
```
<IfModule mod_authz_core.c>
	<Files ~ "7g_log\.txt">
		Require all denied
	</Files>
</IfModule>
```
* Testen: Aufruf von https://domain.tld/7g_log.txt erzeugt ein Forbidden (Error 403)
### Schritt 7: Logging aktivieren
* .htaccess (siehe oben) öffnen
* mehrmals `RewriteRule .* - [F,L]` auskommentieren (`#` davor schreiben), `RewriteRule .* /7g_log.php?....`einkommentieren (`#` entfernen)

Beispiel Zeile 58-60:
(vorher):
```	
	RewriteRule .* - [F,L]
	
	# RewriteRule .* /7g_log.php?log [L,NE,E=7G_QUERY_STRING:%1___%2___%3]
```
 (Nachher):
```
 	# RewriteRule .* - [F,L]
	
	RewriteRule .* /7g_log.php?log [L,NE,E=7G_QUERY_STRING:%1___%2___%3]
```
weitere Zeilen: 96-98, 113-115, 126-128, 140-142, 153-155
### Schritt 8: Log einsehen
* per FTP die Datei /web/7g_log.txt aufrufen
* weitere Detailinfos siehe in der Dokumentation: https://perishablepress.com/7g-firewall-log-blocked-requests/#reading
