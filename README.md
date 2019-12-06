# track: 
Monitor/track IP Address. This project is a website displays active inactive IP addresses via nmap xml results

## ToDo

```gfm
- [x] github load.
- [ ] Multilanguage support.
```

## Installation

1. Clone repository:
   `git clone git@github.com:celikbas/track.git `
   `cd track`

2. Install external libraries:
   install slim[^1]: 
      `composer require slim/slim "^3.0" `
   install php-view [^2]: 
   ` composer require slim/php-view `
    install ezsql mysqli helper [^3]:
   ` composer require sunaryohadi/ezsql-mysqli `

3. Create sql database: table.sql

4. Site will be live at:
   http://x.x.x.x/track/ip/

    



[^1]:  Slim Framework: https://www.slimframework.com/ 
[^2]:  php-view: https://github.com/slimphp/PHP-View 
[^3]:  ezsql-mysqli https://github.com/sunaryohadi/ezsql-mysqli 

