# LGSM-Web 
git forked by zero for Gaming Samurai

from the LGSM Web Interface by JimTR
https://github.com/JimTR/LGSM-Web

## Intent
Bring this project to the point of a well documented, working web interface for LGSM by discovering original intent and architecture.
I'm really rusty at this stuff and not sure if I will be able to recruit anyone to assist but here goes anyway. 
Coffee, may you forever replenish my fortitude and determination.

## Notes on requirements, installing, and configuring
Requirements for this fork are a working Game & LAMP server with LGSM https://gameservermanagers.com/ and PHP7 (I have already run into deprecation issues, so I am keeping this project in PHP7 as much as possible.)
The continued development is being done against Ubuntu 16.04 with Apache2 and MySQL5.
I can't guarantee compatibility beyond this.

(There was talk of this project moving away from LGSM if I understood correctly, so I am not sure how dependant this project is on LGSM at the time it was forked. TBD)

- now testing webhooks for discord

Current installation notes can be found [here](https://github.com/GamingSamurai/LGSM-Web/wiki/installing)

original README follows

# LGSM - Web Interface
Web Interface for LSGM
This version has been written with PHP 7 in mind but should still work with PHP 5.5x
This software needs configuring before it will work - currently there is no utility to do this, if you wish to try it contact the author

For this Software to work at it's best, it can use the Webmin API so you may want to install webmin on the server you wish to host your games on.
Remember this can be a different server to where the web interface code is placed.

see https://noideersoftware.co.uk/dokuwiki/doku.php/steam:home for more information 
I have seen loads of errors with people trying to install this please read above
