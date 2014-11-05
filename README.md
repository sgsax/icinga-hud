icinga-hud
==========

Simple heads-up-display summary for Icinga (and Nagios)

I had been using [Nagios](http://www.nagios.org/) for a long time and had been happy with it, but really wanted a nicer, more modern UI. After a few stops along the way in my search, I ended up migrating to [Icinga](https://www.icinga.org/) about a year ago. While the UI was much improved, I found it very difficult to add my own custom views (cronks, in Icinga parlance), and very cumbersome when I just wanted quick-and-dirty reports. I knew I wanted something a little different and so the idea started percolating for my "HUD" (heads-up display) concept.

I had already installed the Icinga Classic Web alongside their newer web UI, so that I could use [aNag](https://play.google.com/store/apps/details?id=info.degois.damien.android.aNag&hl=en) on my Android devices for remote monitoring. The Classic Web is much like the traditional Nagios UI, and includes a handy CGI interface for generating JSON output from customizable queries. Leveraging this interface, I was able to build by own alternative frontend, the HUD you see here.

I have not tested it with the Nagios web UI, but I suspect it should work fine. If you are currently using Icinga New Web, you can install the Classic Web alongside without interference, even on the same host. You will need to configure a user account that can at least query all of your data, but it does not need to write changes or post acknowledgements.

The project includes a slightly modified bootstrap bundle (based on v. 3.2.0). My intent was to be able to post results on a large display (TV, 1920x1080), so I added some larger resolutions to the base bootstrap css. If you prefer another bootstrap bundle, perhaps your own, I have provided a patch file which you may use against your own copy.

Installation is simple: extract to a web server of your choosing and deploy a functioning site for it. Edit `config.php` with appropriate values for url, port, cgi path, username, and password. Hopefully, it is self-explanatory enough for most people who will use this application. Your host will also need an appropriate package to provide the PHP CURL and JSON libraries. Please check with your distro or platform documentation for details on how to do this.

Please see the wiki for screenshots.

Future plans include:
 * adding custom filter building on all pages
 * editing config info from within the application
 * using the [MK Livestatus plugin](http://mathias-kettner.de/checkmk_livestatus.html) with the new Icinga 2 Web UI
 * making column count a configurable option (currently hard-coded for six columns)

Please use, fork, comment, and submit patches in good health!
