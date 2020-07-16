ToDo
====
 * Upgrade to Symfony 5
 * Write documentation
 * Add remove user form


In Progress
===========
 * CSS. Moving away from Bootstrap
 * Images are removed on post deletion, but also need to remove cached images, (img, thumb)

Done
====

 * ~~User role that only allows moderation~~
 * ~~When clicking an image, expand inside the post~~
 * ~~Message div gets too small, text gets crush.. also text wordwrap needs fixing~~
 * ~~Allow code blocks - Possibly use https://prismjs.com~~
 * ~~When posting form returns error, keep reply post id~~
 * ~~Admin view to create users~~
 * ~~Remove jQuery dependancy~~
   * ~~Removed need for jQuery in upload.js and reply.js~~
   * ~~Replace functionality of collapse.js and alert.js~~
 * ~~Images converted to thumbnails~~
 * ~~When adding banned IP address, date/time already populated with `now` and `now` + 3 days~~
 * ~~When clicking `Reply` message is somehow highlighted to denote message is being replied to~~
 * ~~View that shows all messages from IP address~~
 * ~~When clicking `Reply` on a child post, take to the parent but highlight the child~~
 * ~~CRON command to remove banned IP addresses~~
 * ~~Cooldown between posting~~
 * ~~Link to other boards with `>>>/technology` and `>>>/music/3452`~~
 * ~~Strip EXIF data from images (Maybe https://symfony.com/doc/current/bundles/LiipImagineBundle/filters/general.html#filter-strip)~~
 * ~~Check boardname matches [azAZ0-9] only~~
 * ~~Seperate form for setting user password~~
 * ~~Seperate form for setting board password~~