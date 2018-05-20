# entertain-gerbera-importer
Importer for entertain Playlists to gerbera

Telekom provides the german television service Entertain (and EntertainTV) for which there exists a multicast address list at https://iptv.blog/artikel/multicastadressliste/.
To use this addresses from any UPNP / DLNA ready device the addresses can be added to UPNP server "Gerbera" (successor of "Mediatomb") with this importer.

The importer is written in PHP and intended to be used as a shell script.
It requires following packages (for example on Fedora 27):
php-cli
php-pdo
php-mysqlnd
php-mbstring
