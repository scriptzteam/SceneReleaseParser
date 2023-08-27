<?php
namespace ReleaseParser;

if (php_sapi_name() == "cli") {
    $pre_start = "";
    $pre_end = "";
} else {
    $pre_start = "<pre>";
    $pre_end = "</pre>";
}

require_once "../app/ReleaseParser.php";

function release_parser_test()
{
    $reset = "\33[0m";
    $bold = "\33[1m";
    $green = "\33[92m";
    $green = "\33[32m";
    $greenlight = "\33[92m";
    $greenlightbg = "\33[102m";
    $red = "\33[31m";
    $redlight = "\33[91m";
    $redbg = "\33[41m";
    $black = "\33[30m";
    $white = "\33[97m";
    $yellow = "\33[33m";

    $start_time = \microtime(\true);

    echo \PHP_EOL . "Starting ReleaseParser tests ..." . \PHP_EOL . \PHP_EOL;

    $tests = [
        [
            new ReleaseParser(
                "Diablo_II_Resurrected_Update_v1.0.0.3_incl_Offline_Crack_NSW-VENOM",
                "games"
            ),
            "Title: Diablo II Resurrected / Group: VENOM / Flags: Incl. Crack, Update / Device: Nintendo Switch / Version: 1.0.0.3 / Type: Game",
        ],

        [
            new ReleaseParser("Madshot_NSW-VENOM", "games"),
            "Title: Madshot / Group: VENOM / Device: Nintendo Switch / Type: Game",
        ],

        [
            new ReleaseParser("RSP.OGG.Vorbis.Player.OCX.v2.5.0-Lz0", "Apps"),
            "Title: RSP OGG Vorbis Player OCX / Group: Lz0 / Version: 2.5.0 / Type: App",
        ],

        [
            new ReleaseParser(
                "ActiveState.Visual.Python.for.VS.2003.v1.8.1.2082.WinNT2K.Incl.Keygenerator-TMG",
                "Games"
            ),
            "Title: ActiveState Visual Python for VS 2003 / Group: TMG / Year: 2003 / Flags: KEYGEN / Os: Windows / Version: 1.8.1.2082 / Type: App",
        ],

        [
            new ReleaseParser(
                "PluralSight.Microsoft.Azure.Network.Engineer-secure.And.Monitor.Networks.Bookware-KNiSO",
                "0day"
            ),
            "Title: PluralSight Microsoft Azure Network Engineer-secure And Monitor Networks / Group: KNiSO / Flags: Bookware / Type: App",
        ],

        [
            new ReleaseParser("Aviano.Update.v1.03-ANOMALY", "0day"),
            "Title: Aviano / Group: ANOMALY / Flags: Update / Version: 1.03 / Type: App",
        ],

        [
            new ReleaseParser(
                "QUIZWARE.PRACTICE.TESTS.FOR.COMPUTER.ASSOCIATES.CERTIFICATIONS.V4.84-JGT",
                "ebook"
            ),
            "Title: Quizware Practice Tests For Computer Associates Certifications / Group: JGT / Version: 4.84 / Type: App",
        ],

        [
            new ReleaseParser(
                "SurCode.DVD.Professional.DTS.Encoder.v1.0.21.Retail-iNTENSiON",
                "Apps"
            ),
            "Title: SurCode DVD Professional DTS Encoder / Group: iNTENSiON / Flags: Retail / Version: 1.0.21 / Type: App",
        ],

        [
            new ReleaseParser(
                "Schweighofer.Win1A.Lohn.v23.10.4.0.German.WinALL.Incl.Keygen-BLiZZARD",
                "Apps"
            ),
            "Title: Schweighofer Win1A Lohn / Group: BLiZZARD / Flags: KEYGEN / Os: Windows / Version: 23.10.4.0 / Language: German / Type: App",
        ],

        [
            new ReleaseParser("Broforce.Forever.MacOS-I_KnoW", "Pre"),
            "Title: Broforce Forever / Group: I_KnoW / Os: macOS / Type: App",
        ],

        [
            new ReleaseParser(
                "Harry.Potter.und.die.Kammer.des.Schreckens.TS.Line.Dubbed.German.INTERNAL.VCD.CD2.REPACK-TGSC",
                "Apps"
            ),
            "Title: Harry Potter und die Kammer des Schreckens / Group: TGSC / Flags: Internal, Line dubbed, Repack / Source: Telesync / Format: VCD / Language: German / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Sweet.Home.Alabama.SCREENER.Line.Dubbed.German.VCD-TGSC",
                "Screener"
            ),
            "Title: Sweet Home Alabama / Group: TGSC / Flags: Line dubbed / Source: Screener / Format: VCD / Language: German / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Die.Bourne.Verschwoerung.German.2004.INTERNAL.No.Bock.uff.Proper.READ.NFO.AC3.Dubbed.DL.DVDR-Cinemaniacs",
                "DVDR"
            ),
            "Title: Die Bourne Verschwoerung / Group: Cinemaniacs / Year: 2004 / Flags: Dubbed, Internal, Proper, READNFO / Source: DVD / Format: DVDR / Audio: AC3 / Language: German, Multilingual / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Gegen.den.Strom.2018.German.AC3D.DL.1080p.BluRay.x264-SAVASTANOS",
                "X264"
            ),
            "Title: Gegen den Strom / Group: SAVASTANOS / Year: 2018 / Source: Bluray / Format: x264 / Resolution: 1080p / Audio: AC3D / Language: German, Multilingual / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Burial.Ground.The.Nights.Of.Terror.1981.DUBBED.GRINDHOUSE.VERSION.1080P.BLURAY.X264-WATCHABLE",
                "X264"
            ),
            "Title: Burial Ground The Nights Of Terror / Group: WATCHABLE / Year: 1981 / Flags: Dubbed / Source: Bluray / Format: x264 / Resolution: 1080p / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Batman.v.Superman.Dawn.of.Justice.2016.IMAX.German.DL.TrueHD.Atmos.DUBBED.2160p.UHD.BluRay.x265-GSG9",
                "PRE"
            ),
            "Title: Batman v Superman Dawn of Justice / Group: GSG9 / Year: 2016 / Flags: Dubbed, IMAX, UHD / Source: Bluray / Format: x265 / Resolution: 2160p / Audio: Dolby Atmos, Dolby trueHD / Language: German, Multilingual / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Wonder.Woman.1984.2020.IMAX.German.UHDBD.2160p.DV.HDR10.HEVC.TrueHD.DL.Remux-pmHD",
                "0DAY"
            ),
            "Title: Wonder Woman 1984 / Group: pmHD / Year: 2020 / Flags: Dolby Vision, HDR10, IMAX, Remux / Source: UHDBD / Format: HEVC / Resolution: 2160p / Audio: Dolby trueHD / Language: German, Multilingual / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Cloudy.With.A.Chance.Of.Meatballs.2009.NORDIC.DTS-HD.DTS.AC3.NORDICSUBS.1080p.BluRay.x264-TUSAHD",
                "X264"
            ),
            "Title: Cloudy With A Chance Of Meatballs / Group: TUSAHD / Year: 2009 / Flags: Subbed / Source: Bluray / Format: x264 / Resolution: 1080p / Audio: AC3, DTS, DTS-HD / Language: Nordic / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Angel.Heart.1987.German.DTSMAD.5.1.DL.2160p.UHD.BluRay.HDR.DV.HEVC.Remux-HDSource",
                "BLuray"
            ),
            "Title: Angel Heart / Group: HDSource / Year: 1987 / Flags: Dolby Vision, HDR, Remux, UHD / Source: Bluray / Format: HEVC / Resolution: 2160p / Audio: DTS-HD MA, 5.1 / Language: German, Multilingual / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Animals.United.2010.Audiopack.Dk.No.Fi.DVDRip.XviD-DiGiCo",
                "XViD"
            ),
            "Title: Animals United / Group: DiGiCo / Year: 2010 / Flags: Audiopack / Source: DVDRip / Format: XViD / Language: Danish, Finnish, Norwegian / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Intruders.Die.Aliens.Sind.Unter.Uns.1992.Uncut.German.AC3.DVDRiP.XviD",
                "x265"
            ),
            "Title: Intruders Die Aliens Sind Unter Uns / Group: NOGRP / Year: 1992 / Flags: Uncut / Source: DVDRip / Format: XViD / Audio: AC3 / Language: German / Type: Movie",
        ],

        [
            new ReleaseParser(
                "Full.Metal.Panic.Eps.01-02.INTERNAL.SVCD.DVDrip.DUBBED.DIRFIX-USAnime",
                "SVCD"
            ),
            "Title: Full Metal Panic / Group: USAnime / Episode: 01-02 / Flags: DIRFiX, Dubbed, Internal / Source: DVDRip / Format: SVCD / Type: TV",
        ],

        [
            new ReleaseParser(
                "24.Twenty.Four.S2.E07.German.DVDRiP.Line.Dubbed.SVCD-SOF",
                "SVCD"
            ),
            "Title: 24 Twenty Four / Group: SOF / Season: 2 / Episode: 7 / Flags: Line dubbed / Source: DVDRip / Format: SVCD / Language: German / Type: TV",
        ],

        [
            new ReleaseParser(
                "24.S02E02.9.00.Uhr.bis.10.00.Uhr.German.DL.TV.Dubbed.DVDRip.SVCD.READ.NFO-c0nFuSed",
                "TV"
            ),
            "Show: 24 / Title: 9 00 Uhr bis 10 00 Uhr / Group: c0nFuSed / Season: 2 / Episode: 2 / Flags: READNFO, TV Dubbed / Source: DVDRip / Format: SVCD / Language: German, Multilingual / Type: TV",
        ],

        [
            new ReleaseParser(
                "Direct.Talk.S09E09.Mizutani.Yoshihiro.Relief.Beds.Made.of.Cardboard.1080p.HDTV.H264-DARKFLiX",
                "tv"
            ),
            "Show: Direct Talk / Title: Mizutani Yoshihiro Relief Beds Made of Cardboard / Group: DARKFLiX / Season: 9 / Episode: 9 / Source: HDTV / Format: h264 / Resolution: 1080p / Type: TV",
        ],

        [
            new ReleaseParser(
                "Dark.Net.S01E06.DOC.SUBFRENCH.720p.WEBRip.x264-TiMELiNE",
                "tv"
            ),
            "Title: Dark Net / Group: TiMELiNE / Season: 1 / Episode: 6 / Flags: Docu, Subbed / Source: WEB / Format: x264 / Resolution: 720p / Type: TV",
        ],

        [
            new ReleaseParser(
                "The.X-Files.2x14.Die.Hand.Die.Verletzt.DVDRip.XviD.MultiDub-VeLVeT",
                "tv"
            ),
            "Show: The X-Files / Title: Die Hand Die Verletzt / Group: VeLVeT / Season: 2 / Episode: 14 / Flags: Dubbed / Source: DVDRip / Format: XViD / Type: TV",
        ],

        [
            new ReleaseParser(
                "Riverdale.US.S05.PROPER.FRENCH.WEB.x264-STRINGERBELL",
                "tv"
            ),
            "Title: Riverdale US / Group: STRINGERBELL / Season: 5 / Flags: Proper / Source: WEB / Format: x264 / Language: French / Type: TV",
        ],

        [
            new ReleaseParser(
                "72.Cutest.Animals.S01E0.German.DL.Doku.1080p.WEB.x264-BiGiNT",
                "tv"
            ),
            "Title: 72 Cutest Animals / Group: BiGiNT / Season: 1 / Episode: 0 / Flags: Docu / Source: WEB / Format: x264 / Resolution: 1080p / Language: German, Multilingual / Type: TV",
        ],

        [
            new ReleaseParser(
                "Gilmore.Girls.S05E01.720p.WEB-DL.AAC2.0.H.264-tK",
                "tv"
            ),
            "Title: Gilmore Girls / Group: tK / Season: 5 / Episode: 1 / Source: WEB / Format: h264 / Resolution: 720p / Audio: AAC, 2.0 / Type: TV",
        ],

        [
            new ReleaseParser(
                "Halo.2022.S01E06.POLISH.720p.WEB.H264-A4O",
                "tv"
            ),
            "Title: Halo / Group: A4O / Year: 2022 / Season: 1 / Episode: 6 / Source: WEB / Format: h264 / Resolution: 720p / Language: Polish / Type: TV",
        ],

        [
            new ReleaseParser(
                "New.Amsterdam.2018.S02E13.In.the.Graveyard.1080p.AMZN.Webrip.x265.10bit.EAC3.5.1.JBENTTAoE",
                "tv"
            ),
            "Show: New Amsterdam / Title: In the Graveyard / Group: NOGRP / Year: 2018 / Season: 2 / Episode: 13 / Source: Amazon / Format: x265 / Resolution: 1080p / Audio: 10BIT, EAC3, 5.1 / Type: TV",
        ],

        [
            new ReleaseParser(
                "New.Amsterdam.2018.S02E12.14.Years,.2.Months,.8.Days.1080p.AMZN.Webrip.x265.10bit.EAC3.5.1.JBENTTAoE",
                "tv"
            ),
            "Show: New Amsterdam / Title: 14 Years 2 Months 8 Days / Group: NOGRP / Year: 2018 / Season: 2 / Episode: 12 / Source: Amazon / Format: x265 / Resolution: 1080p / Audio: 10BIT, EAC3, 5.1 / Type: TV",
        ],

        [
            new ReleaseParser(
                "Tulsa.King.S01D03.German.ML.COMPLETE.PAL.DVD9-NAiB",
                "DVDR"
            ),
            "Title: Tulsa King / Group: NAiB / Season: 1 / Disc: 3 / Flags: Complete / Source: DVD / Format: DVDR / Resolution: PAL / Language: German, Multilingual / Type: TV",
        ],

        [
            new ReleaseParser(
                "4x4.Ule.ja.Umber.Autoga.Colombias.S01E09.EE.1080p.WEB.h264-EMX",
                "PRE"
            ),
            "Title: 4x4 Ule ja Umber Autoga Colombias / Group: EMX / Season: 1 / Episode: 9 / Source: WEB / Format: h264 / Resolution: 1080p / Language: Estonian / Type: TV",
        ],

        [
            new ReleaseParser(
                "Speer.Et.Hitler.L.Architecte.Du.Diable.E03.FINAL.DOC.FRENCH.PDTV.XViD-BAWLS",
                "tv-xvid"
            ),
            "Title: Speer Et Hitler L Architecte Du Diable / Group: BAWLS / Episode: 3 / Flags: Docu, Final / Source: PDTV / Format: XViD / Language: French / Type: TV",
        ],

        [
            new ReleaseParser(
                "Friends.Saison5.Episode5-8.vo.subtitlefrench.DVDRIP.DivX-RoToTo",
                "NDS"
            ),
            "Title: Friends / Group: RoToTo / Season: 5 / Episode: 5-8 / Flags: Subbed / Source: DVDRip / Format: DiVX / Type: TV",
        ],

        [
            new ReleaseParser(
                "NFL.2021.09.26.49ers.Vs.Packers.1080p.WEB.h264-SPORTSNET",
                "tv"
            ),
            "Name: NFL / Title: 49ers Vs. Packers / Group: SPORTSNET / Year: 2021 / Date: 26.09.2021 / Source: WEB / Format: h264 / Resolution: 1080p / Type: Sports",
        ],

        [
            new ReleaseParser(
                "Formula1.2021.Russian.Grand.Prix.Highlights.1080p.HDTV.H264-DARKSPORT",
                "tv"
            ),
            "Name: Formula1 / Title: Russian Grand Prix Highlights / Group: DARKSPORT / Year: 2021 / Source: HDTV / Format: h264 / Resolution: 1080p / Type: Sports",
        ],

        [
            new ReleaseParser(
                "WWE.Friday.Night.Smackdown.2021-09-10.German.HDTVRiP.x264-SPORTY",
                "tv"
            ),
            "Name: WWE Friday Night Smackdown / Group: SPORTY / Year: 2021 / Date: 10.09.2021 / Source: HDTV / Format: x264 / Language: German / Type: Sports",
        ],

        [
            new ReleaseParser(
                "Formula1.2023.Hungarian.Grand.Prix.Practice.Two.1080p.WEB.h264-VERUM",
                "X264"
            ),
            "Name: Formula1 / Title: Hungarian Grand Prix Practice Two / Group: VERUM / Year: 2023 / Source: WEB / Format: h264 / Resolution: 1080p / Type: Sports",
        ],

        [
            new ReleaseParser(
                "MLB.2023.08.09.New.York.Yankees.vs.Chicago.White.Sox.720p.WEB.h264-SPORTSNET",
                "TV-X264"
            ),
            "Name: MLB / Title: New York Yankees vs. Chicago White Sox / Group: SPORTSNET / Year: 2023 / Date: 09.08.2023 / Source: WEB / Format: h264 / Resolution: 720p / Type: Sports",
        ],

        [
            new ReleaseParser(
                "Bastard.Episode4.Dubbed.OAV.DVDRip.XviD-DVDiSO",
                "XVID"
            ),
            "Title: Bastard / Group: DVDiSO / Episode: 4 / Flags: Dubbed, OVA / Source: DVDRip / Format: XViD / Type: Anime",
        ],
        [
            new ReleaseParser(
                "Kurokos.Basketball.2nd.Season.NG-shuu.Specials.E06.German.Subbed.2014.ANiME.BDRiP.x264-STARS",
                "anime"
            ),
            "Title: Kurokos Basketball 2nd Season NG-shuu Specials / Group: STARS / Year: 2014 / Episode: 6 / Flags: Anime, Subbed / Source: BDRip / Format: x264 / Language: German / Type: Anime",
        ],
        [
            new ReleaseParser(
                "Pokemon.23.Der.Film.Geheimnisse.des.Dschungels.German.2020.ANiME.DL.EAC3D.1080p.BluRay.x264-STARS",
                "anime"
            ),
            "Title: Pokemon 23 Der Film Geheimnisse des Dschungels / Group: STARS / Year: 2020 / Flags: Anime / Source: Bluray / Format: x264 / Resolution: 1080p / Audio: EAC3D / Language: German, Multilingual / Type: Anime",
        ],

        [
            new ReleaseParser(
                "The.Dells.vs.The.Dramatics.LP-(1974)-diss",
                "MP3"
            ),
            "Title: The Dells vs. The Dramatics LP / Group: diss / Year: 1974 / Type: Music",
        ],

        [
            new ReleaseParser("Zenhiser.Prophet.5.FX.WAV-SONiTUS", "0day"),
            "Title: Zenhiser Prophet 5 FX / Group: SONiTUS / Format: WAV / Type: Music",
        ],

        [
            new ReleaseParser(
                "Victoria_feat._Sledge-Wanna_Be_(More_Than_Your_Lover)-(DRR-20-1_CD-M)-CDM-FLAC-1998-WRE",
                "mp3"
            ),
            "Artist: Victoria feat. Sledge / Title: Wanna Be (More Than Your Lover) / Group: WRE / Year: 1998 / Source: Maxi CD / Format: FLAC / Type: Music",
        ],

        [
            new ReleaseParser(
                "Meschino_-_Romeo-SINGLE-WEB-IT-2023-UOVA",
                "MP3"
            ),
            "Artist: Meschino / Song: Romeo / Group: UOVA / Year: 2023 / Source: Web Single / Language: Italian / Type: Music",
        ],

        [
            new ReleaseParser("(eiffel_65)-blue_cd_beryl-bpm", "mp3"),
            "Artist: (eiffel 65) / Title: blue / Group: bpm / Source: CD / Type: Music",
        ],

        [
            new ReleaseParser(
                "Velarde_x_Luque_x_Vitti_feat_Giovanna_-_Serious_Emotions_2k21_(2nd_Remixes_Pack)-(GR670)-WEB-2021-ZzZz",
                "mp3"
            ),
            "Artist: Velarde x Luque x Vitti feat. Giovanna / Title: Serious Emotions 2k21 (2nd Remixes Pack) / Group: ZzZz / Year: 2021 / Source: WEB / Type: Music",
        ],

        [
            new ReleaseParser(
                "Metallica-the_memory_remains_2-1997-ccmp3d",
                "mp3"
            ),
            "Artist: Metallica / Title: the memory remains 2 / Group: ccmp3d / Year: 1997 / Type: Music",
        ],

        [
            new ReleaseParser(
                "Statik-Drum_and_Bass_Classic_Mix-9th_January_2001-sour",
                "mp3"
            ),
            "Artist: Statik / Title: Drum and Bass Classic Mix / Group: sour / Year: 2001 / Date: 09.01.2001 / Type: Music",
        ],

        [
            new ReleaseParser(
                "Kraftwerk_-_Live_in_Melbourne_(Australia_29.01.2003)-2CD-Bootleg-2003-BFHMP3",
                "mp3"
            ),
            "Artist: Kraftwerk / Title: Live in Melbourne (Australia 29 01 2003) / Group: BFHMP3 / Year: 2003 / Date: 29.01.2003 / Source: Bootleg / Type: Music",
        ],

        [
            new ReleaseParser(
                "Gilles_Peterson--BBC_Radio_6_Music-DVBS-08-05-2023-OMA",
                "mp3"
            ),
            "Artist: Gilles Peterson / Title: BBC Radio 6 Music / Group: OMA / Year: 2023 / Date: 08.05.2023 / Source: DVB / Type: Music",
        ],

        [
            new ReleaseParser(
                "Vicetone_-_The_World_Has_A_Heartbeat_(Incl._Extended_Mix)-(30022913)-WEB-2023-JUSTiFY_iNT",
                "mp3"
            ),
            "Artist: Vicetone / Title: The World Has A Heartbeat (Incl. Extended Mix) / Group: JUSTiFY_iNT / Year: 2023 / Source: WEB / Type: Music",
        ],

        [
            new ReleaseParser(
                "Victoria_feat._Sledge-Wanna_Be_(More_Than_Your_Lover)-(DRR-20-1_CD-M)-CDM-FLAC-1998-WRE",
                "mp3"
            ),
            "Artist: Victoria feat. Sledge / Title: Wanna Be (More Than Your Lover) / Group: WRE / Year: 1998 / Source: Maxi CD / Format: FLAC / Type: Music",
        ],

        [
            new ReleaseParser(
                "VA-The_Collapse_Of_Future_Vol_10_Part_1-TCOF10P1-WEB-2023-WAV",
                "mp3"
            ),
            "Artist: Various / Title: The Collapse Of Future Vol. 10 Part 1 / Group: WAV / Year: 2023 / Source: WEB / Type: Music",
        ],

        [
            new ReleaseParser(
                "IDW.-.Witch.And.Wizard.Battle.For.Shadowland.2012.Hybrid.Comic.eBook-BitBook",
                "ebook"
            ),
            "Author: IDW / Title: Witch And Wizard Battle For Shadowland / Group: BitBook / Year: 2012 / Flags: Comic, eBook / Format: Hybrid / Type: eBook",
        ],

        [
            new ReleaseParser(
                "La.Gazzetta.Dello.Sport.09.28.2021.iTALiAN.RETAiL.eBook-DiVER",
                "ebook"
            ),
            "Title: La Gazzetta Dello Sport / Group: DiVER / Year: 2021 / Date: 28.09.2021 / Flags: eBook, Retail / Language: Italian / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Scootering.September.2021.HYBRiD.MAGAZiNE.eBook-PAPERCLiPS",
                "ebook"
            ),
            "Title: Scootering / Group: PAPERCLiPS / Year: 2021 / Date: 01.09.2021 / Flags: eBook, Magazine / Format: Hybrid / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Viper.Comics.-.Ichabod.Jones.Monster.Hunter.No.04.2012.Hybrid.Comic.eBook-BitBook",
                "ebook"
            ),
            "Author: Viper Comics / Title: Ichabod Jones Monster Hunter / Group: BitBook / Year: 2012 / Issue: 4 / Flags: Comic, eBook / Format: Hybrid / Type: eBook",
        ],

        [
            new ReleaseParser(
                "EDGE.No.314.2018.HYBRiD.MAGAZiNE.eBook-PAPERCLiPS",
                "ebook"
            ),
            "Title: EDGE / Group: PAPERCLiPS / Year: 2018 / Issue: 314 / Flags: eBook, Magazine / Format: Hybrid / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Prog.N119.2021.RETAiL.MAGAZiNE.eBook-PRiNTER",
                "ebook"
            ),
            "Title: Prog / Group: PRiNTER / Year: 2021 / Issue: 119 / Flags: eBook, Magazine, Retail / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Die.Enwor.Saga.Band.05.-.Das.Schwarze.Schiff.German.Ebook-Elements",
                "ebook"
            ),
            "Author: Die Enwor Saga / Title: Das Schwarze Schiff / Group: Elements / Issue: 5 / Flags: eBook / Language: German / Type: eBook",
        ],

        [
            new ReleaseParser(
                "The.Amazing.Spiderman.Issue.501.January.2004.Comic.eBook-Dementia",
                "ebook"
            ),
            "Title: The Amazing Spiderman / Group: Dementia / Year: 2004 / Date: 01.01.2004 / Issue: 501 / Flags: Comic, eBook / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Simpsons.Comics.Ausgabe.15.Januar.1998.German.Comic.eBook-HS",
                "ebook"
            ),
            "Title: Simpsons Comics / Group: HS / Year: 1998 / Date: 01.01.1998 / Issue: 15 / Flags: Comic, eBook / Language: German / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Concorde.-.Die.Traumer.15.Januar.2004.Presseheft.German.Ebook-Elements",
                "ebook"
            ),
            "Author: Concorde / Title: Die Traumer / Group: Elements / Year: 2004 / Date: 15.01.2004 / Flags: eBook / Language: German / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Gerd.Postel.-.Gestaendnisse.Eines.Falschen.Doktors.German.Ebook-Elements",
                "ebook"
            ),
            "Author: Gerd Postel / Title: Gestaendnisse Eines Falschen Doktors / Group: Elements / Flags: eBook / Language: German / Type: eBook",
        ],

        [
            new ReleaseParser(
                "IDW.-.Machete.No.0.2010.Hybrid.Comic.eBook-BitBook",
                "ebook"
            ),
            "Author: IDW / Title: Machete / Group: BitBook / Year: 2010 / Issue: 0 / Flags: Comic, eBook / Format: Hybrid / Type: eBook",
        ],

        [
            new ReleaseParser(
                "Otfried_Preussler_-_Der_Hotzenplotz_Geht_Um-F04-(Audiobook)-DE-2001-S8",
                "Abook"
            ),
            "Author: Otfried Preussler / Title: Der Hotzenplotz Geht Um / Group: S8 / Year: 2001 / Episode: 4 / Flags: ABook / Language: German / Type: ABook",
        ],

        [
            new ReleaseParser("York-2CD-ABOOK-DE-2001-sUppLeX", "abook"),
            "Title: York / Group: sUppLeX / Year: 2001 / Flags: ABook / Source: CD / Language: German / Type: ABook",
        ],

        [
            new ReleaseParser(
                "Die_Drei_Fragezeichen--Folge_212_und_der_weisse_Leopard-AUDIOBOOK-WEB-DE-2021-OMA",
                "abook"
            ),
            "Author: Die Drei Fragezeichen / Title: und der weisse Leopard / Group: OMA / Year: 2021 / Episode: 212 / Flags: ABook / Source: WEB / Language: German / Type: ABook",
        ],

        [
            new ReleaseParser(
                "Perry_Rhodan-SE_117_Duell_Der_Erbfeinde-ABOOK-DE-MP3CD-2021-FLUiD",
                "abook"
            ),
            "Author: Perry Rhodan / Title: Duell Der Erbfeinde / Group: FLUiD / Year: 2021 / Episode: 117 / Flags: ABook / Source: MP3 CD / Language: German / Type: ABook",
        ],

        [
            new ReleaseParser(
                "Jan_Tenner_Der_Neue_Superheld-F20_Rueckkehr_Ins_Reich_Der_Azzarus-Audiobook-DE-2021-VOiCE",
                "abook"
            ),
            "Author: Jan Tenner Der Neue Superheld / Title: Rueckkehr Ins Reich Der Azzarus / Group: VOiCE / Year: 2021 / Episode: 20 / Flags: ABook / Language: German / Type: ABook",
        ],

        [
            new ReleaseParser(
                "NaughtyAmerica.com_17.10.12.Sloan.Harper.Sean.Lawless.Dirty.Wives.Club.XXX.IMAGESET-FuGLi",
                "imgset"
            ),
            "Publisher: NaughtyAmerica.com / Title: Sloan Harper Sean Lawless Dirty Wives Club / Group: FuGLi / Year: 2017 / Date: 12.10.2017 / Flags: Imageset, XXX / Type: XXX",
        ],

        [
            new ReleaseParser(
                "Lustery.E727.Leo.And.Madly.A.Happy.Ending.For.Him.XXX.VERTICAL.HRp.MP4-WRB",
                "XXX"
            ),
            "Publisher: Lustery / Title: Leo And Madly A Happy Ending For Him / Group: WRB / Episode: 727 / Flags: HR, Vertical, XXX / Format: MP4 / Type: XXX",
        ],

        [
            new ReleaseParser(
                "John_Mayer-Wild_Blue_(The_Late_Show_2021-09-23)-DDC-1080p-x264-2021-SRPx",
                "mvid"
            ),
            "Artist: John Mayer / Title: Wild Blue (The Late Show 2021-09-23) / Group: SRPx / Year: 2021 / Date: 23.09.2021 / Source: DDC / Format: x264 / Resolution: 1080p / Type: MusicVideo",
        ],

        [
            new ReleaseParser(
                "David_Guetta_ft_Nicki_Minaj_and_FloRida-Where_Them_Girls_At_(Americas_Got_Talent_08-31-11)-HDTV-720p-X264-2011-2LC",
                "mvid"
            ),
            "Artist: David Guetta ft. Nicki Minaj and FloRida / Title: Where Them Girls At (Americas Got Talent 08-31-11) / Group: 2LC / Year: 2011 / Date: 31.08.2011 / Source: HDTV / Format: x264 / Resolution: 720p / Type: MusicVideo",
        ],
    ];

    $i = 1;

    foreach ($tests as $test) {
        if ($test[0] == $test[1]) {
            $output = !empty($test[0]->get("title_extra"))
                ? $test[0]->get("title") .
                    " $white/$reset " .
                    $test[0]->get("title_extra")
                : $test[0]->get("title");
            echo "$green($i)$bold$greenlight ✓ Passed:$reset $output" .
                \PHP_EOL;
        } else {
            echo "$redlight($i)$bold$red ✘ Failed:$reset " .
                $test[0]->get("release") .
                "\n$greenlightbg$black Right > $reset $test[1]\n$redbg$black Wrong > $reset $test[0]\n";
        }

        $i++;

        \usleep(15000);
    }

    echo \PHP_EOL .
        "All tests finished in " .
        \round(\microtime(\true) - $start_time, 4) .
        "s" .
        \PHP_EOL;
}

function release_parser_test_single()
{
    echo \PHP_EOL .
        "Starting ReleaseParser Single test ..." .
        \PHP_EOL .
        \PHP_EOL;
    $release_name = "Jack_Dangers--Sounds_of_the_20th_Century_No2-VLS-2001-DPS";
    $release_section = "NDS";
    $release = new ReleaseParser($release_name, $release_section);

    echo "[Original] " . $release_name . \PHP_EOL;
    echo "  [Parsed] " . $release . \PHP_EOL;

    \print_r($release);
}

function release_parser_test_file()
{
    $handle = \fopen(__DIR__ . "/releases.txt", "r");

    if ($handle) {
        $counter = 1;
        while (($line = \fgets($handle)) !== \false) {
            echo $counter . " > " . $line;
            echo $counter .
                " > " .
                new ReleaseParser($line) .
                \PHP_EOL .
                \PHP_EOL;
            $counter++;
        }

        fclose($handle);
    } else {
        echo "Failed to open the file";
    }
}

echo $pre_start;
release_parser_test();
echo $pre_end;
