<?php
namespace ReleaseParser;

require_once __DIR__ . "/ReleasePatterns.php";

class ReleaseParser extends ReleasePatterns
{
    private $release = "";

    public $data = [
        "release" => \null,
        "title" => \null,
        "title_extra" => \null,
        "group" => \null,
        "year" => \null,
        "date" => \null,
        "season" => \null,
        "episode" => \null,
        "disc" => \null,
        "flags" => \null,
        "source" => \null,
        "format" => \null,
        "resolution" => \null,
        "audio" => \null,
        "device" => \null,
        "os" => \null,
        "version" => \null,
        "language" => \null,
        "type" => \null,
    ];

    public function __construct(string $release_name, string $section = "")
    {
        $this->release = $release_name;
        $this->set("release", $this->release);

        $this->parseGroup();
        $this->parseFlags();
        $this->parseOs();
        $this->parseDevice();
        $this->parseVersion();
        $this->parseEpisode();
        $this->parseSeason();
        $this->parseDate();
        $this->parseYear();
        $this->parseFormat();
        $this->parseSource();
        $this->parseResolution();
        $this->parseAudio();
        $this->parseLanguage();

        $this->parseSource();
        $this->parseFlags();
        $this->parseLanguage();

        $this->parseType($section);
        $this->parseTitle();
        $this->cleanupAttributes();
    }

    public function __toString(): string
    {
        $class_to_string = "";
        $type = \strtolower($this->get("type"));

        foreach ($this->get("all") as $information => $information_value) {
            if ($information === "release" || $information === "debug") {
                continue;
            }

            if (!empty($this->get("title_extra"))) {
                if ($information === "title") {
                    if ($type === "ebook" || $type === "abook") {
                        $information = "Author";
                    } elseif ($type === "music" || $type === "musicvideo") {
                        $information = "Artist";
                    } elseif ($type === "tv" || $type === "anime") {
                        $information = "Show";
                    } elseif ($type === "xxx") {
                        $information = "Publisher";
                    } else {
                        $information = "Name";
                    }
                } elseif ($information === "title_extra") {
                    if (
                        $this->hasAttribute(
                            ["CD Single", "Web Single", "VLS"],
                            "source"
                        )
                    ) {
                        $information = "Song";
                    } elseif (
                        $this->hasAttribute(
                            ["CD Album", "Vynil", "LP"],
                            "source"
                        )
                    ) {
                        $information = "Album";
                    } elseif ($this->hasAttribute(["EP", "CD EP"], "source")) {
                        $information = "EP";
                    } else {
                        $information = "Title";
                    }
                }
            } else {
                if ($type === "sports" && $information === "title") {
                    $information = "Name";
                }
            }

            if ($type === "ebook" && $information === "episode") {
                $information = "Issue";
            }

            if (isset($information_value)) {
                $values = "";

                if ($information_value instanceof \DateTime) {
                    $values = $information_value->format("d.m.Y");
                } else {
                    $values = \is_array($information_value)
                        ? $values . \implode(", ", $information_value)
                        : $information_value;
                }

                if (!empty($class_to_string)) {
                    $class_to_string .= " / ";
                }

                $class_to_string .= \ucfirst($information) . ": " . $values;
            }
        }

        return $class_to_string;
    }

    public function __debugInfo()
    {
        return $this->get("all");
    }

    private function parseLanguage()
    {
        $language_codes = [];

        $regex_pattern = $this->cleanupPattern(
            $this->release,
            self::REGEX_LANGUAGE,
            [
                "audio",
                "device",
                "flags",
                "format",
                "group",
                "os",
                "resolution",
                "source",
                "year",
            ]
        );

        foreach (self::LANGUAGES as $language_code_key => $language_name) {
            if (!\is_array($language_name)) {
                $language_name = [$language_name];
            }

            foreach ($language_name as $name) {
                $regex = \str_replace(
                    "%language_pattern%",
                    $name,
                    $regex_pattern
                );

                \preg_match($regex, $this->release, $matches);

                if (preg_last_error() && \str_contains($regex, "?<!")) {
                    echo $regex . PHP_EOL;
                }

                if (!empty($matches)) {
                    $language_codes[] = $language_code_key;
                }
            }
        }

        if (!empty($language_codes)) {
            $languages = [];

            foreach ($language_codes as $language_code) {
                $language = self::LANGUAGES[$language_code];

                if (\is_array($language)) {
                    $language = self::LANGUAGES[$language_code][0];
                }

                $languages[$language_code] = $language;
            }

            $this->set("language", $languages);
        }
    }

