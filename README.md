Contao - Erweiterung megamenu
=============================

Changelog
---------
Version 1.3
 * unnötige (doppelte) Angabe der ID im Modul entfernt
 * Animationen angepasst und optimiert
 * Templates für Contao 2.10 + 2.11 hinzugefügt


Anleitung
---------

1. Modul Megamenu anlegen / Template nav_mm
2. MooMenu für Fade oder Drop-Down Animation einstellen
3. Einen Artikel anlegen, der als Megamenü dargestellt wird. Pro dargestelltem Menüpunkt ist ein Artikel notwendig.
4. In der Seitenstruktur für den jeweiligen Menüpunkt das Megamenü aktivieren und den zugehörigen Artikel auswählen
5. Das Ganze per CSS stylen

CSS - Tipps
-----------
Bei der Animation wird zwischen Fade und Drop-Down unterschieden.


CSS - Fade
-----------

Bei der Fade-Animation wird dem 'div' bzw. 'ul' die Klasse .fade mitgegeben. Zum Ausblenden der Animation muss hier
wie beim 'li:hover div' der left oder right - Wert zugewiesen werden. Beispiel:

    #mainnav ul li div.fade { left:0; }

CSS - Drop-Down
---------------

Hier wird als "Submenu" ein zuvor angelegter und in der Seitenstruktur ausgewählter Artikel in einem 'div' dargestellt.
Dieser kann einfach wie ein normales Drop-Down-Menü behandelt werden.

    #mainnav li.submenu div.submenu {
       position:absolute;
       left:-9999px;
       top: 35px;
       width: 500px;
    }

    #mainnav li.submenu div.submenu {
       left:0;
    }

usw.

Wenn die Animation im Modul 'MooMenu' aktiviert wird, gibt es ein paar Dinge zu beachten.
Der Artikel wird von einem weiteren 'div' mit der Klasse '.wrapper' umschlossen. Während der Animation
bekommt dieser 'div' noch die Klasse '.drop'.

Aus den o.a. CSS wird dann:

    #mainnav li.submenu div.submenu,
    #mainnav li.submenu div.wrapper {
      position:absolute;
      left:-9999px;
      top: 35px;
      width: 500px;
      overflow:hidden;
    }

    #mainnav li.submenu div.submenu,
    #mainnav div.drop,
    #mainnav div.drop div.submenu {
      left:0;
    }

