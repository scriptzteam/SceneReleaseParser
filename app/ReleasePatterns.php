<?php
namespace ReleaseParser;

class ReleasePatterns {

	const REGEX_LANGUAGE = '/[._(-]%language_pattern%[._)-][._(-]?(?:%source%|%format%|%audio%|%flags%|%year%|%os%|%device%|%resolution%|(?:us|gbr|eng|nl|fi|fr|no|dk|de|se|ice)|multi|ml[._)-]|dl[._)-]|dual[._-]|%group%)/i';

	const REGEX_DATE = '(\d{2}|\d{4})[._-](\d{2})[._-](\d{2}|\d{4})';

	const REGEX_DATE_MONTHNAME = '(\d{1,2})?(?:th|rd|st|nd)?[._-]?(%monthname%)[._-]?(\d{1,2})?(?:th|rd|st|nd)?[._-]?(\d{4})[._-]?(?:day[._-]?)?(\d{1,2})?';

	const REGEX_DATE_MUSIC = '/\([a-z._]+[._-]' . self::REGEX_DATE . '\)/i';

	const REGEX_YEAR_SIMPLE = '(19\d[\dx]|20\d[\dx])';
	const REGEX_YEAR = '/(?=[(._-]' . self::REGEX_YEAR_SIMPLE . '[)._-])/i';

	const REGEX_GROUP = '/-(\w+)$/i';

	const REGEX_EPISODE = '(?:(?:s\d+[._-]?)?(?:s?ep?|o[av]+[._-]?|f(?:olge[._-])?|band[._-]?|issue[._-]?|ausgabe[._-]?|n[or]?[._-]?|eps[._-]?|episode[._-]?|(?:silber[._-])?edition[._-]?|sets?[._-]?)([\d_-]+)|(?:\d+x)(\d+))';
	const REGEX_EPISODE_TV = '(?<!^)(?:(?:(?:[ST]|saison)\d+)?[._-]?(?:ep?|o[av]+[._-]?|d|eps[._-]?|episode[._-]?)[\d-]+|\d+x\d+|[STD]\d+)';

	const REGEX_DISC = '(?:s\d+)?d(\d+)';

	const REGEX_SEASON = '/[._-](?:(?:[ST]|saison)(\d+)[._-]?(?:[EDP]+\d+)?|(\d+)(?:x\d+))[._-]/i';

	const REGEX_TITLE = '([\w.()-]+)';

	const REGEX_TITLE_EBOOK = '/^' . self::REGEX_TITLE . '[._(-]+(?:%year%|%language%|%flags%|%format%|%regex_date%|%regex_date_monthname%)[._)-]/iU';

	const REGEX_TITLE_FONT = '/^' . self::REGEX_TITLE . '-/i';

	const REGEX_TITLE_MOVIE = '/^' . self::REGEX_TITLE . '[._(-]+(?:%year%|%language%|%source%|%flags%|%format%|%resolution%|%audio%)[._)-]/iU';
	const REGEX_TITLE_MOVIE_EXTRA = '/%year%[._-]' . self::REGEX_TITLE . '[._(-]\.+/iU'; 

	const REGEX_TITLE_MUSIC = '/^' . self::REGEX_TITLE . '(?:\([\w-]+\))?[._(-]+(?:\(?%source%\)?|%year%|%group%|%audio%|%flags%|%format%|%regex_date%|%regex_date_monthname%|%language%[._)-])/iU';
	const REGEX_TITLE_ABOOK = '/^' . self::REGEX_TITLE . '[._(-]+(?:%source%[._)-]|%year%|%group%|%audio%|%flags%|%format%|%language%[._)-])/iU';
	const REGEX_TITLE_MVID = '/^' . self::REGEX_TITLE . '[._(-]+(?:%source%|%year%|%group%|%audio%|%flags%|%format%|%regex_date%|%regex_date_monthname%|%language%[._)-])/iU';

	const REGEX_TITLE_APP = '/^' . self::REGEX_TITLE . '[._(-]+(' . self::REGEX_VERSION . '|%device%|%os%|%source%|%resolution%|%language%)[._)-]/iU'; 

	const REGEX_TITLE_TV = '/^' . self::REGEX_TITLE . '[._-]' . self::REGEX_EPISODE_TV . '/iU';

	const REGEX_TITLE_TV_EPISODE = '/' . self::REGEX_EPISODE_TV . '[._-](?:' . self::REGEX_TITLE . '[._\(-]+)?\.+/iU'; 
	const REGEX_TITLE_TV_DATE = '/^' . self::REGEX_TITLE . '[._\(-]+(?:%regex_date%|%year%)[._\)-]' . self::REGEX_TITLE . '?[._\(-]?(?:%language%[._\)-]|%resolution%|%source%|%flags%|%format%)/iU';

	const REGEX_TITLE_XXX = '/^' . self::REGEX_TITLE . '[._\(-]+(?:%year%|%language%[._\)-]|%flags%)/iU';