    private function parseDate()
    {
        \preg_match(
            "/[._\(-]" . self::REGEX_DATE . "[._\)-]/i",
            $this->release,
            $matches
        );

        $day = $month = $year = $temp = $date = "";

        if (!empty($matches)) {
            $year = (int) $matches[1];
            $month = (int) $matches[2];
            $day = (int) $matches[3];

            if (\preg_match(self::REGEX_DATE_MUSIC, $this->release)) {
                $temp = $year;
                $year = $day;
                $day = $temp;
            }

            if (\strlen((string) $day) == 4) {
                $temp = $year;
                $year = $day;
                $day = $temp;
            }

            if ($month > 12) {
                $temp = $day;
                $day = $month;
                $month = $temp;
            }

            if (\strlen((string) $year) == 2) {
                $year_new = 0;
                try {
                    $year_new = \DateTime::createFromFormat("y", $year);
                } catch (\Exception $e) {
                    \trigger_error(
                        "Datetime Error (Year): " .
                            $year .
                            " / rls: " .
                            $this->release
                    );
                }

                if (!empty($year_new)) {
                    $year = $year_new->format("Y");
                }
            }

            $date = $day . "." . $month . "." . $year;

            try {
                $this->set("date", \DateTime::createFromFormat("d.m.Y", $date));
            } catch (\Exception $e) {
                \trigger_error(
                    "Datetime Error (Date): " .
                        $date .
                        " / rls: " .
                        $this->release
                );
            }
        } else {
            $release_name_cleaned = $this->cleanup($this->release, "episode");

            $all_months = \implode("|", self::MONTHS);

            $regex_pattern = \str_replace(
                "%monthname%",
                $all_months,
                self::REGEX_DATE_MONTHNAME
            );

            \preg_match_all(
                "/[._-]" . $regex_pattern . "[._-]/i",
                $release_name_cleaned,
                $matches
            );

            $last_result_key = $day = $month = $year = "";

            if (!empty($matches[0])) {
                $last_result_key = array_key_last($matches[0]);

                $day = 1;
                if (!empty($matches[1][$last_result_key])) {
                    $day = $matches[1][$last_result_key];
                } elseif (!empty($matches[3][$last_result_key])) {
                    $day = $matches[3][$last_result_key];
                } elseif (!empty($matches[5][$last_result_key])) {
                    $day = $matches[5][$last_result_key];
                }

                $month = $matches[2][$last_result_key];

                $year = $matches[4][$last_result_key];

                foreach (self::MONTHS as $month_number => $month_pattern) {
                    \preg_match("/" . $month_pattern . "/i", $month, $matches);

                    if (!empty($matches)) {
                        $month = $month_number;
                        break;
                    }
                }

                $date = $day . "." . $month . "." . $year;

                try {
                    $this->set(
                        "date",
                        \DateTime::createFromFormat("d.m.Y", $date)
                    );
                } catch (\Exception $e) {
                    \trigger_error(
                        "Datetime Error (Date): " .
                            $date .
                            " / rls: " .
                            $this->release
                    );
                }
            }
        }
    }

    private function parseYear()
    {
        $release_name_cleaned = $this->cleanup($this->release, "version");

        \preg_match_all(self::REGEX_YEAR, $release_name_cleaned, $matches);

        if (!empty($matches[1])) {
            $year = \end($matches[1]);
            $year = \is_numeric($year) ? (int) $year : $this->sanitize($year);
            $this->set("year", $year);
        } elseif (!empty($this->get("date"))) {
            $this->set("year", $this->get("date")->format("Y"));
        }
    }

    private function parseDevice()
    {
        $device = "";

        $release_name_cleaned = $this->cleanup($this->release, ["flags", "os"]);

        foreach (self::DEVICE as $device_name => $device_pattern) {
            if (!\is_array($device_pattern)) {
                $device_pattern = [$device_pattern];
            }

            foreach ($device_pattern as $pattern) {
                \preg_match(
                    "/[._-]" . $pattern . '-\w+$/i',
                    $release_name_cleaned,
                    $matches
                );

                if (!empty($matches)) {
                    $device = $device_name;
                    break;
                }
            }
        }

        if (!empty($device)) {
            $this->set("device", $device);
        }
    }

    private function parseFlags()
    {
        $flags = $this->parseAttribute(self::FLAGS, "flags");

        if (!empty($flags)) {
            $flags = !\is_array($flags) ? [$flags] : $flags;
            $this->set("flags", $flags);
        }
    }

    private function parseGroup()
    {
        \preg_match(self::REGEX_GROUP, $this->release, $matches);

        if (!empty($matches[1])) {
            $this->set("group", $matches[1]);
        } else {
            $this->set("group", "NOGRP");
        }
    }

    private function parseVersion()
    {
        $release_name_cleaned = $this->cleanup($this->release, [
            "flags",
            "device",
        ]);
        $regex_pattern = "/[._-]" . self::REGEX_VERSION . "[._-]/i";
        \preg_match($regex_pattern, $release_name_cleaned, $matches);
        if (!empty($matches)) {
            $this->set("version", \trim($matches[1], "."));
        }
    }

    private function parseSource()
    {
        $source = $this->parseAttribute(self::SOURCE, "source");

        if (!empty($source)) {
            $source = \is_array($source) ? \reset($source) : $source;
            $this->set("source", $source);
        }
    }

    private function parseFormat()
    {
        $format = $this->parseAttribute(self::FORMAT, "format");

        if (!empty($format)) {
            $format = \is_array($format) ? \reset($format) : $format;
            $this->set("format", $format);
        }
    }

