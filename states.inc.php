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
 * states.inc.php
 *
 * backgammonfixbug game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!

$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => clienttranslate("Game setup"),
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array( "playerTurn" => 10 )
    ),

    // 10 - Player turn
    10 => array(
        "name" => "playerTurn",
        "type" => "game",
        "action" => "stPlayerTurn",
        "updateGameProgression" => true,
        "transitions" => array( "selectColumn" => 20,  "nextPlayer" => 40)
    ),

    // 20 - Select checker's column to move a checker
    20 => array(
        "name" => "selectColumn",
        "description" => clienttranslate('${actplayer} must select a column.'),
        "descriptionmyturn" => clienttranslate('${you} must select a column.'),
        "type" => "activeplayer",
        "args" => "argSelectColumn",
        "possibleactions" => array( "selectColumn" ),    // possible actions for player
        "transitions" => array( "selectColumn" => 20, "noMoreDice" => 30 )
    ),

    // 30 - no more dice and wait for player to check done
    30 => array(
        "name" => "noMoreDice",
        "description" => clienttranslate('${actplayer} must select a column.'),
        "descriptionmyturn" => clienttranslate('${you} have no dice to use. Click "Done" to finish turn.'),
        "type" => "activeplayer",
        "transitions" => array( "rollDice" => 10, "selectColumn" => 20, "nextPlayer" => 40, "zombiePass" => 40, "gameEnd" => 99 )
    ),

    // 40 all dice have been played, next player turn
    40 => array(
        "name" => "nextPlayer",
        "type" => "game",
        "action" => "stNextPlayer",
        "transitions" => array( "playerTurn" => 10 )
    ),

    // Final state.
    // Please do not modify.
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);