	const REGEX_TITLE_XXX_DATE = '/^' . self::REGEX_TITLE . '[._\(-]+(?:%regex_date%|%regex_date_monthname%)[._\)-]+' . self::REGEX_TITLE . '[._\(-]+(?:%flags%|%language%[._\)-])/iU';

	const REGEX_VERSION_TEXT = '(?:v(?:ersione?)?|Updated?[._-]?v?|Build)';
	const REGEX_VERSION = self::REGEX_VERSION_TEXT . '[._-]?([\d.]+[a-z\d]{0,3}(?![._-]gage))';

	const TYPE = [

		'ABook' => 'a.*book',

		'Anime' => 'anime',

		'App' => [ 'app', '0day', 'pda' ],

		'eBook' => 'book',

		'Font' => 'font',

		'Game' => [ 'GAME', 'D[SC]', 'G[BC]', 'NSW', 'PS', 'XBOX', 'WII' ],

		'Music' => [ 'mp3', 'flac', 'music', 'ringtones' ],

		'MusicVideo' => 'm(vid|dvd|bluray)',

		'TV' => 'tv',

		'Sports' => 'sport',

		'XXX' => [ 'xxx', 'imgset', 'imageset' ],

		'Movie' => [ 'movie', '(?!tv).*26[45]', 'bluray', 'dvdr', 'xvid', 'divx' ],
	];

	const SOURCE = [
		'ABC' => 'ABC', 
		'Amazon' => [ 'AZ', 'AMZN', 'AmazonHD' ], 
		'Amazon Freevee' => 'Freevee', 
		'ATVP' => 'ATVP', 
		'AUD' => 'AUD', 
		'BBC' => 'BBC', 
		'BBC iPlayer' => 'iP', 
		'BDRip' => 'b[dr]+[._-]?rip',
		'BookMyShow' => 'BMS', 
		'Bootleg' => '(?<!MBluRay.)(?:LIVE|\\d*cd)?.?BOOTLEG',
		'CABLE' => 'cable',
		'CAM' => '(?:new)?cam([._-]?rip)?',
		'CBS' => 'CBS', 
		'CD Album' => '\d*cda', 
		'CD EP' => 'cdep',
		'CD Single' => [ 'cds', '(?:cd[._-]?)single' ], 
		'Comedy Central' => 'CC', 
		'Console DVD' => [ 'xbox.?dvdr?', 'ps2.?dvd', 'ps3.?bd' ],
		'Crave' => 'CRAV', 
		'Crunchyroll' => 'CR', 
		'DAB' => 'dab', 
		'DC Universe' => 'DCU', 
		'DD' => 'dd(?![._-]?\d)', 
		'DDC' => 'ddc', 
		'DAT Tape' => '\d*DAT', 
		'Disney Plus' => [ 'DP', 'DSNP' ], 
		'Disney Networks' => 'DSNY', 
		'Discovery Plus' => 'DSCP', 
		'DSR' => [ 'dsr', 'dth', 'dsr?[._-]?rip', 'sat[._-]?rip', 'dth[._-]?rip' ], 
		'DVB' => [ 'dvb[sct]?(?:[._-]?rip)?', 'dtv', 'digi?[._-]?rip' ],
		'DVDA' => '\d*dvd[_-]?a', 
		'DVDS' => 'dvd[_-]?s', 
		'DVDRip' => '(?:r\d[._-])?dvd[._-]?rip(?:xxx)?',
		'EDTV' => 'EDTV(?:[._-]rip)?', 
		'FM' => '\d*FM', 
		'Google Play' => 'GPLAY', 
		'HBO Max' => [ 'HM', 'HMAX', 'HBOM', 'HBO[._-]Max' ], 
		'HDCAM' => 'HDCAM',
		'HDDVD' => '\d*hd[\d._-]?dvdr?',
		'HDRip' => [ 'hd[._-]?rip', 'hdlight', 'mhd' ],
		'HDTC' => 'HDTC(?:[._-]?rip)?', 
		'HDTV' => 'a?hd[._-]?tv(?:[._-]?rip)?',
		'HLS' => 'HLS', 
		'Hotstar' => 'HTSR', 
		'Hulu' => 'Hulu(?:UHD)?', 
		'iTunes' => [ 'iT', 'iTunes(?:HD)?' ], 
		'Lionsgate Play' => 'LGP', 
		'Line' => 'line(?![._-]dubbed)',
		'LP' => '\d*lp', 
		'Maxi CD' => [ 'cdm', 'mcd', 'maxi[._-]?single', '(?:cd[._-]?)?maxi' ], 
		'Maxi Single' => [ 'maxi[._-]?single|single[._-]?maxi', '(?<!cd[._-])maxi', '12[._-]?inch' ], 
		'MBluray' => 'MBLURAY',
		'MDVDR' => 'MDVDR?',
		'Movies Anywhere' => '(?<!DTS[._-]|HD[._-])MA', 
		'MP3 CD' => '\d*mp3cd',
		'MTV Networks' => 'MTV', 
		'Mubi' => 'MUBI', 
		'NBC' => 'NBC', 
		'Netflix' => [ 'NF', 'NFLX', 'Netflix(?:HD)?' ], 
		'Nintendo eShop' => 'eshop', 
		'Paramount Plus' => 'PMTP', 
		'Peacock' => 'PCOK', 
		'PDTV' => 'PDTV',
		'PPV' => 'PPV(?:[._-]?RIP)?', 
		'PSN' => 'PSN', 
		'RAWRiP' => 'Rawrip', 
		'SAT' => 'sat', 
		'Scan' => 'scan',
		'Screener' => '(b[dr]|bluray|dvd|vhs)?.?(scr|screener)',
		'Showtime' => 'SHO', 
		'SDTV' => '(?:sd)?tv(?:[._-]?rip)?',
		'SBD' => 'SBD', 
		'Stan' => 'Stan(?:HD)?', 
		'Stream' => 'stream',
		'Starz' => 'STA?R?Z', 
		'TBS' => 'TBS', 
		'Telecine' => [ 'tc', 'telecine' ],
		'Telesync' => [ '(?:hd[._-])?ts', 'telesync', 'pdvd' ], 
		'UHDBD' => 'UHD[\d._-]?BD',
		'UHDTV' => 'UHD[._-]?TV',
		'VHS' => 'VHS(?!.?scr|.?screener)(?:.?rip)?',
		'VLS' => 'vls', 
		'Vinyl' => [ '(Complete[._-])?Vinyl', '12inch' ],
		'VODRip' => [ 'VOD.?RIP', 'VODR' ],
		'Web Single' => '(?:web.single|single.web)', 

		'WEB' => 'WEB[._-]?(?!single)(?:tv|dl|u?hd|rip|cap|flac|mux)?',
		'WOW tv' => 'WOWTV', 
		'XBLA' => 'XBLA', 
		'YouTube Red' => 'YTred', 
		'MiniDisc' => [ 'md', 'minidisc' ], 

		'CD' => [ '\d*cdr?\d*', 'cd.?(?:rom|rip)' ], 
		'DVD' => '(Complete.|full.?)?\d*dvd[_-]?[r\d]?', 
		'Bluray' => [ 'bl?u.?r[ae]y', '\d*bdr' ],
		'RiP' => 'rip', 

		'EP' => 'EP',
	];

