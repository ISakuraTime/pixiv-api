# Pixiv API
This is implemented using the Pixiv web API.

---
# Documentation
> The following documentation assumes PHP is deployed to the web root path by default.

## Image URL Image

**Required Parameter**: `url` Image URL

**Endpoint**: `image-url.php`

**Example**: `/image-url.php?url=https://i.pximg.net/img-original/img/2019/06/14/00/00/02/75209748_p0.jpg`

## Image ID Image

**Required Parameter**: `id` Image ID

**Endpoint**: `/image-id.php`

**Example**: `/image-id.php?id=75209748`

## Artworks

**Required Parameter**: `workid` Work ID

**Endpoint**: `/artworks.php`

**Example**: `/artworks.php?workid=112712667`

## Rank

**Optional Parameters**: `content`

> Ranking Content:
>
> Comprehensive (Default) - No Parameter
>
> `illust` - Illustrations
>
> `ugoira` - Animated Images
>
> `manga` - Manga

**Optional Parameter**: `mode`

> Ranking Mode:
>
> `daily` - Daily Rankings
>
> `weekly` - Weekly Rankings
>
> `monthly` - Monthly Rankings **(Not Applicable to Animated Images)**
>
> `rookie` - Rookie Rankings **(Not Applicable to Animated Images)**
>
> `original` - Original Rankings **(Only for Comprehensive Content)** **(Not Applicable to Animated Images)**
>
> `daily_ai` - AI-generated Rankings **(Only for Comprehensive Content)** **(Not Applicable to Animated Images)**
>
> `male` - Male Favorites Rankings **(Only for Comprehensive Content)** **(Not Applicable to Animated Images)**
>
> `female` - Female Favorites Rankings **(Only for Comprehensive Content)** **(Not Applicable to Animated Images)**

Optional Parameter: `date`

> Date Format: `YYYYMMdd`
>
> Example: `20231021`

**Optional Parameters**: `?mode=daily&content=illust`

**Endpoint**: `/rank.php`

**Example**: `/rank.php?mode=daily&content=ugoira&date=20231021`

## User Profile - All User's Works

**Required Parameter**: `userid` User ID

**Endpoint**: `/user-profile-all.php`

**Example**: `/user-profile-all.php?userid=211515`

## User Profile - Illustrations Preview

**Required Parameter**: `userid` User ID

**Required Parameter**: `ids` User's Work IDs, separated by commas

**Endpoint**: `/user-profile-illusts.php`

**Example**: `/user-profile-illusts.php?userid=211515&ids=112686358,111334260`

## User Profile - User's Work Tags

**Required Parameter**: `ids` User's Work IDs, separated by commas

**Endpoint**: `/user-profile-tags-illusts.php`

**Example**: `/user-profile-tags-illusts.php?ids=1098570,1141516,1149594,1199900`