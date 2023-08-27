# __SceneReleaseParser__

### __Description__

This library parses scene release names and splits the data into smaller, simpler, human readable and therefore more reusable data.

The applied rules are mostly based on studying the [existing collection of Scene rules](https://scenerules.org/) and other release examples from a PreDB, since a lot of releases are not named correctly (specially older ones).

The approach was to implement an algorithm that can really parse a variety of scene releases from all decades. The main test file covers some more complex names.

__Example:__

```php
<?php
require_once __DIR__ . "/app/ReleaseParser.php";

$release = new \ReleaseParser\ReleaseParser( '24.S02E02.9.00.Uhr.bis.10.00.Uhr.German.DL.TV.Dubbed.DVDRip.SVCD.READ.NFO-c0nFuSed', 'tv' );

print_r( $release->get() );

=> (
    [release] => 24.S02E02.9.00.Uhr.bis.10.00.Uhr.German.DL.TV.Dubbed.DVDRip.SVCD.READ.NFO-c0nFuSed
    [title] => 24
    [title_extra] => 9 00 Uhr bis 10 00 Uhr
    [group] => c0nFuSed
    [year] =>
    [date] =>
    [season] => 2
    [episode] => 2
    [disc] =>
    [flags] => Array
        (
            [0] => READNFO
            [1] => TV Dubbed
        )

    [source] => DVDRip
    [format] => SVCD
    [resolution] =>
    [audio] =>
    [device] =>
    [os] =>
    [version] =>
    [language] => Array
        (
            [de] => German
            [multi] => Multilingual
        )

    [type] => TV
)

// Other examples
echo $release->get( 'source' );
DVDRip

echo $release->get( 'format' );
SVCD

print_r( $release->get( 'flags' ) );
Array
(
    [0] => READNFO
    [1] => TV Dubbed
)

```