    private function parseResolution()
    {
        $resolution = $this->parseAttribute(self::RESOLUTION);

        if (!empty($resolution)) {
            $resolution = \is_array($resolution)
                ? \reset($resolution)
                : $resolution;
            $this->set("resolution", $resolution);
        }
    }

    private function parseAudio()
    {
        $audio = $this->parseAttribute(self::AUDIO);
        if (!empty($audio)) {
            $this->set("audio", $audio);
        }
    }

    private function parseOs()
    {
        $os = $this->parseAttribute(self::OS);
        if (!empty($os)) {
            $this->set("os", $os);
        }
    }

    private function parseSeason()
    {
        \preg_match(self::REGEX_SEASON, $this->release, $matches);

        if (!empty($matches)) {
            $season = !empty($matches[1]) ? $matches[1] : \null;
            $season =
                empty($season) && !empty($matches[2]) ? $matches[2] : $season;

            if (isset($season)) {
                $this->set("season", (int) $season);
            }
        }
    }

    private function parseEpisode()
    {
        \preg_match(
            "/[._-]" . self::REGEX_EPISODE . "[._-]/i",
            $this->release,
            $matches
        );

        if (!empty($matches)) {
            $episode =
                isset($matches[1]) && $matches[1] != "" ? $matches[1] : \null;
            $episode =
                !isset($episode) && isset($matches[2]) && $matches[2] != ""
                    ? $matches[2]
                    : $episode;

            if (isset($episode)) {
                if (\is_numeric($episode) && $episode !== "0") {
                    $episode = (int) $episode;
                } else {
                    $episode = $this->sanitize(
                        \str_replace(["_", ".", "a", "A"], "-", $episode)
                    );
                }
                $this->set("episode", $episode);
            }
        } else {
            \preg_match(
                "/[._-]" . self::REGEX_DISC . "[._-]/i",
                $this->release,
                $matches
            );

            if (!empty($matches)) {
                $this->set("disc", (int) $matches[1]);
            }
        }
    }

    private function isSports()
    {
        foreach (self::SPORTS as $sport) {
            if (\preg_match("/^" . $sport . "[._-]/i", $this->release)) {
                return \true;
            }
        }

        return \false;
    }

    private function parseType(string &$section)
    {
        $type = $this->guessTypeByParsedAttributes();

        $type = empty($type) ? $this->guessTypeBySection($section) : $type;

        $type = empty($type) ? "Movie" : $type;

        $this->set("type", $type);
    }

    private function guessTypeByParsedAttributes(): string
    {
        $type = "";

        if ($this->isSports()) {
            $type = "Sports";
        } elseif ($this->hasAttribute("ABOOK", "flags")) {
            $type = "ABook";
        } elseif (
            $this->hasAttribute(self::SOURCES_MUSIC, "source") ||
            $this->hasAttribute(self::FLAGS_MUSIC, "flags")
        ) {
            $type = "Music";
        } elseif ($this->hasAttribute(self::FLAGS_EBOOK, "flags")) {
            $type = "eBook";
        } elseif ($this->hasAttribute(self::FLAGS_ANIME, "flags")) {
            $type = "Anime";
        } elseif ($this->hasAttribute(self::FLAGS_XXX, "flags")) {
            $type = "XXX";
        } elseif (
            !empty($this->get("episode")) ||
            !empty($this->get("season")) ||
            $this->hasAttribute(self::SOURCES_TV, "source")
        ) {
            $type = "TV";

            if (\preg_match(self::REGEX_DATE_MUSIC, $this->get("release"))) {
                $type = "MusicVideo";
            }
        } elseif (\preg_match(self::REGEX_DATE_MUSIC, $this->get("release"))) {
            if (!empty($this->get("resolution"))) {
                $type = "MusicVideo";
            } else {
                $type = "Music";
            }
        } elseif (
            !empty($this->get("date")) &&
            !empty($this->get("resolution"))
        ) {
            $type = "TV";
        } elseif ($this->hasAttribute(self::FORMATS_MVID, "format")) {
            $type = "MusicVideo";
        } elseif ($this->hasAttribute(self::FLAGS_MOVIE, "flags")) {
            $type = "Movie";
        } elseif (
            $this->hasAttribute(self::FORMATS_MUSIC, "format") &&
            $this->get("version") === \null
        ) {
            $type = "Music";
        } elseif ($this->hasAttribute(["FONT", "FONTSET"], "flags")) {
            $type = "Font";
        } elseif (
            !empty($this->get("device")) ||
            $this->hasAttribute(self::FLAGS_GAMES, "flags") ||
            $this->hasAttribute(self::SOURCES_GAMES, "source")
        ) {
            $type = "Game";
        } elseif (
            (!empty($this->get("os")) ||
                !empty($this->get("version")) ||
                $this->hasAttribute(self::FLAGS_APPS, "flags")) &&
            !$this->hasAttribute(self::FORMATS_VIDEO, "format")
        ) {
            $type = "App";
        }

        return $type;
    }