	const FORMAT = [

		'AVC' => 'AVC',
		'XViD' => 'XViD',
		'DiVX' => 'DiVX\d*',
		'x264' => 'x\.?264',
		'x265' => 'x\.?265',
		'h264' => 'h\.?264',
		'h265' => 'h\.?265',
		'HEVC' => '(?:HDR10)?HEVC',
		'HEVC' => 'HEVC',
		'VP8' => 'VP8',
		'VP9' => 'VP9',
		'MP4' => 'MP4',
		'MPEG' => 'MPEG',
		'MPEG2' => 'MPEG2',
		'VCD' => 'VCD',
		'CVD' => 'CVD',
		'CVCD' => 'CVCD', 
		'SVCD' => 'X?SVCD',
		'VC1' => '(?:Blu.?ray.)?VC.?1',
		'WMV' => 'WMV',
		'MDVDR' => 'MDVDR?',
		'DVDR' => 'DVD[R\d]',
		'MBluray' => '(Complete.?)?MBLURAY',
		'Bluray' => '(complete.?)?bluray',
		'MViD' => 'MViD',

		'AZW' => 'AZW',
		'Comic Book Archive' => 'CB[artz7]',
		'CHM' => 'CHM',
		'ePUB' => 'EPUB',
		'Hybrid' => 'HYBRID',
		'LIT' => 'LIT',
		'MOBI' => 'MOBI',
		'PDB' => 'PDB',
		'PDF' => 'PDF',

		'DAISY' => 'DAISY', 
		'FLAC' => '(?:WEB.?)?FLAC',
		'KONTAKT' => 'KONTAKT',
		'MP3' => 'MP3',
		'WAV' => 'WAV',
		'3GP' => '3gp',

		'ISO' => '(?:Bootable.)?ISO',

		'CrossPlatform' => 'Cross(?:Format|Platform)',
		'OpenType' => 'Open.?Type',
		'TrueType' => 'True.?Type',

		'Java Platform, Micro Edition' => 'j2me(?:v\d*)?',
		'Java' => 'JAVA',

		'Multiformat' => 'MULTIFORMAT'
	];

	const RESOLUTION = [
		'SD' => 'SD',
		'NTSC' => 'NTSC',	
		'PAL' => 'PAL',		
		'480p' => '480p',
		'576p' => '576p',
		'720p' => '720p',
		'1080i' => '1080i',
		'1080p' => '1080p',
		'1920p' => '1920p',
		'2160p' => '2160p',
		'2700p' => '2700p',
		'2880p' => '2880p',
		'3072p' => '3072p',
		'3160p' => '3160p',
		'3600p' => '3600p',
		'4320p' => '4320p'
	];

