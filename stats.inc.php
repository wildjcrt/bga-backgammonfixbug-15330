<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * backgammon implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * stats.inc.php
 *
 * backgammon game statistics description
 *
 */

/*
    In this file, you are describing game statistics, that will be displayed at the end of the
    game.

    !! After modifying this file, you must use "Reload  statistics configuration" in BGA Studio backoffice ("Your game configuration" section):
    http://en.studio.boardgamearena.com/admin/studio

    There are 2 types of statistics:
    _ table statistics, that are not associated to a specific player (ie: 1 value for each game).
    _ player statistics, that are associated to each players (ie: 1 value for each player in the game).

    Statistics types can be "int" for integer, "float" for floating point values, and "bool" for boolean

    Once you defined your statistics there, you can start using "initStat", "setStat" and "incStat" method
    in your game logic, using statistics names defined below.

    !! It is not a good idea to modify this file when a game is running !!

    If your game is already public on BGA, please read the following before any change:
    http://en.doc.boardgamearena.com/Post-release_phase#Changes_that_breaks_the_games_in_progress

    Notes:
    * Statistic index is the reference used in setStat/incStat/initStat PHP method
    * Statistic index must contains alphanumerical characters and no space. Example: 'turn_played'
    * Statistics IDs must be >=10
    * Two table statistics can't share the same ID, two player statistics can't share the same ID
    * A table statistic can have the same ID than a player statistics
    * Statistics ID is the reference used by BGA website. If you change the ID, you lost all historical statistic data. Do NOT re-use an ID of a deleted statistic
    * Statistic name is the English description of the statistic as shown to players

*/


$stats_type = array(

    // Statistics global to table
    "table" => array(

        "turns_number" => array("id"=> 10,
            "name" => totranslate("Number of turns"),
            "type" => "int" ),
    ),

    // Statistics existing for each player
    "player" => array(

        "path_to_win" => array(
            "id"=> 11,
            "name" => totranslate("Remaining path"),
            "type" => "int"
        ),

        "number_of_dice" => array(
            "id"=> 21,
            "name" => totranslate("Number of dice roll"),
            "type" => "int" ),

        "double_number" => array(
            "id"=> 12,
            "name" => totranslate("Number of double"),
            "type" => "int" ),

        "number_of_checker_i_eat" => array(
            "id"=> 13,
            "name" => totranslate("Number of checkers you eat"),
            "type" => "int" ),

        "number_of_checker_eaten" => array(
            "id"=> 14,
            "name" => totranslate("Number of checkers opponent eats"),
            "type" => "int" ),

        "number_of_pass" => array(
            "id"=> 15,
            "name" => totranslate("Number of times you had to pass"),
            "type" => "int" ),

        "checkers_out" => array(
            "id"=> 16,
            "name" => totranslate("Checkers out of the board"),
            "type" => "int" ),

        "checkers_remaining_at_home_board" => array(
            "id"=> 17,
            "name" => totranslate("Checkers remaining at home board"),
            "type" => "int" ),

        "checkers_remaining_at_outer_board" => array(
            "id"=> 18,
            "name" => totranslate("Checkers remaining at outer board"),
            "type" => "int" ),

        "checkers_remaining_at_opponent_outer_board" => array(
            "id"=> 19,
            "name" => totranslate("Checkers remaining at opponent outer board"),
            "type" => "int" ),

        "checkers_remaining_at_opponent_home_board" => array(
            "id"=> 20,
            "name" => totranslate("Checkers remaining at opponent home board"),
            "type" => "int" ),


    )

);