    private function guessTypeBySection(string &$section): string
    {
        $type = "";

        if (!empty($section)) {
            foreach (self::TYPE as $type_parent_key => $type_value) {
                if (!\is_array($type_value)) {
                    $type_value = [$type_value];
                }

                foreach ($type_value as $value) {
                    \preg_match("/" . $value . "/i", $section, $matches);

                    if (!empty($matches)) {
                        $type = $type_parent_key;
                        break;
                    }
                }

                if (!empty($type)) {
                    break;
                }
            }
        }

        return $type;
    }

    private function parseTitle()
    {
        $type = \strtolower($this->get("type"));
        $release_name_cleaned = \str_replace(",", "", $this->release);

        $title = $title_extra = \null;

        $regex_pattern = $regex_used = "";

        switch ($type) {
            case "music":
            case "abook":
            case "musicvideo":
                $regex_pattern = self::REGEX_TITLE_MUSIC;
                $regex_used = "REGEX_TITLE_MUSIC";

                if ($type === "abook") {
                    $regex_pattern = self::REGEX_TITLE_ABOOK;
                    $regex_used = "REGEX_TITLE_ABOOK";
                } elseif ($type === "musicvideo") {
                    $regex_pattern = self::REGEX_TITLE_MVID;
                    $regex_used = "REGEX_TITLE_MVID";
                }

                $regex_pattern = $this->cleanupPattern(
                    $this->release,
                    $regex_pattern,
                    ["audio", "flags", "format", "group", "language", "source"]
                );

                if (
                    !\preg_match(self::REGEX_DATE_MUSIC, $release_name_cleaned)
                ) {
                    $regex_pattern = $this->cleanupPattern(
                        $this->release,
                        $regex_pattern,
                        ["regex_date", "regex_date_monthname", "year"]
                    );
                }

                \preg_match($regex_pattern, $release_name_cleaned, $matches);

                if (!empty($matches)) {
                    $title = $matches[1];

                    $title_splitted = \explode("-", $title);

                    if (!empty($title_splitted)) {
                        $title = $this->cleanup(
                            "." . $title_splitted[0],
                            "episode"
                        );

                        unset($title_splitted[0]);

                        $separator = $type === "abook" ? " - " : "-";

                        $i = 0;
                        foreach ($title_splitted as $title_part) {
                            $title_part = $this->cleanup(
                                "." . $title_part . ".",
                                "episode"
                            );
                            $title_part = \trim($title_part, ".");

                            if (!empty($title_part)) {
                                if (
                                    $i === 0 ||
                                    ($i > 0 &&
                                        (\str_contains($title_part, "_") ||
                                            \str_contains($title_part, ")") ||
                                            \is_numeric($title_part)))
                                ) {
                                    $title_extra = !empty($title_extra)
                                        ? $title_extra .
                                            $separator .
                                            $title_part
                                        : $title_part;
                                }
                            }

                            $i++;
                        }
                    }
                    break;
                }

                if (empty($title)) {
                    goto standard;
                }

            case "game":
            case "app":
                $regex_pattern = self::REGEX_TITLE_APP;
                $regex_used = "REGEX_TITLE_APP";

                $regex_pattern = $this->cleanupPattern(
                    $release_name_cleaned,
                    $regex_pattern,
                    ["device", "os"]
                );

                \preg_match($regex_pattern, $release_name_cleaned, $matches);

                if (!empty($matches)) {
                    $title = $matches[1];
                    break;
                }

                if (empty($title)) {
                    goto standard;
                }

            case "tv":
            case "sports":
            case "docu":
                $regex_pattern = self::REGEX_TITLE_TV;
                $regex_used = "REGEX_TITLE_TV";

                $release_name_no_year = $this->cleanup($release_name_cleaned, [
                    "year",
                ]);

                \preg_match($regex_pattern, $release_name_no_year, $matches);

                if (!empty($matches)) {
                    $title = $matches[1];

                    $regex_pattern = self::REGEX_TITLE_TV_EPISODE;
                    $regex_used .= " + REGEX_TITLE_TV_EPISODE";

                    $release_name_cleaned = $this->cleanup(
                        $release_name_cleaned,
                        [
                            "audio",
                            "flags",
                            "format",
                            "language",
                            "resolution",
                            "source",
                        ]
                    );

                    \preg_match(
                        $regex_pattern,
                        $release_name_cleaned,
                        $matches
                    );

                    $title_extra =
                        !empty($matches[1]) && $matches[1] !== "."
                            ? $matches[1]
                            : "";

                    if (\is_numeric($title_extra)) {
                        if (
                            \strlen($title_extra) <= 2 &&
                            \str_contains(
                                $this->get("episode"),
                                (int) $title_extra
                            )
                        ) {
                            $title_extra = "";
                        }
                    }

                    break;
                } else {
                    $regex_pattern = self::REGEX_TITLE_TV_DATE;
                    $regex_used = "REGEX_TITLE_TV_DATE";

                    $regex_pattern = $this->cleanupPattern(
                        $this->release,
                        $regex_pattern,
                        [
                            "flags",
                            "format",
                            "language",
                            "resolution",
                            "source",
                            "regex_date",
                            "year",
                        ]
                    );

                    \preg_match(
                        $regex_pattern,
                        $release_name_cleaned,
                        $matches
                    );

                    if (!empty($matches)) {
                        $title = $matches[1];

                        $title_extra =
                            !empty($matches[2]) && $matches[2] !== "."
                                ? $matches[2]
                                : "";

                        break;
                    }
                }

                if (empty($title)) {
                    goto standard;
                }

            case "anime":
                $regex_pattern = self::REGEX_TITLE_TV;
                $regex_used = "REGEX_TITLE_TV";

                \preg_match($regex_pattern, $release_name_cleaned, $matches);

                if (!empty($matches)) {
                    $title = $matches[1];

                    $regex_pattern = self::REGEX_TITLE_TV_EPISODE;
                    $regex_used .= " + REGEX_TITLE_TV_EPISODE";

                    $regex_pattern = $this->cleanupPattern(
                        $this->release,
                        $regex_pattern,
                        ["flags", "format", "language", "resolution", "source"]
                    );

                    \preg_match(
                        $regex_pattern,
                        $release_name_cleaned,
                        $matches
                    );

                    $title_extra = !empty($matches[1]) ? $matches[1] : "";

                    break;
                }

                if (empty($title)) {
                    goto standard;
                }

            case "xxx":
                $matches = [];

                if (!empty($this->get("episode"))) {
                    $regex_pattern = self::REGEX_TITLE_TV;
                    $regex_used = "REGEX_TITLE_TV";

                    \preg_match(
                        $regex_pattern,
                        $release_name_cleaned,
                        $matches
                    );

                    if (!empty($matches)) {
                        $title = $matches[1];

                        $regex_pattern = self::REGEX_TITLE_TV_EPISODE;
                        $regex_used .= " + REGEX_TITLE_TV_EPISODE";

                        $release_name_cleaned = $this->cleanup(
                            $release_name_cleaned,
                            [
                                "audio",
                                "flags",
                                "format",
                                "language",
                                "resolution",
                                "source",
                            ]
                        );

                        \preg_match(
                            $regex_pattern,
                            $release_name_cleaned,
                            $matches
                        );

                        $title_extra =
                            !empty($matches[1]) && $matches[1] !== "."
                                ? $matches[1]
                                : "";

                        break;
                    }
                }

                if (empty($title)) {
                    $regex_pattern = !empty($this->get("date"))
                        ? self::REGEX_TITLE_XXX_DATE
                        : self::REGEX_TITLE_XXX;
                    $regex_used = !empty($this->get("date"))
                        ? "REGEX_TITLE_XXX_DATE"
                        : "REGEX_TITLE_XXX";

                    $regex_pattern = $this->cleanupPattern(
                        $this->release,
                        $regex_pattern,
                        [
                            "flags",
                            "year",
                            "language",
                            "source",
                            "regex_date",
                            "regex_date_monthname",
                        ]
                    );

                    \preg_match(
                        $regex_pattern,
                        $release_name_cleaned,
                        $matches
                    );

                    if (!empty($matches)) {
                        $title = $matches[1];

                        $title_extra = !empty($matches[2]) ? $matches[2] : "";

                        break;
                    }
                }

                if (empty($title)) {
                    goto standard;
                }

            case "ebook":
                $regex_pattern = self::REGEX_TITLE_EBOOK;
                $regex_used = "REGEX_TITLE_EBOOK";

                $release_name_cleaned = $this->cleanup(
                    $release_name_cleaned,
                    "episode"
                );

                $regex_pattern = $this->cleanupPattern(
                    $this->release,
                    $regex_pattern,
                    [
                        "flags",
                        "format",
                        "language",
                        "regex_date",
                        "regex_date_monthname",
                        "year",
                    ]
                );

                \preg_match($regex_pattern, $release_name_cleaned, $matches);

                if (!empty($matches)) {
                    $title = $matches[1];

                    $title_splitted = \explode("-", $title);

                    if (!empty($title_splitted)) {
                        $title = $title_splitted[0];

                        unset($title_splitted[0]);

                        foreach ($title_splitted as $title_part) {
                            if (!empty($title_part)) {
                                $title_extra = !empty($title_extra)
                                    ? $title_extra . " - " . $title_part
                                    : $title_part;
                            }
                        }
                    }
                    break;
                }

                if (empty($title)) {
                    goto standard;
                }

            case "font":
                $regex_pattern = self::REGEX_TITLE_FONT;
                $regex_used = "REGEX_TITLE_FONT";

                $release_name_cleaned = $this->cleanup($release_name_cleaned, [
                    "version",
                    "os",
                    "format",
                ]);

                \preg_match($regex_pattern, $release_name_cleaned, $matches);

                if (!empty($matches)) {
                    $title = $matches[1];
                    break;
                }

                if (empty($title)) {
                    goto standard;
                }

            default:
                standard:

                $regex_pattern = self::REGEX_TITLE_TV_DATE;
                $regex_used = "REGEX_TITLE_TV_DATE";

                $regex_pattern = $this->cleanupPattern(
                    $this->release,
                    $regex_pattern,
                    ["flags", "format", "language", "resolution", "source"]
                );

                if ($type === "xxx") {
                    $release_name_cleaned = $this->cleanup(
                        $release_name_cleaned,
                        ["episode", "monthname", "daymonth"]
                    );
                }

                $regex_pattern = \str_replace(
                    "%dateformat%",
                    "(?:\d+[._-]){3}",
                    $regex_pattern
                );

                \preg_match($regex_pattern, $release_name_cleaned, $matches);

                if (!empty($matches) && !empty($matches[2])) {
                    $title = $matches[1];

                    $title_extra = $matches[2];
                } else {
                    $regex_pattern = self::REGEX_TITLE_MOVIE;
                    $regex_used = "REGEX_TITLE_MOVIE";

                    $regex_pattern = $this->cleanupPattern(
                        $this->release,
                        $regex_pattern,
                        [
                            "flags",
                            "format",
                            "language",
                            "resolution",
                            "source",
                            "year",
                            "audio",
                        ]
                    );

                    \preg_match(
                        $regex_pattern,
                        $release_name_cleaned,
                        $matches
                    );

                    if (!empty($matches)) {
                        $title = $matches[1];
                    } else {
                        $regex_pattern = self::REGEX_TITLE;
                        $regex_used = "REGEX_TITLE";

                        if (str_contains($release_name_cleaned, "-")) {
                            $regex_pattern .= "-";
                        }

                        \preg_match(
                            "/^" . $regex_pattern . "/i",
                            $release_name_cleaned,
                            $matches
                        );

                        $title = !empty($matches) ? $matches[1] : "";
                    }
                }
        }

        $this->set("debug", $regex_used . ": " . $regex_pattern);

        $title = $this->sanitize($title);
        $title = $title === "VA" ? "Various" : $title;
        $this->set("title", $title);

        $title_extra = empty($title_extra) ? \null : $title_extra;
        if (isset($title_extra)) {
            $this->set("title_extra", $this->sanitize($title_extra));
        }
    }

