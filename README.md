# xG-Apache-Firewall-for-Contao
Just a little collection how to configure the xG firewall (made by Jeff Starr) in Contao CMS. 
Eine kleine Sammlung wie die xG firewall (von Jeff Starr) in Contao CMS konfiguriert wird.

## Ziel
diverse Bots/Crawler scannen das Internet regelmäßig nach Schwachstellen oder auf der Suche nach Inhalten (z.B. Mailadressen). Auch die eigene Webseite ist davon regelmäßig betroffen. Diverse "Hausmittelchen" werden durch die Webseite ausprobiert, um solche Bots/Crawler auf Abstand zu halten.
Warum nicht diese Bots/Crawler bereits beim Betreten der Webseite abhalten?

## nG Apache Firewall/Blacklist
Jeff Starr (https://perishablepress.com/) stellt seit mehreren Jahren die "[nG Firewall](https://perishablepress.com/tag/ng/)"  kostenlos bereit, die sehr einfach zu konfigurieren ist.
* [6G Firewall](https://perishablepress.com/6g/) (Release Jnauar 2016)
* [7G Firewall](https://perishablepress.com/7g-firewall/) (Release Januar 2019)
### Lizenz
Open Source und frei nutzbar für alle ("open source and free for all to use"). Nur eine kleine Referenz in der .htaccess muss vorhanden bleiben.

## Technische Voraussetzungen
* Server: Apache 2.0+
* Zugriff auf die .htaccess-Datei
* die Anleitung bezieht sich auf die Arbeit mit Contao CMS (https://contao.org)
