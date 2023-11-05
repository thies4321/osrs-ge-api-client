<?php

declare(strict_types=1);

namespace OsrsGeApiClient\HttpClient\Util\Converter;

use OsrsGeApiClient\Entity\Activity;
use OsrsGeApiClient\Entity\Hiscore;
use OsrsGeApiClient\Entity\Skill;

use function array_slice;
use function explode;

use const PHP_EOL;

final class TextHiscore
{
    private const SKILL_MAPPING = [
        'Overall',
        'Attack',
        'Defence',
        'Strength',
        'Hitpoints',
        'Ranged',
        'Prayer',
        'Magic',
        'Cooking',
        'Woodcutting',
        'Fletching',
        'Fishing',
        'Firemaking',
        'Crafting',
        'Smithing',
        'Mining',
        'Herblore',
        'Agility',
        'Thieving',
        'Slayer',
        'Farming',
        'Runecrafting',
        'Hunter',
        'Construction',
    ];

    private const ACITIVITY_MAPPING = [
        'League Points',
        'Deadman Points',
        'Bounty Hunter - Hunter',
        'Bounty Hunter - Rogue',
        'Bounty Hunter (Legacy) - Hunter',
        'Bounty Hunter (Legacy) - Rogue',
        'Clue Scrolls (all)',
        'Clue Scrolls (beginner)',
        'Clue Scrolls (easy)',
        'Clue Scrolls (medium)',
        'Clue Scrolls (hard)',
        'Clue Scrolls (elite)',
        'Clue Scrolls (master)',
        'LMS - Rank',
        'PvP Arena - Rank',
        'Soul Wars Zeal',
        'Rifts closed',
        'Abyssal Sire',
        'Alchemical Hydra',
        'Artio',
        'Barrows Chests',
        'Bryophyta',
        'Callisto',
        'Cal\'varion',
        'Cerberus',
        'Chambers of Xeric',
        'Chambers of Xeric: Challenge Mode',
        'Chaos Elemental',
        'Chaos Fanatic',
        'Commander Zilyana',
        'Corporeal Beast',
        'Crazy Archaeologist',
        'Dagannoth Prime',
        'Dagannoth Rex',
        'Dagannoth Supreme',
        'Deranged Archaeologist',
        'Duke Sucellus',
        'General Graardor',
        'Giant Mole',
        'Grotesque Guardians',
        'Hespori',
        'Kalphite Queen',
        'King Black Dragon',
        'Kraken',
        'Kree\'Arra',
        'K\'ril Tsutsaroth',
        'Mimic',
        'Nex',
        'Nightmare',
        'Phosani\'s Nightmare',
        'Obor',
        'Phantom Muspah',
        'Sarachnis',
        'Scorpia',
        'Skotizo',
        'Spindel',
        'Tempoross',
        'The Gauntlet',
        'The Corrupted Gauntlet',
        'The Leviathan',
        'The Whisperer',
        'Theatre of Blood',
        'Theatre of Blood: Hard Mode',
        'Thermonuclear Smoke Devil',
        'Tombs of Amascut',
        'Tombs of Amascut: Expert Mode',
        'TzKal-Zuk',
        'TzTok-Jad',
        'Vardorvis',
        'Venenatis',
        'Vet\'ion',
        'Vorkath',
        'Wintertodt',
        'Zalcano',
        'Zulrah',
    ];

    public static function decode(string $text): Hiscore
    {
        $data = explode(PHP_EOL, $text); //24
        $hiscores = [];

        foreach ($data as $hiscore) {
            $hiscores[] = explode(',', $hiscore);
        }

        $skills = array_slice($hiscores, 0, 24);
        $activities = array_slice($hiscores, 24);

        $skillObjects = [];
        foreach ($skills as $key => $skill) {
            $skillObjects[] = new Skill(
                self::SKILL_MAPPING[$key],
                $skill[0] === '-1' ? null : (int) $skill[0],
                (int) $skill[1],
                (int) $skill[2],
            );
        }

        $activityObjects = [];
        foreach ($activities as $key => $activity) {
            if (empty($activity[0])) {
                continue;
            }

            $activityObjects[] = new Activity(
                self::ACITIVITY_MAPPING[$key],
                $activity[0] === '-1' ? null : (int) $activity[0],
                $activity[1] === '-1' ? null : (int) $activity[1],
            );
        }

        return new Hiscore($skillObjects, $activityObjects);
    }
}