    private function parseAttribute(array $attribute, string $type = "")
    {
        $attribute_keys = [];

        foreach ($attribute as $attr_key => $attr_pattern) {
            if (!\is_array($attr_pattern)) {
                $attr_pattern = [$attr_pattern];
            }

            foreach ($attr_pattern as $pattern) {
                $flags = "i";

                if ($type === "source" && $pattern === "iT") {
                    $flags = "";
                }

                if ($type === "flags") {
                    if (
                        $attr_key === "Final" ||
                        $attr_key === "New" ||
                        $attr_key === "V2" ||
                        $attr_key === "V3" ||
                        $attr_key === "Cover" ||
                        $attr_key === "Docu" ||
                        $attr_key === "HR" ||
                        $attr_key === "Vertical"
                    ) {
                        $pattern = $this->cleanupPattern(
                            $this->release,
                            $pattern,
                            [
                                "flags",
                                "format",
                                "source",
                                "language",
                                "resolution",
                            ]
                        );
                    }
                }

                $separators = "[._-]";

                $regex_pattern =
                    "/(" . $separators . ")" . $pattern . '\1/' . $flags;

                \preg_match($regex_pattern, $this->release, $matches);

                if (empty($matches)) {
                    $regex_pattern =
                        "/" . $separators . $pattern . '-\w+$/' . $flags;
                    \preg_match($regex_pattern, $this->release, $matches);
                }

                if (empty($matches)) {
                    $regex_pattern = "/\(" . $pattern . "\)/" . $flags;
                    \preg_match($regex_pattern, $this->release, $matches);
                }

                if (empty($matches) && $type === "format") {
                    $regex_pattern = "/[._]" . $pattern . '$/' . $flags;
                    \preg_match($regex_pattern, $this->release, $matches);
                }

                if (
                    !empty($matches) &&
                    !\in_array($attr_key, $attribute_keys)
                ) {
                    $attribute_keys[] = $attr_key;
                }
            }
        }

        if (!empty($attribute_keys)) {
            if (\count($attribute_keys) == 1) {
                $attribute_keys = \implode($attribute_keys);
            }

            return $attribute_keys;
        }
        return \null;
    }

