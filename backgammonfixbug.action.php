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
 * backgammon.action.php
 *
 * backgammon main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/backgammon/backgammon/myAction.html", ...)
 *
 */


class action_backgammon extends APP_GameAction
{
// Constructor: please do not modify
    public function __default()
    {
        if( self::isArg( 'notifwindow') )
        {
            $this->view = "common_notifwindow";
            $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
        }
        else
        {
            $this->view = "backgammon_backgammon";
            self::trace( "Complete reinitialization of board game" );
        }
    }

    /**
     * Ajax function, player has validate is move cliking on column
     */
    public function moveChecker()
    {
        self::setAjaxMode();
        $startColNum = self::getArg('start_col_num', AT_int, true);
        $destinationColNum = self::getArg('destination_col_num', AT_int, true);
        $playerId = self::getArg('player_id', AT_int, true);
        $diceUsedIds = self::getArg('dices_used', AT_int, true);

        $this->game->moveCheckerGame($startColNum, $destinationColNum, $playerId, $diceUsedIds);

        self::ajaxResponse();
    }

    /**
     * Player cant play
     */
    public function cantPlayFromJs()
    {
        self::setAjaxMode();
        $this->game->cantPlayFromJsGame();
        self::ajaxResponse();
    }

}


