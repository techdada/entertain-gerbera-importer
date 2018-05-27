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


Usage:

Copy config.properties.sample to config.properties. 

Enter your database user/pass/name/host details

Adapt, if needed, the PLS link.

If you have a rtp proxy which needs to be used adapt its address, otherwise leave it empty.

To attach the URL objects to a node within Gerbera, there are two options:

1. Create node Videos->T-Entertain using the Gerbera web interface
2. Create any other node using the web interface, and find out its ID. Enter this ID as pls_nodeid= in the properties file.

When all is configured, run the script by using

php entertain-importer.php