    public function hasAttribute($values, $attribute_name)
    {
        $attribute = $this->get($attribute_name);

        if (isset($attribute)) {
            if (!\is_array($values)) {
                $values = [$values];
            }

            foreach ($values as $value) {
                if (\is_array($attribute)) {
                    foreach ($attribute as $attr_value) {
                        if (\strtolower($value) === \strtolower($attr_value)) {
                            return \true;
                        }
                    }
                } else {
                    if (\strtolower($value) === \strtolower($attribute)) {
                        return \true;
                    }
                }
            }
        }

        return \false;
    }

    private function cleanup(string $release_name, $informations): string
    {
        if (empty($informations) || empty($release_name)) {
            return $release_name;
        }

        if (!\is_array($informations)) {
            $informations = [$informations];
        }

        foreach ($informations as $information) {
            $information_value = $this->get($information);

            if (
                str_contains($information, "month") ||
                str_contains($information, "date")
            ) {
                $information_value = $this->get("date");
            }

            if (isset($information_value) && $information_value != "") {
                $attributes = [];

                switch ($information) {
                    case "audio":
                        if (\is_array($information_value)) {
                            foreach ($information_value as $audio) {
                                $attributes[] = self::AUDIO[$audio];
                            }
                        } else {
                            $attributes[] = self::AUDIO[$information_value];
                        }
                        break;

                    case "daymonth":
                        $attributes = [
                            $information_value->format("d") . "(th|rd|nd|st)?",
                            $information_value->format("j") . "(th|rd|nd|st)?",
                            $information_value->format("m"),
                        ];
                        break;

                    case "device":
                        $attributes[] = self::DEVICE[$information_value];
                        break;

                    case "format":
                        if (\is_array($information_value)) {
                            foreach ($information_value as $format) {
                                $attributes[] = self::FORMAT[$format];
                            }
                        } else {
                            $attributes[] = self::FORMAT[$information_value];
                        }
                        break;

                    case "episode":
                        $attributes[] = self::REGEX_EPISODE;
                        break;

                    case "flags":
                        foreach ($information_value as $flag) {
                            if ($flag != "UPDATE" && $flag != "3D") {
                                $attributes[] = self::FLAGS[$flag];
                            }
                        }
                        break;

                    case "language":
                        foreach (
                            $information_value
                            as $language_code_key => $language
                        ) {
                            $attributes[] = self::LANGUAGES[$language_code_key];
                        }
                        break;

                    case "monthname":
                        $monthname = \preg_replace(
                            "/\((?!\?)/i",
                            "(?:",
                            self::REGEX_DATE_MONTHNAME
                        );

                        $monthname = \str_replace(
                            "%monthname%",
                            self::MONTHS[$information_value->format("n")],
                            $monthname
                        );
                        $attributes[] = $monthname;
                        break;

                    case "os":
                        if (\is_array($information_value)) {
                            foreach ($information_value as $value) {
                                $attributes[] = self::OS[$value];
                            }
                        } else {
                            $attributes[] = self::OS[$information_value];
                        }
                        break;

                    case "resolution":
                        $attributes[] = self::RESOLUTION[$information_value];
                        break;

                    case "source":
                        $attributes[] = self::SOURCE[$information_value];
                        break;

                    case "version":
                        $attributes[] = self::REGEX_VERSION;
                        break;

                    case "year":
                        $attributes[] = self::REGEX_YEAR_SIMPLE;
                        break;
                }

                if (!empty($attributes)) {
                    foreach ($attributes as $attribute) {
                        $attribute = !\is_array($attribute)
                            ? [$attribute]
                            : $attribute;
                        foreach ($attribute as $value) {
                            if ($information === "os") {
                                $value = "(?:for[._-])?" . $value;
                            }

                            $release_name = \preg_replace(
                                "/[._(-]" . $value . "[._)-]/i",
                                "..",
                                $release_name
                            );

                            if ($information === "format") {
                                $release_name = \preg_replace(
                                    "/[._]" . $value . '$/i',
                                    "..",
                                    $release_name
                                );
                            }
                        }
                    }
                }
            }
        }

        return $release_name;
    }