	const AUDIO = [
		'10BIT' => '10B(?:IT)?',
		'16BIT' => '16B(?:IT)?',
		'24BIT' => '24B(?:IT)?',
		'44K' => '44kHz',
		'48K' => '48kHz',
		'96K' => '96KHZ',
		'160K' => '16\dk(?:bps)?',
		'176K' => '176khz',
		'192K' => '19\dk(?:bps)?',
		'AAC' => 'AAC(?:\d)*',
		'AC3' => 'AC3(?:dub|dubbed|MD)?',
		'AC3D' => 'AC3D',
		'EAC3' => 'EAC3',
		'EAC3D' => 'EAC3D',
		'Dolby Atmos' => 'ATMOS',
		'Dolby Digital' => [ 'DOLBY.?DIGITAL', 'dd[^p]?\d+' ],
		'Dolby Digital Plus' => [ 'DOLBY.?DIGITAL', 'ddp.?\d' ],
		'Dolby Digital Plus, Dolby Atmos' => 'ddpa.?\d',
		'Dolby trueHD' => '(?:Dolby)?[._-]?trueHD',
		'DTS' => 'DTSD?(?!.?ES|.?HD|.?MA)[._-]?\d*',
		'DTS-ES' => 'DTS.?ES(?:.?Discrete)?',
		'DTS-HD' => 'DTS.?(?!MA)HD(?!.?MA)',
		'DTS-HD MA' => [ 'DTS.?HD.?MA', 'DTS.?MAD?' ],
		'DTS:X' => 'DTS[._-]?X',
		'OGG' => 'OGG',

		'2.0' => [ 'd+2[._-]?0', '\w*(?<!v[._-]|v)2[._-]0', '2ch' ],
		'2.1' => [ 'd+2[._-]?1', '\w*(?<!v[._-]|v)2[._-]1' ],
		'3.1' => [ 'd+3[._-]?1', '\w*(?<!v[._-]|v)3[._-]1' ],
		'5.1' => [ 'd+5[._-]?1', '\w*(?<!v[._-]|v)5[._-]1(?:ch)?' ],
		'7.1' => [ 'd+7[._-]?1', '\w*(?<!v[._-]|v)7[._-]1' ],
		'7.2' => [ 'd+7[._-]?2', '\w*(?<!v[._-]|v)7[._-]2' ],
		'9.1' => [ 'd+9[._-]?1', '\w*(?<!v[._-]|v)9[._-]1' ],

		'Dual Audio' => '(Dual[._-]|2)Audio',
		'Tripple Audio' => '(Tri|3)Audio'
	];

	const DEVICE = [
		'3DO' => '3DO',
		'Bandai WonderSwan' => 'WS',
		'Bandai WonderSwan Color' => 'WSC',
		'Commodore Amiga' => 'AMIGA',
		'Commodore Amiga CD32' => 'CD32',
		'Commodore C64' => 'C64',
		'Commodore C264' => 'C264',
		'Nintendo DS' => 'NDS',
		'Nintendo 3DS' => '3DS',
		'Nintendo Entertainment System' => 'NES',
		'Super Nintendo Entertainment System' => 'SNES',
		'Nintendo GameBoy' => [ 'GB', 'GAMEBOY' ],
		'Nintendo GameBoy Color' => 'GBC',
		'Nintendo GameBoy Advanced' => 'GBA',
		'Nintendo Gamecube' => [ 'NGC', 'GAMECUBE' ],
		'Nintendo iQue Player' => 'iQP',
		'Nintendo Switch' => 'NSW',
		'Nintendo WII' => 'WII',
		'Nintendo WII-U' => 'WII[._-]?U',
		'NEC PC Engine' => 'PCECD',
		'Nokia N-Gage' => '(?:nokia[._-])?n[._-]?gage(?:[._-]qd)?',
		'Playstation' => 'PS[X1]?',
		'Playstation 2' => 'PS2(?:.?dvdr?|.?cd)?',
		'Playstation 3' => 'PS3(?:.?bd)?',
		'Playstation 4' => 'PS4',
		'Playstation 5' => 'PS5',
		'Playstation Portable' => 'PSP',
		'Playstation Vita' => 'PSV',
		'Pocket PC' => 'PPC\d*',
		'Sega Dreamcast' => [ 'DC', 'DREAMCAST' ],
		'Sega Mega CD' => 'MCD',
		'Sega Mega Drive' => 'SMD',
		'Sega Saturn' => 'SATURN',
		'Tiger Telematics Gizmondo' => 'GIZMONDO',
		'VTech V.Flash' => 'VVD',
		'Microsoft Xbox' => 'xbox(?:.?dvdr?i?p?\d?|rip|full|cd)?',
		'Microsoft Xbox One' => 'XBOXONE',
		'Microsoft Xbox360' => [ 'XBOX360', 'X360' ],
	];

