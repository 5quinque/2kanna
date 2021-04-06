ToDo
====
 * Write documentation
 * Correct permissions for every page admin/mod/boardadmin/anon
 * https://devcenter.heroku.com/articles/deploying-symfony4#trusting-the-heroku-router
 * Table prefixes? https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/cookbook/sql-table-prefixes.html
 * JavaScript classes - https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Classes
 * IPv6 addresses will not cache, due to ':' character being invalid
 * Make "Recently Active Threads" not look crap
 * Fix homepage caching issue
 * Owners of boards can administrate that board

In Progress
===========
 * Testing - https://symfony.com/doc/current/best_practices.html#smoke-test-your-urls

Done
====
 * ~~Username length error needs to notify the user of the error~~
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
 * ~~Liip 'strip' filter breaks animated gifs~~
 * ~~Images are removed on post deletion, but also need to remove cached images, (img, thumb)~~
 * ~~When videos upload to S3, they don't have public read permission~~
 * ~~Add remove user form~~
 * ~~Remove child post's images and their cache on deletion of parent~~
 * ~~Upgrade to Symfony 5~~
 * ~~CSS. Moving away from Bootstrap~~
 * ~~Video thumbnails (Instead of thumbnails, small video that expands on play)~~
 * ~~No 'remember me' checkbox. Just always remember~~
 * ~~https://github.com/liip/LiipImagineBundle/issues/1293~~
 * ~~User defined settings (cached)~~
 * ~~Correctly get user's IP addr. when behind CloudFlare~~
 * ~~Cache homepage board list~~
 * ~~Settings 'group by'~~
 * ~~Dismiss alerts~~
 * ~~Post IDs start at higher number~~
 * ~~'Ban' link on posts~~
 * ~~Ban IP CIDR ranges~~
 * ~~Timezone setting~~
 * ~~Cache banned IP addresses~~
 * ~~/admin/login successful login redirects to /admin~~
 * ~~Setting - [checkbox] allow anyone to create boards~~
 * ~~Board links in header at all times~~
 * ~~Remove post title~~
 * ~~On board_show, child posts need to be most recent~~
 * ~~Recent posts block on homepage~~
 * ~~Give labels to settings~~
 * ~~Autoupdate new posts without needing refresh~~
 * ~~'delete' redirect to slug not id~~
 * ~~'Reply' button~~
 * ~~When collapsing posts, also include child posts in count~~
 * ~~Use Webpack encore to process assets (https://symfony.com/doc/current/frontend.html)~~
 * ~~'Edit' wordfilter~~
 * ~~Sticky posts~~
 * ~~Admin can remove boards~~
 * ~~On login, redirect to /user or /admin~~