    private function cleanupPattern(
        string $release_name,
        string $regex_pattern,
        $informations
    ): string {
        if (
            empty($informations) ||
            empty($release_name) ||
            empty($regex_pattern)
        ) {
            return $regex_pattern;
        }

        if (!\is_array($informations)) {
            $informations = [$informations];
        }

        foreach ($informations as $information) {
            $information_value = $this->get($information);

            if (
                str_contains($information, "month") ||
                str_contains($information, "date")
            ) {
                $information_value = $this->get("date");
            }

            if (isset($information_value) && $information_value != "") {
                $attributes = [];

                switch ($information) {
                    case "audio":
                        if (\is_array($information_value)) {
                            foreach ($information_value as $audio) {
                                $attributes[] = self::AUDIO[$audio];
                            }
                        } else {
                            $attributes[] = self::AUDIO[$information_value];
                        }
                        break;

                    case "device":
                        if (\is_array($information_value)) {
                            foreach ($information_value as $device) {
                                $attributes[] = self::DEVICE[$device];
                            }
                        } else {
                            $attributes[] = self::DEVICE[$information_value];
                        }
                        break;

                    case "flags":
                        foreach ($information_value as $flag) {
                            if ($flag != "3D") {
                                $attributes[] = self::FLAGS[$flag];
                            }
                        }
                        break;

                    case "format":
                        if (\is_array($information_value)) {
                            foreach ($information_value as $format) {
                                $attributes[] = self::FORMAT[$format];
                            }
                        } else {
                            $attributes[] = self::FORMAT[$information_value];
                        }
                        break;

                    case "group":
                        $attributes[] = $information_value;
                        break;

                    case "language":
                        $language_code = array_key_first($information_value);
                        $attributes[] = self::LANGUAGES[$language_code];
                        break;

                    case "os":
                        if (\is_array($information_value)) {
                            foreach ($information_value as $value) {
                                $attributes[] = self::OS[$value];
                            }
                        } else {
                            $attributes[] = self::OS[$information_value];
                        }
                        break;

                    case "resolution":
                        $attributes[] = self::RESOLUTION[$information_value];
                        break;

                    case "regex_date":
                        $attributes[] = \preg_replace(
                            "/\((?!\?)/i",
                            "(?:",
                            self::REGEX_DATE
                        );
                        break;

                    case "regex_date_monthname":
                        $regex_date_monthname = \preg_replace(
                            "/\((?!\?)/i",
                            "(?:",
                            self::REGEX_DATE_MONTHNAME
                        );

                        $regex_date_monthname = \str_replace(
                            "%monthname%",
                            self::MONTHS[$information_value->format("n")],
                            $regex_date_monthname
                        );
                        $attributes[] = $regex_date_monthname;

                        break;

                    case "source":
                        $attributes[] = self::SOURCE[$information_value];
                        break;

                    case "year":
                        $attributes[] = $information_value;
                        break;
                }

                if (!empty($attributes)) {
                    $values = "";

                    foreach ($attributes as $attribute) {
                        $attribute = !\is_array($attribute)
                            ? [$attribute]
                            : $attribute;
                        foreach ($attribute as $value) {
                            $value =
                                $information === "os"
                                    ? "(?:for[._-])?" . $value
                                    : $value;

                            $values = !empty($values)
                                ? $values . "|" . $value
                                : $value;
                        }
                    }

                    $regex_pattern = \str_replace(
                        "%" . $information . "%",
                        $values,
                        $regex_pattern
                    );
                }
            }
        }

        return $regex_pattern;
    }