	const OS = [
		'IBM AIX' => 'AIX', 
		'Android' => 'Android',
		'BlackBerry' => 'Blackberry',
		'BSD' => '(?:Free|Net|Open)?BSD',
		'HP-UX' => 'HPUX', 
		'iOS' => [ 'iOS', 'iPhone' ],
		'Linux' => 'Linux(?:es)?',
		'macOS' => 'mac([._-]?osx?)?',
		'PalmOS' => 'Palm[._-]?OS\d*',
		'Solaris' => [ '(Open)?Solaris', 'SOL' ],
		'SunOS' => 'SunOS',
		'Symbian' => 'Symbian(?:OS\d*[._-]?\d*)?',
		'Ubuntu' => 'Ubuntu',
		'Unix'	=> 'Unix(All)?',
		'WebOS' => 'WebOS',

		'Windows' => 'win(?:(?:[\d]+[\dxk]?|nt|all|dows|xp|vista|[msp]e)?[._-]?){0,6}',
		'Windows CE' => 'wince',
		'Windows Mobile' => 'wm\d+([._-]?se)?',
	];

	const LANGUAGES = [
		'am' => 'Amharic',
		'ar' => 'Arabic',
		'az' => 'Azerbaijani',
		'bg' => 'Bulgarian',
		'bs' => 'Bosnian',
		'ch' => [ 'Swiss', 'CH' ],
		'cs' => [ 'Czech', 'CZ' ],
		'cy' => 'Welsh',
		'de' => [ 'German', 'GER', 'DE' ],
		'dk' => [ 'Danish', 'DK' ],
		'el' => [ 'Greek', 'GR' ],
		'en' => [ 'English', 'VOA', 'USA?', 'AUS', 'UK', 'GBR', 'ENG' ],
		'es' => [ 'Spanish', 'Espanol', 'ES', 'SPA', 'Latin.Spanish' ],
		'et' => [ 'Estonian', 'EE' ],
		'fa' => [ 'Persian', 'Iranian', 'IR' ],
		'fi' => [ 'Finnish', 'FIN?' ],
		'fil' => 'Filipino',
		'fr' => [ 'French', 'Fran[cç]ais', 'TRUEFRENCH', 'VFF' ],
		'ga' => 'Irish',
		'he' => 'Hebrew',
		'hi' => 'Hindi',
		'hr' => 'Croatian',
		'ht' => [ 'Creole', 'Haitian' ],
		'hu' => 'Hungarian',
		'id' => 'Indonesian',
		'is' => [ 'Icelandic', 'ICE' ],
		'it' => [ 'Italian', 'ITA?' ],
		'jp' => [ 'Japanese', 'JA?PN?' ],
		'kk' => 'Kazakh',
		'ko' => [ 'Korean', 'KOR' ],
		'km' => 'Cambodian',
		'lo' => 'Laotian',
		'lt' => [ 'Lithuanian', 'LIT' ],
		'lv' => 'Latvian',
		'mi' => 'Maori',
		'mk' => 'Macedonian',
		'ms' => [ 'Malay', 'Malaysian' ],
		'nl' => [ 'Dutch', 'HOL', 'NL', 'Flemish', 'FL' ],
		'no' => [ 'Norwegian', 'NOR?(?![._-]?\d+)' ],
		'ps' => 'Pashto',
		'pl' => [ 'Polish', 'PO?L' ],
		'pt' => [ '(?<!brazilian.)Portuguese', 'PT' ],
		'pt-BR' => [ 'Brazilian(?:.portuguese)?', 'BR' ],
		'ro' => 'Romanian',
		'ru' => [ 'Russian', 'RU' ],
		'sk' => [ 'Slovak', 'SK', 'SLO', 'Slovenian', 'SI' ],
		'sv' => [ 'Swedish', 'SW?E' ],
		'sw' => 'Swahili',
		'tg' => 'Tajik',
		'th' => 'Thai',
		'tl' => 'Tagalog',
		'tr' => [ 'Turkish', 'Turk', 'TR' ],
		'uk' => 'Ukrainian',
		'vi' => 'Vietnamese',
		'zh' => [ 'Chinese', 'CH[ST]' ],

		'multi' => [ 'Multilingual', 'Multi.?(?:languages?|lang|\d*)?', 'EURO?P?A?E?', '(?<!WEB.)[MD]L', 'DUAL(?!.Audio)' ],
		'nordic' => [ 'Nordic', 'SCANDiNAViAN' ]

	];

