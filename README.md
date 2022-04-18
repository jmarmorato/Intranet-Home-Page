# Intranet-Home-Page

Created in response to personal "dashboards" that are little more than pages with a list of frequently accessed links, Intranet Home Page is a personal, self-hosted **homepage** with integrations for multiple publicly-available and self-hosted data feeds.

### What's new in April 2022?
- Fixed spacing issues between weather alert banners and cards
- Filtered out weather advisories to avoid alert fatigue and cluttering the page
- Google search suggestions autocomplete dropdown
- Some code cleanup

### Currently implemented integrations include:
- (US) National Weather Service severe weather alerts
- (US) National Weather Service current conditions and local forecast
- Random image from Piwigo album
- CalDAV calendar feed
- Quick Links
- Static Image
- RSS Feed

This is a very new project, and it needs a lot of work.  Contributions are welcome!

### TODO / What I'd like to do in the near future:
- Fix the CSS such that card links don't get too close to the bottom of their cards
- Move all of the data access and processing code into its own models / controllers and have the browser fetch each card separately.  This will allow each backend request (CalDAV, RSS, weather API...) to happen concurrently and speed up page load times.
- Create a dark theme

![name-of-you-image](https://github.com/jmarmorato/Intranet-Home-Page/blob/master/screenshots/1.png?raw=true)

# Setup Instructions

### Setup Instructions for Ubuntu (Tested on 20.04)

After setting up a fresh Ubuntu instance, we need to install some packages.  Note that the PHP version must be 7.4 or higher.

`apt install apache2 php libapache2-mod-php php-mysql php-mbstring php-intl php-xml php-curl git composer`

Next we have to download the git repository

`cd /var/www`

`git clone https://github.com/jmarmorato/Intranet-Home-Page.git`


Update Apache config to point to the installation

`nano /etc/apache2/sites-enabled/000-default.conf`

Edit the DocumentRoot Line to read:

`DocumentRoot /var/www/Intranet-Home-Page/public`

Restart Apache

`service apache2 restart`

Download SabreDAV dependency

```
cd /var/www/Intranet-Home-Page/app/ThirdParty
composer require sabre/dav ~3.2.0
```

Rename config.json.default to config.json

```
cd /var/www/Intranet-Home-Page
cp config.json.default config.json
```

Finally, set proper ownership to the web-server user

```
cd /var/www
chown www-data:www-data -R Intranet-Home-Page/
```

# Configuration

### Branding

The only branding built in at this point is the tab / window title (page_title), and the navbar title (header_text).  These can be set to whatever stringd you want, however the header_text should be kept short if many drop down menus will be configured, as this can cause spacing issues.

### Weather Alerts (US only at this time)

The weather alerts module uses alerts.weather.gov to pull weather alerts for your location.  To set your alert zone, first head over to https://alerts.weather.gov/index.php. Scroll down and next to your state, click on either "Zone List", or "County List".  If you live in a particularly large county, it may be split into multiple zones so you may be able to get slightly better accuracy using a zone rather than a county.  Next, locate your location, and right click the corresponding "ATOM" icon, and click "Copy Clink".  Paste this link into the "url" field under the "weather_alerts" configuration object in config.json.

### Navbar

The "navbar" configuration object should only contain an array called "lists".  Each object in the "lists" array specifies a dropdown menu of links.

A simple navbar would be configured as follows:
```json
{
  "title" : "Dropdown Title",
  "items" : [
    {"link_text" : "Google", "link_url" : "https://www.google.com/"},
    {"link_text" : "Netflix", "link_url" : "https://www.netflix.com/"}
  ]
}
```

You can add a horozontal line to separate the links in the list by adding `{"divider" : true}` to the list of items.

Intranet Home Page also supports multi-column dropdowns.  To create a multicolumn dropdown, simply replace the "items" array with a "columns" array.  The "columns" arrray should only contain array elements, which contain the menu items.  That would look something like this.

```json
{
  "title" : "Column 1",
  "columns" : [
     [
      {"header" : "This is a header!"},
      {"link_text" : "Unifi Controller", "link_url"  : "https://192.168.0.2:8443/"},
      {"link_text" : "LibreNMS", "link_url"  : "http://192.168.9.5"},
      {"divider" : true},
      {"link_text" : "pfSense", "link_url"  : "https://192.168.0.1/"}
    ],
    [
      {"header" : "Column 2"},
      {"link_text" : "UPS", "link_url"  : "https://192.168.7.7/"},
      {"divider" : true},
      {"header" : "Cloud"},
      {"link_text" : "Linode", "link_url"  : "https://www.linode.com/"}
    ]
  ]
}
```

Take note that I included the syntax for some headers in that example.

### CalDAV Card

The configuration for most of the cards are pretty self-explanitory.  The CalDAV card follows this pattern (note, the example URL is for NextCloud, but you can use any CalDAV URL in that field):

```json
{
  "type" : "caldav",
  "card_title" : "Upcoming Events",
  "caldav_url"  : "http://<nextcloud-ip>/remote.php/dav/calendars/admin/personal/",
  "username" : "admin",
  "password" : "admin",
  "calendars": [
    "personal"
  ],
  "days_to_display" : 10
}
```

### Random Piwigo Image Card

The random Piwigo image card displays a random image from the specified piwigo album.  At the bottom of the card is a link to your Piwigo server (you can set the link text with "piwigo_link_text").

```json
{
  "type" : "piwigo",
  "card_title" : "Random Sample",
  "piwigo_url" : "http://<piwigo-ip>",
  "piwigo_link_text" : "Browse",
  "piwigo_album_id" : 1
}
```

### Quick Links

The Quick Links card displays a list of links that you may frequently want to access without clicking a dropdown first.  Use this for your most frequently accessed links.

```json
{
  "type" : "quick_links",
  "card_title" : "Quick Links",
  "links" : [
    {"link_text" : "Network Documentation", "link_url"  : "http://docs.example.net"},
    {"link_text" : "Blue Iris", "link_url"  : "http://blueiris.example.net"},
    {"link_text" : "Weather Station", "link_url"  : "http://weewx.example.net/"},
    {"link_text" : "Shopping Lists", "link_url"  : "https://lists.example.net/"},
    {"link_text" : "Jitsi", "link_url"  : "https://jitsi.example.net/"}
  ]
}
```

### RSS Feed

I use the RSS feed card to display a news feed.  This just displays the article title and links to the article link as specified by the RSS feed.  You should be able to use any RSS feed with this card.  The card also features a link at the bottom to location of your specification.

```json
{
  "type" : "rss",
  "card_title" : "News",
  "rss_url" : "https://www.cbsnews.com/latest/rss/main",
  "num_items" : 10,
  "card_link" : "https://www.cbsnews.com",
  "card_link_text" : "View News"
}
```

### Image

The image card will display an image of your chosing.  I use this to display a regional radar GIF.

```json
{
  "type" : "image",
  "card_title" : "Current Radar",
  "image_url" : "https://radar.weather.gov/ridge/lite/NORTHEAST_loop.gif",
  "image_href" : "https://radar.weather.gov/"
  "card_link" : "https://forecast.weather.gov/"
  "card_link_text" : "Local Forecast"
}
```
"image_url" Specifies the location for the image
"image_href" Specifies where you go when you click on the image
"card_link" Specifies the card link location
"card_link_text" Specifies the card link text

### US Weather

```json
{
  "type" : "us_weather",
  "card_title" : "Weather",
  "forecast_url" : "https://api.weather.gov/gridpoints/PHI/51,101/forecast",
  "conditions_url" : "https://api.weather.gov/stations/<station-id>/observations/latest"
}
```

See this page for getting your forecast URL: https://www.weather.gov/documentation/services-web-api#.

While you are on that page, begin typing your closest "city" in the "Local forecast by City, st or ZIP code" box on the top left of thge page.  Click on your location from the suggestions, and take note of the location code in the "Current conditions at" banner.  This will usually be a four letter code in parentheses.  Drop that into the <station-id> in the example above and you should be set.