    private function cleanupAttributes()
    {
        $type = \strtolower($this->get("type"));

        if ($type === "movie") {
            if ($this->get("version") !== \null) {
                $this->set("version", \null);
            }
        } elseif ($type === "app") {
            if ($this->get("audio") !== \null) {
                $this->set("audio", \null);
            }

            if (
                $this->get("source") !== null &&
                \str_contains($this->get("title"), $this->get("source"))
            ) {
                $this->set("source", null);
            }
        } elseif ($type === "ebook") {
            if ($this->get("format") === "Hybrid") {
                $flags = $this->get("flags");
                if (($key = array_search("Hybrid", $flags)) !== \false) {
                    unset($flags[$key]);
                }
                $this->set("flags", $flags);
            }
        } elseif ($type === "music") {
            if (!empty($this->get("episode"))) {
                $this->set("episode", \null);
            }
            if (!empty($this->get("season"))) {
                $this->set("season", \null);
            }
        }

        if (
            $type !== "app" &&
            $type !== "game" &&
            $this->hasAttribute("TRAiNER", "flags")
        ) {
            $flags = $this->get("flags");
            if (($key = array_search("TRAiNER", $flags)) !== \false) {
                unset($flags[$key]);
            }
            $this->set("flags", $flags);
        }
    }

    private function sanitize(string $text): string
    {
        if (!empty($text)) {
            $text = \trim($text, "-");

            $text = \str_replace(["_", "."], " ", $text);

            $text = \trim(\preg_replace("/\s{2,}/i", " ", $text));

            if (\str_word_count($text) > 1) {
                $text_temp = \str_replace(["-", " "], "", $text);
                if (\ctype_upper($text_temp)) {
                    $text = \ucwords(\strtolower($text));
                }
            }

            $type = !empty($this->get("type")) ? $this->get("type") : "";

            $special_words_after = [
                "feat",
                "ft",
                "incl",
                "(incl",
                "inkl",
                "nr",
                "st",
                "pt",
                "vol",
            ];
            if (\strtolower($type) != "app") {
                $special_words_after[] = "vs";
            }

            $special_words_before = [];
            if (\strtolower($type) === "xxx") {
                $special_words_before = ["com", "net", "pl"];
            }

            $text_splitted = \explode(" ", $text);

            if (\is_array($text_splitted)) {
                foreach ($text_splitted as $text_word) {
                    if (
                        \in_array(\strtolower($text_word), $special_words_after)
                    ) {
                        $text = \str_replace(
                            $text_word,
                            $text_word . ".",
                            $text
                        );
                    } elseif (
                        \in_array(
                            \strtolower($text_word),
                            $special_words_before
                        )
                    ) {
                        $text = \str_replace(
                            " " . $text_word,
                            "." . $text_word,
                            $text
                        );
                    }
                }
            }
        }

        return $text;
    }

    public function get(string $name = "all")
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } elseif ($name === "all") {
            return $this->data;
        }

        return \null;
    }

    private function set(string $name, $value)
    {
        if (\array_key_exists($name, $this->data)) {
            $value = \is_array($value) && empty($value) ? \null : $value;
            $this->data[$name] = $value;
            return \true;
        }
        return \false;
    }
}