	const FLAGS = [
		'3D' => '3D',
		'ABook' => 'A(?:UDiO)?BOOK',
		'Abridged' => [ 'ABRIDGED', 'gekuerzte?(?:.(?:fassung|lesung))' ], 
		'Addon' => 'ADDON', 
		'Anime' => 'ANiME',
		'ARM' => 'ARM', 
		'Audiopack' => 'Audio.?pack', 
		'Beta' => 'BETA', 
		'Bookware' => 'BOOKWARE', 
		'Boxset' => 'BOXSET',
		'Chapterfix' => 'CHAPTER.?FIX', 
		'Cheats' => 'Cheats', 
		'Chrono' => 'CHRONO',
		'Colorized' => 'COLORIZED',
		'Comic' => 'COMIC',
		'Complete' => 'Complete',
		'Convert' => 'CONVERT',
		'Cover' => '(?:(?:CUST?OM|%language%|%format%|%source%|%resolution%|scans?|disc|ps[\dp]*|xbox\d*|gamecube|gc|unrated|\d*DVD\w+|hig?h?.?res|int|front|retail|\d+dpi|r\d+|original)[._-]?)COVERS?',
		'CPOP' => 'CPOP', 
		'Incl. Crack' => [ 'CRACK.?ONLY', '(?:incl|working)[._-](?:[a-zA-Z]+[._-])?crack' ], 
		'Cracked' => 'CRACKED', 
		'Crackfix' => 'CRACK.?FIX', 
		'Criterion' => 'CRITERION', 
		'Cuefix' => 'Cue.?fix',
		'Digipack' => 'DIGIPAC?K?', 
		'Directors Cut' => [ 'Directors?.?cut', 'dir[._-]?cut' ],
		'DIRFiX' => 'DIR.?FIX',
		'DIZFiX' => 'DIZ.?FIX',
		'DLC' => '(?:incl.)?DLCS?(?!.?(?:Unlocker|Pack))?', 
		'DOX' => 'D[O0]X',
		'Docu' => 'DO[CK]U?',
		'Dolby Vision' => [ 'DV', 'DoVi' ],
		'Dubbed' => [ '(?<!line.|line|mic.|mic|micro.|tv.)Dubbed', 'E.?Dubbed', '(?!over|thunder)[a-z]+dub' ],
		'eBook' => 'EBOOK',
		'Extended' => 'EXTENDED(?:.CUT|.Edition)?(?!.MIX)',
		'Final' => 'FINAL[._-]?(%language%|%flags%)?',
		'FiX' => '(?<!hot.|sample.|nfo.|rar.|dir.|crack.|sound.|track.|diz.|menu.)FiX(?:.?only)?',
		'Font' => '(Commercial.)?FONTS?',
		'Fontset' => '(Commercial.)?FONT.?SET',
		'Fullscreen' => 'FS', 
		'FSK' => 'FSK', 
		'Hardcoded Subtitles' => 'HC', 
		'HDLIGHT' => 'HDLIGHT',
		'HDR' => 'HDR',
		'HDR10' => 'HDR10(?:hevc)?',
		'HDR10+' => 'HDR10(Plus|\+)',
		'Hentai' => 'Hentai',
		'HLG' => 'HLG', 
		'HOTFiX' => 'HOT.?FIX',
		'HOU' => 'HOU',
		'HR' => 'HRp?.(%flags%|%format%|%source%|%year%)', 
		'HSBS' => 'HS(?:BS)?',
		'Hybrid' => 'HYBRID',
		'Imageset' => [ '(?:Full[._-]?)?(?:IMA?GE?|photo|foto).?SETS?', 'pic.?xxx' ],
		'IMAX' => 'IMAX',
		'Internal' => 'iNT(ERNAL)?',
		'IVTC' => 'IVTC', 
		'JAV' => 'JAV', 
		'KEY' => 'GENERIC.?KEY',
		'KEYGEN' => [ '(?:Incl.)?KEY(?:GEN(?:ERATOR)?|MAKER)(?:.only)?', 'KEYFILE.?MAKER' ],
		'Intel' => 'INTEL',
		'Line dubbed' => [ 'ld', 'line.?dubbed' ],
		'Limited' => 'LIMITED',
		'Magazine' => 'MAG(AZINE)?',
		'Menufix' => 'MENU.?FIX',
		'Micro dubbed' => [ '(?:ac3)?md', 'mic(ro)?.?dubbed' ],
		'MIPS' => 'MIPS', 
		'New' => 'New[._-](%format%|%language%|%source%|%resolution%)',
		'NFOFiX' => 'NFO.?FiX',
		'OAR' => 'OAR', 
		'OVA' => 'O[AV]+\d*', 
		'OAD' => 'OAD', 
		'ONA' => 'OMA', 
		'OEM' => 'OEM',
		'OST' => 'OST', 

		'Incl. Patch' => [ '(?:incl.)?(?:[a-z]+[._-])?patch(?:ed)?(?:[._-]only)', 'no[a-zA-Z]+[._-]patch(?:ed)?(?:[._-]only)' ], 
		'Patchfix' => 'patchfix', 
		'Paysite' => 'PAYSITE', 
		'Portable' => 'Portable', 
		'Preair' => 'PREAIR',
		'Proper' => '(?:REAL)?PROPER',
		'Promo' => 'PROMO',
		'Prooffix' => 'PROOF.?FIX',
		'Rated' => 'RATED',
		'RARFix' => 'RARFIX',
		'READNFO' => 'READ.?NFO',
		'Redump' => '(?:introfree.|new.)?REDUMP(?:.no.intro)?', 
		'Refill' => 'Refill',
		'Reissue' => 'REISSUE',	
		'Regged' => 'REGGED', 
		'Regraded' => 'regraded', 
		'Remastered' => 'REMASTERED',
		'Remux' => 'REMUX',
		'Repack' => '(working.)?REPACK',
		'RERiP' => 're.?rip',
		'Restored' => 'RESTORED',
		'Retail' => 'RETAIL',
		'Ringtone' => 'rtones?',
		'Samplefix' => 'SAMPLE.?FIX',
		'SDR' => 'SDR',
		'Serial' => 'SERIAL(?!.Killer)?', 
		'SFVFix' => 'SFV.?FIX',
		'SH3' => 'SH3', 
		'Sizefix' => 'size.?fixed?',
		'Soundfix' => 'SOUNDFIX',
		'Special Edition' => [ 'SE(?![._-]\d*)', 'SPECIAL.EXTENDED.EDITION' ],
		'STV' => 'STV', 

		'Subbed' => [ '[a-zA-Z]*SUB(?:BED|s)?', '(?:vo.?|fan.?)?SUB(?!pack)[._-]?\w+', '(c|custom).SUB(?:BED|s)', '(ST.?|VOS.?|VO.?ST.?)(FR[EA]?)?' ],
		'Subfix' => 'SUB.?FIX',
		'Subpack' => '(custom.|vob.)?\\w*sub.?pack',
		'Superbit' => 'Superbit',	
		'Syncfix' => 'SYNC.?FIX', 
		'Theatrical' => 'THEATRICAL',
		'Trackfix' => 'TRACK.?FiX', 
		'Trailer' => 'TRAILER',
		'TRAiNER' => 'Trainer(?!.XXX)(?:.(?:%flags%))?',
		'Tutorial' => 'TUTORIAL',
		'TV Dubbed' => 'tv.?dubbed',
		'UHD' => 'UHD',
		'Upscaled UHD' => 'UpsUHD',
		'Unabridged' => [ 'UNABRIDGED', 'Ungekuerzt' ], 
		'Uncensored' => 'UNCENSORED',
		'Uncut' => 'UNCUT',
		'Unlicensed' => 'UNLiCENSED',
		'Unrated' => 'UNRATED',
		'Untouched' => 'UNTOUCHED',
		'USK' => 'USK', 
		'Update' => '(WITH.)?UPDATE',
		'V1' => 'V1.(%format%|%language%|%source%|%resolution%)',
		'V2' => 'V2.(%format%|%language%|%source%|%resolution%)',
		'V3' => 'V3.(%format%|%language%|%source%|%resolution%)',
		'Vertical' => 'Vertical.(hrp?|xxx|%format%|%source%|%resolution%)',
		'VKI' => 'VKI', 
		'VR' => 'VR', 
		'VR180' => 'VR180',
		'Workprint' => [ 'WORKPRINT', 'WP' ],
		'Widescreen' => [ 'widescreen', 'WS' ], 
		'x64' => 'x64', 
		'x86' => 'x86', 
		'XSCale' => 'Xscale', 
		'XXX' => 'XXX'
	];

	const MONTHS = [
		1 => 'Januar[iy]?|Janvier|Gennaio|Enero|Jan',
		2 => 'Februar[iy]?|Fevrier|Febbraio|Febrero|Feb',
		3 => 'Maerz|March|Moart|Mars|Marzo|Mar',
		4 => 'A[bpv]rile?|Apr',
		5 => 'M[ae][iy]|Maggio|Mayo',
		6 => 'Jun[ie]o?|Juin|Giugno|Jun',
		7 => 'Jul[iy]o?|Juillet|Luglio|Jul',
		8 => 'August|Aout|Agosto|Augustus|Aug',
		9 => 'Septemb[er][er]|Settembre|Septiembre|Sep',
		10 => 'O[ck]tob[er][er]|Ottobre|Octubre|Oct',
		11 => 'Novi?emb[er][er]|Nov',
		12 => 'D[ei][cz]i?emb[er][er]|Dec'
	];

	const SPORTS = [

		'a-league',			
		'Premier.?League',	
		'La.?Liga',			
		'Bundesliga\.\d{4}',
		'Eredivisie',		
		'Ligue.?1',			
		'Seria.?A',			
		'FA.Cup',			
		'EPL',				
		'EFL.(?:\d{1,4}|cup|championship)', 
		'MLS',				
		'CSL',				
		'fifa.(?:world.cup|women|fotbolls|wm|U\d+)', 
		'(?:international.)?football.(?:australia|womens|sydney|friendly|ligue1|serie.a|uefa|conference|league)', 
		'(?:womens.)?UEFA',	
		'UEL',				
		'concacaf',			
		'conmebol',			
		'caf',				
		'afc.asian',		

		'Formul[ae].?[1234E].\d{4}',
		'F\d.\d{4}',
		'Superleague.Formula',	
		'Nascar.(?:cup|truck|xfinity)',
		'Indycar\.(series|racing|\d{4})',
		'Porsche.(Carrera|sprint)',
		'DTM.(\d{2,4}|spa|lauszitzring|gp\d+|\d+.lauf)',
		'wrc.(?:fia|\d{4})', 
		'Supercars.championship',
		'W.series.\d{4}',
		'Moto.?(GP|\d).\d{4}',

		'Cycling.(?:volta|giro|tour|strade|paris|criterium|liege|fleche|amstel|la.vuelta)', 
		'giro.d.italia', 
		'la.vuelta.(?:a.espana.)?\d{4}', 
		'tour.de.france.(?:femmes.)?\d{4}.stage.?\d+', 
		'UCI',	

		'(?:Super.|international.)?rugby(.world.cup)?',
		'IPL',	
		'NRL',	

		'NBA.(?:East|West|Finals)',
		'WNBA.\d{4}',
		'Eurocup',

		'T20',
		'BBL',	

		'NFL.(?:pre.?season|super.bowl|pro.bowl|conference|divisional|wild.card|[an]fc|football|week\d+|\d{4})',

		'wwe.(?:nxt|friday|this|main|monday|wrestlemania)',
		'aew.(?:collision|dynamite|dark)',

		'NHL\.(?:\d{4}|stanley.cup|playoffs)',

		'MLB.(?:\d{4}|spring|world.series|pre.?season|playoffs|ws|alcs)',

		'boxing.\d{4}.\d{2}.\d{2}',

		'Grand.Sumo',

		'wimbledon.(?:tennis.)?\d{4}',
		'us.open.\d{4}',
		'french.open(?:.tennis)?.\d{4}',
		'australien.open',

		'LPL.PRO',

		'World.cup'
	];

	const FLAGS_MOVIE = [ 'Dubbed', 'AC3 Dubbed' , 'HDR', 'HDR10', 'HDR10+', 'IMAX', 'Line dubbed', 'Micro dubbed', 'THEATRICAL', 'UNCUT', 'Remux', 'Subbed', 'Directors Cut' ];
	const FLAGS_EBOOK = [ 'eBook', 'Magazine', 'Comic', 'ePUB' ];
	const FLAGS_MUSIC = [ 'OST' ];
	const FLAGS_APPS = [ 'Cracked', 'Regged', 'KEYGEN', 'Incl. Patch', 'Crackfix', 'ISO', 'ARM', 'Intel', 'x86', 'x64', 'Portable' ];
	const FLAGS_GAMES = [ 'DLC', 'TRAiNER' ];
	const FLAGS_ANIME = [ 'Anime', 'Hentai', 'OVA', 'ONA', 'OAD' ];
	const FLAGS_XXX = [ 'XXX', 'JAV', 'Imageset' ];

	const FORMATS_VIDEO = [ 'AVC', 'VCD', 'SVCD', 'CVCD', 'XViD', 'DiVX', 'x264', 'x265', 'h264', 'h265', 'HEVC', 'MP4', 'MPEG', 'MPEG2', 'VC1', 'WMV' ];
	const FORMATS_MUSIC = [ 'FLAC', 'KONTAKT', 'MP3', 'OGG', 'WAV' ];
	const FORMATS_MVID = [ 'MBluray', 'MDVDR', 'MViD' ];

	const SOURCES_GAMES = [ 'Console DVD', 'Nintendo eShop', 'XBLA' ];
	const SOURCES_MOVIES = [ 'Bluray', 'CAM', 'DVD', 'HDCAM', 'HDTC', 'Screener', 'Telecine', 'Telesync', 'UHDBD' ];
	const SOURCES_MUSIC = [ 'AUD', 'CD Album', 'CD EP', 'CD Single', 'DAT Tape', 'DVDA', 'EP', 'FM', 'LP', 'Maxi CD', 'Maxi Single', 'MP3 CD', 'Tape', 'VLS', 'Vinyl', 'Web Single' ];
	const SOURCES_MVID = [ 'DDC', 'MBluray', 'MDVDR' ];
	const SOURCES_TV = [ 'ATVP', 'DSR', 'EDTV', 'HDTV', 'PDTV', 'SDTV', 'UHDTV', 'ABC', 'BBC iPlayer', 'CBS', 'Comedy Central', 'DC Universe', 'Discovery Plus', 'HBO Max', 'Hulu', 'MTV Networks', 'NBC', 'TBS' ];

}

if ( !\function_exists( 'array_key_first' ) )
{
	function array_key_first( array $arr )
	{
		foreach( $arr as $key => $unused )
		{
			return $key;
		}
		return \null;
	}
}

if ( !\function_exists( 'array_key_last' ) )
{
	function array_key_last( array $array )
	{
		if ( !\is_array( $array ) || empty( $array ) )
		{
			return \null;
		}
		return \array_keys( $array )[ \count( $array ) - 1 ];
	}
}

if ( !\function_exists( 'str_contains' ) )
{
	function str_contains( string $haystack, string $needle )
	{
		return empty( $needle ) || \strpos( $haystack, $needle ) !== false;
	}
}
