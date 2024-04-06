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
 * backgammonfixbug.game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 *
 */
/*
class backgammonfixbug

protected   function getGameName( )
protected   function setupNewGame( $players, $options = array() )
protected   function getAllDatas()
            function getGameProgression()
            function getBoard()
            function getNbRepositoryForPlayerId($in_player_id = 0)  * return the number of checkers in repository for player
public      function getDiceInfo()                                    *  return an array of dice info from DB dice1, dice2, dice3, dice4, dice1_usable, dice2_usable, dice3_usable, dice4_usable
private     function rollDice($forbidDouble = false)                *  Roll new dice and notify players that dice has been rolled
public      function getPlayerColor($in_player_id)                    *  return player color in DB from playerId
public      function getOtherPlayerId($in_player_id)                *  we got the id of a player, return the id of the other player
            function getPlayerPitNum($inPlayerId)                    *  return pit number for player Id 25=yellow, 26=red, 0 otherwise
            function getPlayerRepoNum($inPlayerId)                    *  return the player repository number for playerId 27=red 28=yellow 0=otherwise
            function getPitColId($in_player_id)                        *  same as getPlayerPitNum
            function getPlayerInfo($in_player_id)                    *  return an array of the player info from DB player_id, player_name, player_score, player_score_aux, player_color
public      function playerHasTokenInPit($in_player_id)                *  return true if player has token in pit
            function diceValueList()                                *  Return an array of dice values [1=>3, 2=>6, 3=>0, 4=>0]    *  If dice not usable, value is 0
            function updateBoardDb($colNum, $tokenNb, $playerId)    *  Update board DB with datas $colNum, $tokenNb, $playerId
            function diceIdsToList($diceIds)                        *  Return an array from diceIds : 123 => [1, 2, 3]
            function setDiceUnusable($diceIds)                        *  Put $diceIds unusable in DB (ex : 123)
            function existValidMoveInMoveList($moveList)            *  return true if there is a successfull move in the movelist
            function getNbTokenInPit($playerId)                        *  Return the number of token in pit from DB for player $playerId
            function getDiceListValueFromUsable($diceUsableList)    *  From list dice1, dice1_usable etc... return list of all dices    *  array(1 => 4, 2 => 3, 3 => 0, 4 => 0)    *  0 if dice is not usable
            function message($txt, $color='white')                    *  notify all player a message in a textarea
            function moveCheckerGame($startColNum, $destinationColNum, $playerId, $diceUsedIds)            *  Client send data for moving a checker
            function resetToPrevBoardJsGame()                          *  Reset board table token_nb=prev_token_nb, player_id=prev_player_id
            function onConfirmToEndTurnJsGame()                        *  Confirm to end turn, sync board records and change to next player
            function cantPlayFromJsGame()                            *  client send a cant play at JSsetup (should not happened)
            function argPlayerTurn()                                *  arg for playerTurn (no args)
            function argSelectColumn()                                *  Return arguments for the select column state : myColor pitColId mustPlayPit dice1Usable dice2Usable dice3Usable dice4Usable moveList
            function stPlayerTurn()                                    *  new player turn, roll dice, check if move avaliable and go to the appropriate state
            function stNextPlayer()                                    *  enter in state nextPlayer, change the active player and initiate his turn
            function cantPlay()                                        *  notify active player cannot play, and go to the nextPlayer state
            function zombieTurn( $state, $active_player )
            function playerWon($playerId)

class oneBoard

    public function __construct($bgObject, $inTabColumn = array(), $inTabDice = array())
    public function read()
        * fill object with DB values
    public function display()
        * return HTML code for displaying the object
    public function displayPossibleMoveForDice($inDiceValue1, $inDiceValue2, $inPlayerId)
        * return a possible move object which is an array of oneBoard
        * starting playing dice $inDiceValue1 then try to play $inDiceValue2
        * display it in HTML
    public function getPossibleMoveForTwoDice($inDiceValue1, $inDiceValue2, $inPlayerId, $objMoveOneBoard)
        * check play with 2 dices, dice are usable and not null
        * return a moveOneBoard object that give the info from col+dice move, if we can do a second move
        * modify $this update canPlay and canPlayBothDice properties of object
    public function getPossibleMoveForOneDice($inDiceValue1, $inPlayerId)
        * return an array ($movePossible=boolean, $tabres=moveList) for one dice for playerId for all the board
    public function getBoardWithMoveColDice($inColId, $inDiceValue, $inPlayerId)
        * return a oneBoard object with move from $inColId with dice $inDiceValue for player $inPlayerId
        * it s the big function engine of the game
    public function getDestinationCol($inColId, $inDiceValue, $inPlayerId)
        * return the number of the column reach from colId with the value of the dice for playerId
        * if out of the boeard, return REPO or OVER_REPO
    public function hasTokenInPit($inPlayerId)
        * return true if playerId has token in pit
    public function copyTo($inDest)
        * copy the current oneBoard object to $inDest oneBoard object
    public function allTokenHome($inPlayerId)
        * return true if every playerId token are in his home
    public function existsTokenBeyond($inPlayerId, $inColNumber)
        * return true if exist player $inPlayerId token beyond $inColNumber
        * dont check repo nor pit
    function getEveryMoveFromOneColumn($playerId, $colId, $dice1, $dice2, $dice3, $dice4)
        * Return array of possible move per dice
        * move with dice 1
        * move with dice 2
        * move with dice 12
        * move with dice 21
        * move with dice 123
        * move with dice 1234
        * for one column
    function getEveryMoveForColumns($playerId, $dice1, $dice2, $dice3, $dice4)
        * Return array of possible move per dice
        * move with dice 1
        * move with dice 2
        * move with dice 12
        * move with dice 21
        * move with dice 123
        * move with dice 1234
        * for all column
    function oneDiceOnlyMustPlayBiggerCorrection($dice1, $dice2, $moveList)
        * If you can only play one dice, either the 1 or the 2, but not both
        * you must play the bigger one
        * Not valid if checker is from pit
    function doubleDiceCorrection($moveList, $dice1, $dice2, $dice3, $dice4)
        * when double, start to use dice 4, then 3 then 2, then 1
        * return a movelist
    function mergeMoveList($moveList1, $moveList12)
        * list 1 is from (startCol) to (startCol + dice 1)
        * list 12 is from (startCol + dice 1) to (startCol + dice 1 + dice 2)
        * list result is from (startCol) to (startCol + dice 1 + dice 2)
        * Both move ARE SUCCESS


class oneColumn

    public function __construct($inCol, $playerId, $inNum)

class moveOneBoard

    function __construct($dice1Value, $dice2Value)
    function setPossibleDice($startCol, $diceValue, $isNextmovePossible)

class moveOneColumn

    function __construct($startCol, $diceValue, $isNextmovePossible = false)


*/
require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class backgammonfixbug extends Table
{
    function __construct( )
    {


        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();self::initGameStateLabels( array(
        //    "my_first_global_variable" => 10,
        //    "my_second_global_variable" => 11,
        //      ...
        //    "my_first_game_variant" => 100,
        //    "my_second_game_variant" => 101,
        //      ...
    ) );

    }

    protected function getGameName( )
    {
        return "backgammonfixbug";
    }

    /*
        setupNewGame:

        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {

        // Game configuration
        // player top / bottom
        $default_colors = array( "aa7903", "ff0000" );

        // Remove existing players
        $sql = "DELETE FROM player WHERE 1 ";
        self::DbQuery( $sql );

        // Create new players
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach ( $players as $player_id => $player ) {

            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";

            if( $color == 'ff0000' ) {
                // Bottom player is red
                $bottom_player_id = $player_id;
            } else {
                // Top player is yellow
                $top_player_id = $player_id;
            }
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );
        self::reloadPlayersBasicInfos();

        // Init the board
        $sql = "INSERT INTO board (col_id, token_nb, player_id, prev_token_nb, prev_player_id) VALUES ";
        $sql_values = array();
        for ($i=0; $i < 28; $i++) {
            $col_id = $i+1;
            switch ($col_id) {
                case 1 : // 2 yellow
                    $token_nb = 2;
                    $player_id = $top_player_id;
                    break;
                case 6 : // 5 red
                    $token_nb = 5;
                    $player_id = $bottom_player_id;
                    break;
                case 8 : // 3 red
                    $token_nb = 3;
                    $player_id = $bottom_player_id;
                    break;
                case 12 : // 5 yellow
                    $token_nb = 5;
                    $player_id = $top_player_id;
                    break;
                case 13 : // 5 red
                    $token_nb = 5;
                    $player_id = $bottom_player_id;
                    break;
                case 17 : // 3 yellow
                    $token_nb = 3;
                    $player_id = $top_player_id;
                    break;
                case 19 : // 5 yellow
                    $token_nb = 5;
                    $player_id = $top_player_id;
                    break;
                case 24 : // 2 red
                    $token_nb = 2;
                    $player_id = $bottom_player_id;
                    break;
                case 25 : // yellow pit
                    $token_nb = 0;
                    $player_id = $top_player_id;
                    break;
                case 26 : // red pit
                    $token_nb = 0;
                    $player_id = $bottom_player_id;
                    break;
                case 27 : // red repo
                    $token_nb = 0;
                    $token_nb = 0;
                    $player_id = $bottom_player_id;
                    break;
                case 28 : // yellow repo
                    $token_nb = 0;
                    $player_id = $top_player_id;
                    break;
                default : // 0 token
                    $token_nb = 0;
                    $player_id = 0;
                    break;
            }
            $sql_values[] = "($col_id, $token_nb, $player_id, $token_nb, $player_id)";
        }
        $sql .= implode( $sql_values, ',' );
        self::DbQuery( $sql );
        self::reloadPlayersBasicInfos();

        /************ Start the game initialization *****/

        // Init dice values
        $dice1 = 6;
        $dice2 = 6;
        $dice3 = 6;
        $dice4 = 6;

        // insert dice value in DB

        $sql = "INSERT INTO dice_result (dice1, dice2, dice3, dice4) VALUES ($dice1, $dice2, $dice3, $dice4) ";
        self::DbQuery( $sql );
        self::reloadPlayersBasicInfos();

        // init statistics
        self::initStat( "player", "path_to_win", 0);
        self::initStat( "player", "double_number", 0);
        self::initStat( "player", "number_of_checker_i_eat", 0);
        self::initStat( "player", "number_of_checker_eaten", 0);
        self::initStat( "player", "number_of_pass", 0);
        self::initStat( "player", "checkers_out", 15);
        self::initStat( "player", "checkers_remaining_at_home_board", 0);
        self::initStat( "player", "checkers_remaining_at_outer_board", 0);
        self::initStat( "player", "checkers_remaining_at_opponent_outer_board", 0);
        self::initStat( "player", "checkers_remaining_at_opponent_home_board", 0);
        self::initStat( "table", "turns_number", 0);


        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas:

        Gather all informations about current game situation (visible by the current player).

        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();

        // Get information about players
        $sql = "SELECT player_id id, player_name name, player_score score, player_score_aux score_aux FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );

        // Get information about placed checkers
        $sql = "SELECT col_id, token_nb, player_id FROM board";
        $result['board'] = self::getObjectListFromDB( $sql );

        // Get information about the dice roll
        $sql = "SELECT dice1, dice2, dice3, dice4, dice1_usable, dice2_usable, dice3_usable, dice4_usable FROM dice_result";
        $result['dice_result'] = self::getObjectListFromDB($sql);

        // check if player can play
        $oneBoard = new oneBoard($this);
        $oneBoard->read();

        $playerId = self::getActivePlayerId();
        $diceListValue = self::getDiceListValueFromUsable($result['dice_result'][0]);

        $moveList = $oneBoard->getEveryMoveForColumns($playerId, $diceListValue[1], $diceListValue[2], $diceListValue[3], $diceListValue[4]);
        $result['moveList'] = $moveList;
        $result['isMovePossible'] = self::existValidMoveInMoveList($moveList);

        // lengt to win for player panel
        $otherPlayerId = self::getOtherPlayerId($playerId);
        $pathToWin = array();
        $pathToWin[$playerId] = self::getLengthToWin($playerId);
        $pathToWin[$otherPlayerId] = self::getLengthToWin($otherPlayerId);

        $result['pathToWin'] = $pathToWin;

        return $result;
    }


    /*
        getGameProgression:

        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).

        This method is called each time we are in a game state with the "updateGameProgression" property set to true
        (see states.inc.php)
    */
    /**
     * getGameProgression
     *
     * Compute and return the current game progression.
     * The number returned must be an integer beween 0 (=the game just started) and
     * 100 (= the game is finished or almost finished).
     *
     * This method is called each time we are in a game state with the "updateGameProgression" property set to true
     * (see states.inc.php)
     *
     * @return the game progression in percent.
     */
    function getGameProgression()
    {
        // get both player id
        $player1_id = self::getActivePlayerId();
        $player2_id = self::getOtherPlayerId($player1_id);
        $length1 = self::getLengthToWin($player1_id);
        $length2 = self::getLengthToWin($player2_id);
        $winnerLength = $length1;
        if ($length2 < $length1) {
            $winnerLength = $length2;
        }
        $lengthDone = 167 - $winnerLength;
        $progression = ($lengthDone * 100) / 167;
        return max(0, $progression);
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

    // Get the complete board with a double associative array
    function getBoard()
    {
//        $plop = self::getCollectionFromDB( "SELECT col_id, token_nb, player_id FROM board");
//        self::notifyAllPlayers("plop","<textarea>".print_r($plop, 1)."</textarea>",array());

        return self::getCollectionFromDB( "SELECT col_id, token_nb, player_id FROM board");
    }

    /**
     * return the number of checkers in repository for player
     * @param int $in_player_id
     * @return mixed
     */
    function getNbRepositoryForPlayerId($in_player_id = 0)
    {
        if (!$in_player_id) {
            $in_player_id = self::getActivePlayerId();
        }
        $sql = "SELECT token_nb FROM board WHERE player_id = $in_player_id AND col_id >= 27";
        return self::getUniqueValueFromDB($sql);
    }

    /**
     * return an array of dice info from DB dice1, dice2, dice3, dice4, dice1_usable, dice2_usable, dice3_usable, dice4_usable
     * @return mixed
     */
    public function getDiceInfo()
    {
        $sql = "SELECT dice1, dice2, dice3, dice4, dice1_usable, dice2_usable, dice3_usable, dice4_usable FROM dice_result";
        $res = self::getObjectListFromDB($sql);

        return $res[0];

    }

    /**
     * Roll new dice and notify players that dice has been rolled
     * @param bool $forbidDouble
     */
    private function rollDice($forbidDouble = false)
    {
        self::incStat( 1, "turns_number");

        // Roll dices
        $dice1_value = bga_rand(1, 6);
        $dice2_value = bga_rand(1, 6);
//                $dice1_value = 5;
//                $dice2_value = 6;

        // if it is first turn, fordib double
        if ($forbidDouble) {
            $nbtry = 0;
            while ($dice1_value == $dice2_value && $nbtry < 1000) {
                $dice2_value = bga_rand(1, 6);
                $nbtry++;
            }
        }

        $dice3_value = 0;
        $dice4_value = 0;

        $additional_greatings_text = false;
        if ($dice1_value == $dice2_value) {
            $dice3_value = $dice1_value;
            $dice4_value = $dice1_value;
            /*
             * help for translators
             * $additional_greatings_text is empty or is "It's a double !" if player has a double
             * It is used bellow in line
             * clienttranslate( '${player_name}'." roll dice and get ${dice1_value} and ${dice2_value} $additional_greatings_text" )
             */
            $additional_greatings_text = true;
            self::incStat(1, "double_number", self::getActivePlayerId());
        }

        self::incStat(1, "number_of_dice", self::getActivePlayerId());

        // Notify all players about dice rolling
        /*
         * help for translators
         * just translate the part between "..." keep '${player_name}'. at the beginning of the line
         * " roll dice and get ${dice1_value} and ${dice2_value} $additional_greatings_text"
         * ${dice1_value} is a number, the value of the first dice, just copy it like it is : ${dice1_value}
         * ${dice2_value} is a number, the value of the second dice, just copy it like it is : ${dice2_value}
         * $additional_greatings_text : is a text that congratulate player for having a double, or it is empty
         * $additionalEaterMessage is a message telling player that he eats a checker. It should have been translated a few lines bellow
         * If player eats a checker, it is " and eats a checker, yummy !"
        */
        self::notifyAllPlayers(
            "rollDiceDone",
            clienttranslate( '${player_name} roll dice and get ${dice1_value} and ${dice2_value} ${newline} ${additional}' ),
            array(
                'i18n' => array( 'additional' ),
                'player_name' => self::getActivePlayerName(),
                'dice1_value' => $dice1_value,
                'dice2_value' => $dice2_value,
                'dice3_value' => $dice3_value,
                'dice4_value' => $dice4_value,
                'newline' => $additional_greatings_text ? '<br/>' : '',
                'additional' => $additional_greatings_text ? clienttranslate("It's a double !") : '',
        )
        );

        if ($dice1_value == $dice2_value) {
            $sql_double = ",dice3 = $dice1_value, dice4= $dice1_value";
            $sql_double_usable_init = ", dice3_usable=1, dice4_usable=1";
        } else {
            $sql_double = ',dice3=0, dice4=0';
            $sql_double_usable_init = ", dice3_usable=0, dice4_usable=0";
        }

        // save dice roll in database
        $sql = "UPDATE dice_result
                SET dice1 = $dice1_value,
                    dice2 = $dice2_value
                    $sql_double ,
                    dice1_usable=1,
                    dice2_usable=1
                    $sql_double_usable_init
                    WHERE 1";
        self::DbQuery( $sql );
        self::reloadPlayersBasicInfos();

        return array($dice1_value, $dice2_value);
    }

    /**
     * return player color in DB from playerId
     * @param $in_player_id
     * @return mixed
     */
    public function getPlayerColor($in_player_id)
    {
        $sql = "SELECT player_color FROM player WHERE player_id=$in_player_id";
        return $this->getUniqueValueFromDB( $sql );
    }


    /**
     * we got the id of a player, return the id of the other player
     * @param $in_player_id
     * @return int
     */
    public function getOtherPlayerId($in_player_id)
    {
        $sql = "SELECT player_id FROM player WHERE player_id != $in_player_id";
        return $this->getUniqueValueFromDB( $sql );
    }

    /**
     * return pit number for player Id 25=yellow, 26=red, 0 otherwise
     * @param $inPlayerId
     * @return int
     */
    function getPlayerPitNum($inPlayerId)
    {
        $playerColor = self::getPlayerColor($inPlayerId);
        if ($playerColor == 'aa7903') {
            return 25;
        } else if ($playerColor == 'ff0000') {
            return 26;
        } else {
            return 0;
        }
    }

    /**
     * return the player repository number for playerId 27=red 28=yellow 0=otherwise
     * @param $inPlayerId
     * @return int
     */
    function getPlayerRepoNum($inPlayerId)
    {
        $playerColor = self::getPlayerColor($inPlayerId);
        if ($playerColor == 'aa7903') {
            return 28;
        } else if ($playerColor == 'ff0000') {
            return 27;
        } else {
            return 0;
        }
    }

    /**
     * @param $in_player_id
     * @return int
     */
    function getPitColId($in_player_id)
    {
        return $this->getPlayerPitNum($in_player_id);
    }

    /**
     * return an array of the player info from DB player_id, player_name, player_score, player_score_aux, player_color
     * @param $in_player_id
     * @return mixed
     */
    function getPlayerInfo($in_player_id)
    {
        $sql = "SELECT player_id, player_name, player_score, player_score_aux, player_color
                FROM player
                WHERE player_id=$in_player_id";
        return $this->getObjectFromDB( $sql );
    }

    /**
     * return true if player has token in pit
     * @param $in_player_id
     * @return bool
     */
    public function playerHasTokenInPit($in_player_id)
    {
        $pit_column = self::getPitColId($in_player_id);
        $sql = "SELECT token_nb FROM board WHERE player_id=$in_player_id AND col_id=$pit_column";
        $res = $this->getObjectFromDB( $sql );
        return ($res['token_nb'] > 0);
    }

    /**
     * Return an array of dice values [1=>3, 2=>6, 3=>0, 4=>0]
     * If dice not usable, value is 0
     * @return array
     */
    function diceValueList()
    {
        $diceInfoList = self::getDiceInfo();
        $diceValueList = array();
        for ($i=1; $i <=4; $i++) {
            if ($diceInfoList['dice'.$i.'_usable']) {
                $diceValueList[$i] = $diceInfoList['dice'.$i];
            } else {
                $diceValueList[$i] = 0;
            }
        }
        return $diceValueList;
    }

    /**
     * Update board DB with datas $colNum, $tokenNb, $playerId
     * @param $colNum
     * @param $tokenNb
     * @param $playerId
     */
    function updateBoardDb($colNum, $tokenNb, $playerId)
    {
        if ($playerId == '') {
            $playerId = 0;
        }
        $sql = "UPDATE board SET token_nb=$tokenNb, player_id=$playerId WHERE col_id=$colNum;";
        self::DbQuery( $sql );
        self::reloadPlayersBasicInfos();
    }

    /**
     * Return an array from diceIds : 123 => [1, 2, 3]
     * @param diceIds $
     * @return array
     */
    function diceIdsToList($diceIds)
    {
        return str_split($diceIds, 1);
    }

    /**
     * Put dice unusable in DB
     * @param $diceIds
     */
    function setDiceUnusable($diceIds)
    {
        $usedDiceList = self::diceIdsToList($diceIds);
        for ($i=0; $i < count($usedDiceList); $i++) {
            $diceLabel = 'dice'.$usedDiceList[$i].'_usable';
            $sql = "UPDATE dice_result SET $diceLabel = 0 WHERE 1;";
            self::DbQuery($sql);
            self::reloadPlayersBasicInfos();
        }
    }

    /**
     * return true if there is a successfull move in the movelist
     * @param $moveList
     * @return mixed
     */
    function existValidMoveInMoveList($moveList)
    {
        for ($i=1; $i <= 26; $i++) {
            if( isset($moveList[$i] ))
            {
                foreach ($moveList[$i] as $diceIds => $moveInfo) {
                    if ($moveInfo['success']) {
                        return true;
                    }
                }
            }
        }
        return false;
    }


    /**
     * Return the number of token in pit for player $playerId
     * @param $playerId
     * @return mixed
     */
    function getNbTokenInPit($playerId)
    {
        $pitId = self::getPitColId($playerId);
        $sql = 'SELECT token_nb FROM board WHERE col_id='.$pitId;
        return self::getUniqueValueFromDB($sql);

    }

    /**
     * From list dice1, dice1_usable etc... return list of all dices
     * array(1 => 4, 2 => 3, 3 => 0, 4 => 0)
     * 0 if dice is not usable
     * @param $diceUsableList
     * @return array
     */
    function getDiceListValueFromUsable($diceUsableList)
    {
        $diceList = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
        if ($diceUsableList['dice1_usable']) {
            $diceList[1] = $diceUsableList['dice1'];
        }
        if ($diceUsableList['dice2_usable']) {
            $diceList[2] = $diceUsableList['dice2'];
        }
        if ($diceUsableList['dice3_usable']) {
            $diceList[3] = $diceUsableList['dice3'];
        }
        if ($diceUsableList['dice4_usable']) {
            $diceList[4] = $diceUsableList['dice4'];
        }
        return $diceList;
    }


    /**
     * Return true if player won the game
     * @param $playerId
     * @return bool
     */
    function playerWon($playerId)
    {
        $sql = 'SELECT count(*) FROM board WHERE player_id = '.$playerId.' AND token_nb > 0 AND col_id <= 26';
        $res = $this->getUniqueValueFromDB($sql);
        return ($res == 0);
    }

    /**
     * return the length checker have to walk to get active player win
     * @return int
     */
    function getLengthToWin($in_player_id) {
        $out_res = 0;
        $player_color = self::getPlayerColor($in_player_id);

        if ($player_color == 'ff0000') {
            $sql = "SELECT SUM(token_nb * col_id) FROM `board` WHERE player_id=$in_player_id AND col_id <= 24";
            $out_res += $this->getUniqueValueFromDB( $sql );
            $sql = "SELECT token_nb FROM board WHERE player_id=$in_player_id AND col_id = 26";
            $out_res += $this->getUniqueValueFromDB( $sql ) * 25;
        } else {
            $sql = "SELECT SUM(token_nb * (25 - col_id)) FROM `board` WHERE player_id=$in_player_id AND col_id <= 24";
            $out_res += $this->getUniqueValueFromDB( $sql );
            $sql = "SELECT token_nb FROM board WHERE player_id=$in_player_id AND col_id = 25";
            $out_res += $this->getUniqueValueFromDB( $sql ) * 25;
        }
        return $out_res;
    }


    // stat functions

    function getCheckerOut($inPlayerId)
    {
        return self::getNbRepositoryForPlayerId($inPlayerId);
    }

    function getStatInfo($inPlayerId, $inCode)
    {
        $playerColor = self::getPlayerColor($inPlayerId);
        $tabHome = array(19,20,21,22,23,24);
        $tabOuter = array(13,14,15,16,17,18);
        $tabOpponent = array(1,2,3,4,5,6,25);
        $tabOpponentOuter = array(7,8,9,10,11,12);

        if ($playerColor == "ff0000") {
            $tabOpponent = array(19,20,21,22,23,24,26);
            $tabOpponentOuter = array(13,14,15,16,17,18);
            $tabHome= array(1,2,3,4,5,6);
            $tabOuter= array(7,8,9,10,11,12);
        }

        $getCheckerAtHome = 0;
        $getCheckerAtOuter = 0;
        $getCheckerAtOpponentOuter = 0;
        $getCheckerAtOpponentHome = 0;

        $tabboard = self::getBoard();
        foreach ($tabboard as $boarditem) {
            if ($boarditem['player_id'] == $inPlayerId && $boarditem['token_nb'] > 0 ) {
                if (in_array($boarditem['col_id'], $tabHome)) {
                    $getCheckerAtHome += $boarditem['token_nb'];
                } else if (in_array($boarditem['col_id'], $tabOuter)) {
                    $getCheckerAtOuter += $boarditem['token_nb'];
                } else if (in_array($boarditem['col_id'], $tabOpponentOuter)) {
                    $getCheckerAtOpponentOuter += $boarditem['token_nb'];
                } else if (in_array($boarditem['col_id'], $tabOpponent)) {
                    $getCheckerAtOpponentHome += $boarditem['token_nb'];
                }
            }
        }

        switch ($inCode) {
            case "getCheckerAtHome":
                return $getCheckerAtHome;
                break;
            case "getCheckerAtOuter":
                return $getCheckerAtOuter;
                break;
            case "getCheckerAtOpponentOuter":
                return $getCheckerAtOpponentOuter;
                break;
            case "getCheckerAtOpponentHome":
                return $getCheckerAtOpponentHome;
                break;

        }
        return 0;
    }



    function getCheckerAtHome($inPlayerId)
    {
        return self::getStatInfo($inPlayerId, "getCheckerAtHome");
    }

    function getCheckerAtOuter($inPlayerId)
    {
        return self::getStatInfo($inPlayerId, "getCheckerAtOuter");
    }

    function getCheckerAtOpponentOuter($inPlayerId)
    {
        return self::getStatInfo($inPlayerId, "getCheckerAtOpponentOuter");
    }

    function getCheckerAtOpponentHome($inPlayerId)
    {
        return self::getStatInfo($inPlayerId, "getCheckerAtOpponentHome");
    }

    /**
     *
     */
    function updateScores()
    {
        // Update scores according to the number of disc on board plus the number of other player token on board
        $myId = self::getActivePlayerId();
        $otherId = self::getOtherPlayerId($myId);

        $myNbTokenRepo = self::getNbRepositoryForPlayerId($myId);
        $otherNbTokenRepo = self::getNbRepositoryForPlayerId($otherId);

//        $myScore = $myNbTokenRepo + (15 - $otherNbTokenRepo);
//        $otherScore = $otherNbTokenRepo + (15 - $myNbTokenRepo);

        $sql = "UPDATE player
                SET player_score = $myNbTokenRepo
                WHERE player_id = $myId";
        self::DbQuery($sql);

        $sql = "UPDATE player
                SET player_score = $otherNbTokenRepo
                WHERE player_id = $otherId";
        self::DbQuery($sql);

//        $newScores[$myId] = $myScore;
//        $newScores[$otherId] = $otherScore;

        $newScores[$myId] = $myNbTokenRepo;
        $newScores[$otherId] = $otherNbTokenRepo;



        self::notifyAllPlayers(
            "newScores",
            "Player scores ",
            array(
                "scores" => $newScores
            )
        );
    }

    /*
        upgradeTableDb:

        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    */

    function upgradeTableDb( $from_version ) {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345

        if( $from_version <= 1501282033 )
        {
            $sql = "SELECT player_id, token_nb FROM repository";
            $repoInfoList = $this->getObjectListFromDB($sql);

            foreach (array( "", "zz_replay1_", "zz_replay2_", "zz_replay3_", "zz_savepoint_" ) as $prefix)
            {
                for ($i = 0; $i < count($repoInfoList); $i++) {
                    $sql = "UPDATE ".$prefix."board SET token_nb = ".$repoInfoList[$i]['token_nb']."
                                WHERE col_id >= 27
                                AND player_id = ".$repoInfoList[$i]['player_id'];
                    self::DbQuery($sql);
                }

            }
        }
    }


    /**
     * For debug
     * @param $txt
     */
    function message($txt, $color='white')
    {
        self::notifyAllPlayers("plop","<textarea style='background-color:$color'>$txt</textarea>",array());
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
////////////

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in backgammonfixbug.action.php)
    */

    /**
     * Client send data for moving a checker
     * @param $startColNum
     * @param $destinationColNum
     * @param $playerId
     * @param $diceUsedIds
     * @throws feException
     */
    function moveCheckerGame($startColNum, $destinationColNum, $playerId, $diceUsedIds)
    {
        /*if ($playerId != self::getActivePlayerId()) {
            throw new feException( "Move not authorized" );
        }*/
        self::checkAction("selectColumn");

        // list of possible moves
        $diceValueList = self::diceValueList();

        $objBoard = new oneBoard($this);
        $objBoard->read();
        $moveList = $objBoard->getEveryMoveForColumns($playerId, $diceValueList[1], $diceValueList[2], $diceValueList[3], $diceValueList[4]);

        // self::message("playerid=".$playerId." dice1=". $diceValueList[1]." dice2=". $diceValueList[2]." dice3=". $diceValueList[3]." dice4". $diceValueList[4]." startcol=".$startColNum." destcol=".$destinationColNum." diceUsedIds=".$diceUsedIds);
        // playerid=2251644 dice1=1 dice2=5 dice3=0 dice40 startcol=26 destcol=19 diceUsedIds=12

        // check if list submit by player is correct

        // if we have dice1 or dice2 that eats a checker, player cannot play dice12 not dice 21 - check this case @todo

        if (isset($moveList[$startColNum]['dice'.$diceUsedIds])
            && $moveList[$startColNum]['dice'.$diceUsedIds]['endCol'] == $destinationColNum
            && $moveList[$startColNum]['dice'.$diceUsedIds]['success']) {

            // process to changes in the DB
            $startColNewTokenNb = $moveList[$startColNum]['dice'.$diceUsedIds]['startColNumAfterMove'];
            $startColNewPlayerId = $moveList[$startColNum]['dice'.$diceUsedIds]['startColOwnerIdAfterMove'];
            $endColNewTokenNb = $moveList[$startColNum]['dice'.$diceUsedIds]['endColNumAfterMove'];
            $endColNewPlayerId = $moveList[$startColNum]['dice'.$diceUsedIds]['endColOwnerIdAfterMove'];
            $eatChecker = $moveList[$startColNum]['dice'.$diceUsedIds]['eatChecker'];

            self::updateBoardDb($startColNum, $startColNewTokenNb, $startColNewPlayerId);
            self::updateBoardDb($destinationColNum, $endColNewTokenNb, $endColNewPlayerId);

            // put dices used as unusable
            self::setDiceUnusable($diceUsedIds);

            // if player eats a checker
            $otherPlayerId = self::getOtherPlayerId($playerId);
            $otherPlayerPitId = self::getPitColId($otherPlayerId);
            $nbTokenInOtherPlayerPit = self::getNbTokenInPit($otherPlayerId);
            if ($moveList[$startColNum]['dice'.$diceUsedIds]['eatChecker']) {
                $nbTokenInOtherPlayerPit++;
                // save data in DB
                self::updateBoardDb($otherPlayerPitId, $nbTokenInOtherPlayerPit, $otherPlayerId);
                /*
                 * help for translators
                 * It is the message that congratulate the player who eats a checker
                 * It is used in a text bellow
                 * $textMessage = clienttranslate( '${player_name}'." move a checker from  $startColText to column $destColTranslated $additionalEaterMessage");
                 * It is empty if the player didnt eat a checker
                 */
                $additionalEaterMessage = clienttranslate( " and eats a checker, yummy !");

                // for stats
                self::incStat( 1, "number_of_checker_i_eat", $playerId);
                self::incStat( 1, "number_of_checker_eaten", $otherPlayerId);
            } else {
                $additionalEaterMessage = "";
            }

            // notify player that change has been made
            if (self::getPlayerColor($playerId) == 'ff0000') {
                $startColTranslated = 25 - $startColNum;
                $destColTranslated = 25 - $destinationColNum;
            } else {
                $startColTranslated = $startColNum;
                $destColTranslated = $destinationColNum;
            }

            if ($destinationColNum >= 27) {
                // move free a checker
                /*
                 * help for translators
                 * just translate the part between "..." keep '${player_name}'. at the beginning of the line
                 * " move a checker from column $startColTranslated and free it."
                 * $startColTranslated is the number of the column or "bar", just use as it is in the text : $startColTranslated
                 * e.g. hanakuso0 move a checker from column 19 and free it.
                 */
                $textMessage = clienttranslate( '${player_name} moves a checker from column ${startColTranslated} and frees it.' );
            } else {
                // move doesnt free a checker
                if ($startColNum == 25 || $startColNum == 26) {
                    // move start in bar
                    /*
                     * help for translators
                     * if the colupmn is the "bar" the name of the column is "bar" instead of a number
                     * The "bar" is the middle column where eaten checker go waiting to be free
                     * e.g. hanakuso0 move a checker from bar to column 5
                     */
                    $textMessage = clienttranslate( '${player_name} moves a checker from the bar to column ${destColTranslated} ${additional}' );
                } else {
                /*
                 * help for translators
                 * Just translate the part between "..."
                 * Keep '${player_name}'. as it is at the beginning of the line
                 * $startColTranslated is the number of the column or "bar", just use as it is in the text : $startColTranslated
                 * e.g. hanakuso0 move a checker column 8 bar to column 5
                 */
                    $textMessage = clienttranslate( '${player_name} moves a checker from column ${startColTranslated} to column ${destColTranslated} ${additional}' );
                }
            }
            $passivePlayerId = self::getOtherPlayerId($playerId);
            self::notifyAllPlayers(
                "checkerMoved",
                $textMessage,
                array(
                    'i18n' => array( 'additional' ),
                    'activePlayerId' => $playerId,
                    'passivePLayerId' => $passivePlayerId,
                    'player_name' => self::getActivePlayerName(),
                    'activePlayerColor' => self::getPlayerColor($playerId),
                    'passivePlayerColor' =>  self::getPlayerColor($passivePlayerId),
                    'activePlayerPitId' => self::getPlayerPitNum($playerId),
                    'passivePlayerPitId' => self::getPlayerPitNum($passivePlayerId),
                    'startColumn' => $startColNum,
                    'startColumnTokenNb' => $startColNewTokenNb,
                    'startColumnPlayerId' => $startColNewPlayerId,
                    'startColTranslated' => $startColTranslated,
                    'destColTranslated' => $destColTranslated,
                    'endColumn' => $destinationColNum,
                    'endColumnTokenNb' => $endColNewTokenNb,
                    'endColumnPlayerId' => $endColNewPlayerId,
                    'diceUsedIds' => $diceUsedIds,
                    // if a checker has been eaten
                    'eatChecker' => $moveList[$startColNum]['dice'.$diceUsedIds]['eatChecker'],
                    'additional' => $additionalEaterMessage,
                    'tokenNbInPitAfterEat' => $nbTokenInOtherPlayerPit,
                    'nbTokenInRepo' => self::getNbRepositoryForPlayerId($playerId),
                )
            );

            // check if game is over
            if (self::playerWon($playerId)) {
                // self::message("victoire");
                $loserId = self::getOtherPlayerId($playerId);
                self::setStat( self::getLengthToWin($loserId), "path_to_win", $loserId);
                self::setStat( self::getCheckerOut($loserId), "checkers_out", $loserId);
                self::setStat( self::getCheckerAtHome($loserId), "checkers_remaining_at_home_board", $loserId);
                self::setStat( self::getCheckerAtOuter($loserId), "checkers_remaining_at_outer_board", $loserId);
                self::setStat( self::getCheckerAtOpponentOuter($loserId), "checkers_remaining_at_opponent_outer_board", $loserId);
                self::setStat( self::getCheckerAtOpponentHome($loserId), "checkers_remaining_at_opponent_home_board", $loserId);

                self::updateScores();

                $this->gamestate->nextState('gameEnd');
                return;
            }

            // update the playerPanel to display the lenght of checkers path the player has to go to win
            $path_length_to_win = self::getLengthToWin($playerId);
            self::notifyAllPlayers("playerPanel",
                "",
                array(
                    "path_length" => $path_length_to_win,
                    "player_id" => $playerId));
            $path_length_to_win = self::getLengthToWin(self::getOtherPlayerId($playerId));
            self::notifyAllPlayers("playerPanel",
                "",
                array(
                    "path_length" => $path_length_to_win,
                    "player_id" => self::getOtherPlayerId($playerId)));


            // if a token has been put to repository, recalculate score
            if ($destinationColNum >= 27) {
                self::updateScores();
            }

            // check the new state
            // here DB has been update with values after the move
            $objBoard = new oneBoard($this);
            $objBoard->read();
            $diceValueList = self::diceValueList(); // if dice not usable, its value is 0
            $moveList = $objBoard->getEveryMoveForColumns($playerId, $diceValueList[1], $diceValueList[2], $diceValueList[3], $diceValueList[4]);
            if ($diceValueList[1] != 0 || $diceValueList[2] != 0 || $diceValueList[3] != 0 || $diceValueList[4] != 0) {
                // still a valid dice, but can we play ?
                if (self::existValidMoveInMoveList($moveList)) {
                    // exist move
                    // self::message("exists next move");
                    $this->gamestate->nextState('selectColumn');
                }
                else {
                    // no more move bro
                    // self::message("no more move bro");
                    $this->gamestate->nextState('noMoreDice');
                }
            } else {
                // no more dice
                // self::message("no more dice");
                $this->gamestate->nextState('noMoreDice');
            }
        } else {
            // some kind of error of hack
            // player click toooo fast
            self::notifyPlayer(
                $playerId,
                "resetHints",
                "",
                array(
                    'message' => clienttranslate("Please wait for move to be done before doing a another move.")
                )
            );
            // self::message("SOME KIND OF HACK MAN or click too fast, anyway");
        }
    }

    /**
     * Reset board table token_nb=prev_token_nb, player_id=prev_player_id
     */
    function resetToPrevBoardJsGame()
    {
        $sql = "UPDATE board SET token_nb = prev_token_nb, player_id = prev_player_id";
        self::DbQuery( $sql );
        
        $sql = "UPDATE dice_result SET dice1_usable = 1 WHERE dice1 != 0";
        self::DbQuery( $sql );
          
        $sql = "UPDATE dice_result SET dice2_usable = 1 WHERE dice2 != 0";
        self::DbQuery( $sql );
          
        $sql = "UPDATE dice_result SET dice3_usable = 1 WHERE dice3 != 0";
        self::DbQuery( $sql );
          
        $sql = "UPDATE dice_result SET dice4_usable = 1 WHERE dice4 != 0";
        self::DbQuery( $sql );
          
        $this->gamestate->nextState('selectColumn');
        self::reloadPlayersBasicInfos();
    }

    /**
     * Confirm to end turn, sync board records and change to next player
     */
    function onConfirmToEndTurnJsGame()
    {
        $sql = "UPDATE board SET prev_token_nb = token_nb, prev_player_id = player_id";
        self::DbQuery( $sql );
        self::reloadPlayersBasicInfos();

        $this->gamestate->nextState('nextPlayer');
    }

    /**
     * client send a cant play at JSsetup (should not happened)
     */
    function cantPlayFromJsGame()
    {
        self::cantPlay();
    }



//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*

    Example for game state "MyGameState":

    function argMyGameState()
    {
        // Get some values from the current game situation in database...

        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }
    */

    /**
     * argPlayerTurn
     *
     * Return all the data needed by the player who begin its turn.
     *
     * @return An array which contains the data.
     *
     * This method is called each time we enter into "playerTurn" game state,
     * and its result is transfered automatically to the client-side
     */

    /**
     * @return array
     */
    function argPlayerTurn()
    {
    }

    /**
     * Return arguments for the select column state
     * @return array
     */
    function argSelectColumn()
    {
        $playerId = self::getActivePlayerId();
        $playerColor = self::getPlayerColor($playerId);
        $objBoard = new oneBoard($this);
        $objBoard->read();

        // give all possibility for player
        $diceInfoList = self::getDiceInfo();
        $diceValueList = self::diceValueList();
        $moveList = $objBoard->getEveryMoveForColumns($playerId, $diceValueList[1], $diceValueList[2], $diceValueList[3], $diceValueList[4]);

        return array(
            'myColor' => $playerColor,
            'pitColId' => self::getPitColId($playerId),
            'mustPlayPit' => $this->playerHasTokenInPit($playerId),
            'dice1Usable' => $diceInfoList['dice1_usable'],
            'dice2Usable' => $diceInfoList['dice2_usable'],
            'dice3Usable' => $diceInfoList['dice3_usable'],
            'dice4Usable' => $diceInfoList['dice4_usable'],
            'moveList' => $moveList
        );
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    /*

    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...

        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }
    */
    /**
     *
     */
    function stPlayerTurn()
    {

        self::incStat( 1, "turns_number");

        self::notifyAllPlayers("selectPlayer", clienttranslate("Next player"), array());

        // Roll dices
        // if it is first turn, fordib double
        if (self::getStat("turns_number") == 1) {
            $forbidDouble = true;
        } else {
            $forbidDouble = false;
        }

        list($dice1, $dice2) = self::rollDice($forbidDouble);

        // check how many dice player can play : 0, 1 or 2
        $activePlayerId = self::getActivePlayerId();

        $objBoard = new oneBoard($this); // empty new board
        $objBoard->read();  // fill board with database datas

        // is move possible with dice 1
        list($movePossible1, $message1) = $objBoard->getPossibleMoveForOneDice($dice1, $activePlayerId);
        if ($movePossible1) {
            // entering state selectColumn
            // self::message("stPlayerTurn tu peux jouer dé 1");
            $this->gamestate->nextState('selectColumn');
        } else {
            list($movePossible2, $message2) = $objBoard->getPossibleMoveForOneDice($dice2, $activePlayerId);
            // is move possible with dice 2
            if ($movePossible2) {
                // entering state selectColumn
                // self::message("stPlayerTurn tu peux pas jouer dé 1 mais dé 2 = ok");
                $this->gamestate->nextState('selectColumn');
            } else {
                // entering state cannot play
                // self::message("stPlayerTurn tu peux pas jouer, dsl");
                $this->gamestate->nextState('noMoreDice');
            }
        }
    }

    /**
     * enter in state nextPlayer, change the active player and initiate his turn
     */
    function stNextPlayer()
    {
        $player_id = $this->activeNextPlayer();
        self::giveExtraTime( $player_id );
        $this->gamestate->nextState( 'playerTurn' );
    }


    /**
     * Warn player that active player cannot play, and go to state nextPlayer
     */
    function cantPlay()
    {
        $activePlayerId = self::getActivePlayerId();
        $otherPlayerId = self::getOtherPlayerId($activePlayerId);

        self::notifyPlayer(
            $activePlayerId,
            "cantPlay",
            clienttranslate( "You cant play." ),
            array(
                'player_name' => self::getActivePlayerName(),
                'playerId' => $activePlayerId,
            )
        );

        // self::notifyPlayer(
        //     $otherPlayerId,
        //     "cantPlay",
        //     clienttranslate( '${player_name} cannot play.' ),
        //     array(
        //         'player_name' => self::getActivePlayerName(),
        //         'playerId' => $activePlayerId,
        //     )
        // );

        // to include spectator
        self::notifyAllPlayer(
            "cantPlay",
            clienttranslate( '${player_name} cannot play.' ),
            array(
                'player_name' => self::getActivePlayerName(),
                'playerId' => $activePlayerId,
            )
        );

        // stats
        self::incStat(1, "number_of_pass", $activePlayerId);

        $this->gamestate->nextState( 'nextPlayer' );
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:

        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
    */

    function zombieTurn( $state, $active_player )
    {
        $statename = $state['name'];

        if ($state['type'] == "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                    break;
            }

            return;
        }

        if ($state['type'] == "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $sql = "
                UPDATE  player
                SET     player_is_multiactive = 0
                WHERE   player_id = $active_player
            ";
            self::DbQuery( $sql );

            $this->gamestate->updateMultiactiveOrNextState( '' );
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }


}





class oneBoard
{

    const REPO = '888';
    const OVER_REPO = '999';

    const START_COL_NOT_YOUR_COL = "Start column is not yours.";
    const START_COL_NO_TOKEN_IN_COL = "There is no token in this column.";
    const START_COL_HAVE_TO_PLAY_PIT_COL = "You have token in pit.";
    const DEST_COL_NOT_ALL_TOKEN_AT_HOME_TO_GO_REPO = "You need all your token home to free a token.";
    const DEST_COL_NOT_ALL_TOKEN_AT_HOME_TO_GO_OVER_REPO = "You need all your token home, and no token beyond, to free this token.";
    const DEST_COL_CANNOT_GO_OVER_REPO_EXISTS_TOKEN_BEYOND = "You have token beyond this one, you cannot free this token.";
    const DEST_COL_TOO_MUCH_OTHER_PLAYER_TOKEN_IN_DEST_COL = "There are too much other player token in dest column.";
    const START_COL_CANNOT_BE_REPO = "Start column cannot be a repository";
    const UNAVAILABLE_COLUMN = "Useless column";
    const UNKNOWN_ERROR = "Unknown error.";
    const ADD_TOKEN_IN_REPO = "Move ok - add token in REPO with exact number";
    const ADD_TOKEN_BEYOND_REPO = "Move ok - add token in REPO beyond the exact number";
    const ADD_TOKEN_IN_OWN_COLUMN = "Move ok - add token in one colmn of yours";
    const ADD_TOKEN_IN_EMPTY_COLUMN = "Move ok - add token in empty column";
    const ADD_TOKEN_EAT_TOKEN = "Move ok - eat a token, yummy";

    const OTHER_MOVE_CAN_PLAY_BOTH_DICE = "You can only play one dice with this move, and another move allow you to play both dices";
    const PLAY_BIGGER_DICE_ONLY = "If you can play only one dice, you must play the bigger one.";

    public $tabColumn;  // tab of oneColumn object
    public $tabDice;
    public $canPlay = 0;
    public $canPlayBothDice = 0;
    public $bgObject;

    public function __construct($bgObject, $inTabColumn = array(), $inTabDice = array())
    {
        $this->bgObject = $bgObject;
        $this->tabColumn = $inTabColumn;
        $this->tabDice = $inTabDice;
    }

    /**
     * Read from database
     */
    public function read()
    {
        $tabBoard = $this->bgObject->getBoard();
        $this->tabColumn[0] = new oneColumn(0, 0, 0);
        foreach ($tabBoard as $colNum => $tabcolInfo) {
            $objCol = new oneColumn($colNum, $tabcolInfo['player_id'], $tabcolInfo['token_nb']);
            $this->tabColumn[] = $objCol;
        }
        // get repo info
        $idRepo = 0;
        $tabPlayer = $this->bgObject->loadPlayersBasicInfos();
        foreach ($tabPlayer as $playerId => $tabPlayerInfo) {
            $playerColor = $tabPlayerInfo["player_color"];
            if ($playerColor == "aa7903") {
                $idRepo = 28;
            } else if ($playerColor == "ff0000") {
                $idRepo = 27;
            }
            $objCol = new oneColumn($idRepo, $playerId, $this->bgObject->getNbRepositoryForPlayerId($playerId));
            $this->tabColumn[$idRepo] = $objCol;
        }

        // get dice info
        $this->tabDice = $this->bgObject->getDiceInfo();
    }

    /**
     * return HTML code for displaying the object
     * @return mixed|string
     */
    public function display()
    {
        $htmlres = '';
        $htmlres .= '<table class="petite">';
        $htmlres .= '<tr style="background-color: #e3e3e3"><td>12</td><td>11</td><td>10</td><td>9</td><td>8</td><td>7</td><td style="background-color: #e3e3e3">25</td><td>6</td><td>5</td><td>4</td><td>3</td><td>2</td><td>1</td><td class="reporouge">27 = repo-rouge</td>';
        $htmlres .= '<tr><td>col12A</td><td>col11A</td><td>col10A</td><td>col9A</td><td>col8A</td><td>col7A</td><td style="background-color: #e3e3e3">col25A</td><td>col6A</td><td>col5A</td><td>col4A</td><td>col3A</td><td>col2A</td><td>col1A</td><td>col27A</td>';
        $htmlres .= '<tr><td>col13A</td><td>col14A</td><td>col15A</td><td>col16A</td><td>col17A</td><td>col18A</td><td style="background-color: #e3e3e3  ">col26A</td><td>col19A</td><td>col20A</td><td>col21A</td><td>col22A</td><td>col23A</td><td>col24A</td><td>col28A</td>';
        $tabcol = $this->tabColumn;
        foreach ($tabcol as $i => $tabinfo) {
            $colcolor = 'classnone';
            if ($tabinfo->playerId == 2251644) {
                $colcolor = 'classred';
            } else if ($tabinfo->playerId == 2251643) {
                $colcolor = 'classyellow';
            }
            $htmlres = str_replace('col'.$tabinfo->column.'A', '<span class="'.$colcolor.'">'.$tabinfo->number.'</span>', $htmlres);
        }
        $htmlres .= '<tr style="background-color: #e3e3e3"><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td><td>18</td><td style="background-color: #e3e3e3">26</td><td>19</td><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td class="repojaune">28 = repo-jaune</td>';
        $htmlres .= '</table>';
        return $htmlres;
    }



    /**
     * return a possible move object which is an array of oneBoard
     * starting playing dice $inDiceValue1 then try to play $inDiceValue2
     * @param $inDiceValue1
     * @param $inDiceValue2
     * @param $inPlayerId
     */
    public function displayPossibleMoveForDice($inDiceValue1, $inDiceValue2, $inPlayerId)
    {
        // foreach comlumn owned by the playerId
        foreach ($this->tabColumn as $i => $objOneColumn) {
            if ($objOneColumn->playerId == $inPlayerId && $objOneColumn->column <= 26 && $objOneColumn->number > 0) {
                echo "<h2>Départ de la colonne ".$objOneColumn->column." avec le dé $inDiceValue1</h2>";
                echo $this->display();
                $newBoard = new oneBoard($this->bgObject);
                $this->copyTo($newBoard);
                // if move is possible with dice1 from this col
                $tabInfo = $newBoard->getBoardWithMoveColDice($objOneColumn->column, $inDiceValue1, $inPlayerId);
                $moveOk = $tabInfo['success'];
                $message = $tabInfo['message'];
                echo $message;
                if ($moveOk) {
                    echo $newBoard->display();
                    $this->canPlay = 1;
                    // est-ce que on peut jouer avec le dé $inDiceValue2 et la board $newBoard
                    echo '<div style="margin-left:600px;">';
                    foreach ($newBoard->tabColumn as $j => $objOneColumn2) {
                        if ($objOneColumn2->playerId == $inPlayerId && $objOneColumn2->column <= 26 && $objOneColumn2->number > 0) {
                            echo "<h2>Départ de la colonne ".$objOneColumn2->column." avec le dé $inDiceValue2</h2>";
                            echo $newBoard->display();
                            $newBoard2 = new oneBoard($this->bgObject);
                            $newBoard->copyTo($newBoard2);
                            $tabInfo2 = $newBoard2->getBoardWithMoveColDice($objOneColumn2->column, $inDiceValue2, $inPlayerId);
                            $moveOk2 = $tabInfo2['success'];
                            $message2 = $tabInfo2['message'];
                            echo $message2;
                            if ($moveOk2) {
                                echo $newBoard2->display();
                                $this->canPlayBothDice = 1;
                                echo "<div style='background-color:#80abd6'>Can play both dice = 1</div>";
                            }
                        }
                    }
                    echo '</div>';
                }
            }
        }
    }


    /**
     * check play with 2 dices, dice are usable and not null
     * return a moveOneBoard object that give the info from col+dice move, if we can do a second move
     * modify $this update canPlay and canPlayBothDice properties of object
     * @param $inDiceValue1
     * @param $inDiceValue2
     * @param $inPlayerId
     * @param $objMoveOneBoard
     * @return moveOneBoard
     */
    public function getPossibleMoveForTwoDice($inDiceValue1, $inDiceValue2, $inPlayerId, $objMoveOneBoard)
    {

//        $bothDiceMoveList = array();
//        $onlyOneDiceMoveList = array();
        foreach ($this->tabColumn as $i => $objOneColumn) {
            // foreach comlumn owned by the playerId
            if ($inDiceValue1 > 0 && $objOneColumn->playerId == $inPlayerId && $objOneColumn->column <= 26 && $objOneColumn->number > 0) {
                // if dice1 not null, my column not repo with token in it
                $newBoard = new oneBoard($this);
                $this->copyTo($newBoard);
                // if move is possible with dice1 from this col
                $tabInfo = $newBoard->getBoardWithMoveColDice($objOneColumn->column, $inDiceValue1, $inPlayerId);
                $moveOk = $tabInfo['success'];
                $destCol1 = $tabInfo['endCol'];
                if ($moveOk) {
                    $this->canPlay = 1;
                    // est-ce que on peut jouer avec le dé $inDiceValue2 et la board $newBoard
                    foreach ($newBoard->tabColumn as $j => $objOneColumn2) {
                        if ($inDiceValue2 > 0 && $objOneColumn2->playerId == $inPlayerId && $objOneColumn2->column <= 26 && $objOneColumn2->number > 0) {
                            $newBoard2 = new oneBoard($this);
                            $newBoard->copyTo($newBoard2);
                            $tabInfo2 =$newBoard2->getBoardWithMoveColDice($objOneColumn2->column, $inDiceValue2, $inPlayerId);
                            $moveOk2 = $tabInfo2['success'];
                            $destCol2 = $tabInfo2['endCol'];
                            if ($moveOk2) {
                                $this->canPlayBothDice = 1;
//                                $bothDiceMoveList[] = '['.$objOneColumn->column.'-'.$inDiceValue1.'=>'.$destCol1.'] / ['.$objOneColumn2->column.'-'.$inDiceValue2.'=>'.$destCol2.']' ;
                                $objMoveOneBoard->setPossibleDice($objOneColumn->column, $inDiceValue1, true);
                            } else {
//                                $onlyOneDiceMoveList[] = '['.$objOneColumn->column.'-'.$inDiceValue1.'=>'.$destCol1.'] / ['.$objOneColumn2->column.'-'.$inDiceValue2.'=>'.$destCol2.']' ;
                                $objMoveOneBoard->setPossibleDice($objOneColumn->column, $inDiceValue1, false);
                            }
                        }
                    }
                }
            }
        }

//        return array($onlyOneDiceMoveList, $bothDiceMoveList, $objMoveOneBoard);
        return $objMoveOneBoard;
    }



    /**
     * return an array ($movePossible=boolean, $tabres=moveList) for one dice for playerId for all the board
     * @param $inDiceValue1
     * @param $inPlayerId
     * @return array
     */
    public function getPossibleMoveForOneDice($inDiceValue1, $inPlayerId)
    {
        $movePossible = false;
        $tabres = array();
        foreach ($this->tabColumn as $i => $objOneColumn) {
            // foreach comlumn owned by the playerId
            if ($objOneColumn->column == 0) {
                $tabres[$objOneColumn->column]['message'] = self::UNAVAILABLE_COLUMN;
                $tabres[$objOneColumn->column]['success'] = 0;
            } else if ($objOneColumn->column > 26) {
                $tabres[$objOneColumn->column]['message'] = self::START_COL_CANNOT_BE_REPO;
                $tabres[$objOneColumn->column]['success'] = 0;
            } else if($objOneColumn->number == 0) {
                $tabres[$objOneColumn->column]['message'] = self::START_COL_NO_TOKEN_IN_COL;
                $tabres[$objOneColumn->column]['success'] = 0;
            } else if ($objOneColumn->playerId != $inPlayerId) {
                $tabres[$objOneColumn->column]['message'] = self::START_COL_NOT_YOUR_COL;
                $tabres[$objOneColumn->column]['success'] = 0;
            } else {
                $newBoard = new oneBoard($this);
                $this->copyTo($newBoard);
                // if move is possible with dice1 from this col
                $tabMoveResult = $newBoard->getBoardWithMoveColDice($objOneColumn->column, $inDiceValue1, $inPlayerId);
//                echo "<pre>hubc Dice value=$inDiceValue1 playerid=$inPlayerId ".print_r($tabMoveResult, 1)."</pre>";
                $moveOk = $tabMoveResult['success'];
                $message = $tabMoveResult;
                $tabres[$objOneColumn->column] = $message;
                if ($moveOk) {
                    $movePossible = true;
                }
            }
        }
        return array($movePossible, $tabres);
    }

    /**
     * return a oneBoard object with move from $inColId with dice $inDiceValue for player $inPlayerId
     * @param $inColId
     * @param $inDiceValue
     * @param $inPlayerId
     * @return array
     */
    public function getBoardWithMoveColDice($inColId, $inDiceValue, $inPlayerId)
    {
        if ($this->hasTokenInPit($inPlayerId) && $this->bgObject->getPitColId($inPlayerId) != $inColId) {
            // if player has token in pit, must be is pit column
            // echo "Le joueur $inPlayerId a des pions dans le pit a délivrer.";
            return array(
                'success' => false,
                'message' =>  self::START_COL_HAVE_TO_PLAY_PIT_COL,
                'action' => 'FROM-'.$this->tabColumn[$inColId]->column,
                'startCol' => $this->tabColumn[$inColId]->column,
                'endCol' => '',
                'startColNumAfterMove' => '',
                'startColOwnerIdAfterMove' => '',
                'endColNumAfterMove' => '',
                'endColOwnerIdAfterMove' => '',
                'diceValue' => $inDiceValue,
                'eatChecker' => '',
                'warningHint' => '',
                'moveAfterCheckerBeenEaten' => ''
            );
        }

        if ($this->tabColumn[$inColId]->playerId != $inPlayerId) {
            // check if start column is owned by the player
            // echo "La colonne n'appartient pas au joueur $inPlayerId";
            return array(
                'success' => false,
                'message' => self::START_COL_NOT_YOUR_COL,
                'action' => 'FROM-'.$this->tabColumn[$inColId]->column,
                'startCol' => $this->tabColumn[$inColId]->column,
                'endCol' => '',
                'startColNumAfterMove' => '',
                'startColOwnerIdAfterMove' => '',
                'endColNumAfterMove' => '',
                'endColOwnerIdAfterMove' => '',
                'diceValue' => $inDiceValue,
                'eatChecker' => '',
                'warningHint' => '',
                'moveAfterCheckerBeenEaten' => ''
            );
        }
        if ($this->tabColumn[$inColId]->number == 0) {
            // player must have at least one token in the column
            // echo "Le joueur $inPlayerId n'a pas de pions dans cette colonne";
            return array(
                'success' => false,
                'message' =>  self::START_COL_NO_TOKEN_IN_COL,
                'action' => 'FROM-'.$this->tabColumn[$inColId]->column,
                'startCol' => $this->tabColumn[$inColId]->column,
                'endCol' => '',
                'startColNumAfterMove' => '',
                'startColOwnerIdAfterMove' => '',
                'endColNumAfterMove' => '',
                'endColOwnerIdAfterMove' => '',
                'diceValue' => $inDiceValue,
                'eatChecker' => '',
                'warningHint' => '',
                'moveAfterCheckerBeenEaten' => ''
            );
        }

        $destCol = $this->getDestinationCol($inColId, $inDiceValue, $inPlayerId);

        // check if move valid
        if ($destCol == self::REPO) {
            // check if on peut aller au REPO
            if (!$this->allTokenHome($inPlayerId)) {
                // echo "On ne peut pas aller au repo, tous les tokens ne sont pas home";
                return array(
                    'success' => false,
                    'message' =>  self::DEST_COL_NOT_ALL_TOKEN_AT_HOME_TO_GO_REPO,
                    'action' => 'FROM-'.$this->tabColumn[$inColId]->column,
                    'startCol' => $this->tabColumn[$inColId]->column,
                    'endCol' => '',
                    'startColNumAfterMove' => '',
                    'startColOwnerIdAfterMove' => '',
                    'endColNumAfterMove' => '',
                    'endColOwnerIdAfterMove' => '',
                    'diceValue' => $inDiceValue,
                    'eatChecker' => '',
                    'warningHint' => '',
                    'moveAfterCheckerBeenEaten' => ''
                );
            }
        } else if ($destCol == self::OVER_REPO) {
            // check if on va au dela du repo
            if (!$this->allTokenHome($inPlayerId)) {
                // echo "Tous les tokens ne sont pas home, on ne peut pas aller *au delà* du repo";
                return array(
                    'success' => false,
                    'message' =>  self::DEST_COL_NOT_ALL_TOKEN_AT_HOME_TO_GO_OVER_REPO,
                    'action' => 'FROM-'.$this->tabColumn[$inColId]->column,
                    'startCol' => $this->tabColumn[$inColId]->column,
                    'endCol' => '',
                    'startColNumAfterMove' => '',
                    'startColOwnerIdAfterMove' => '',
                    'endColNumAfterMove' => '',
                    'endColOwnerIdAfterMove' => '',
                    'diceValue' => $inDiceValue,
                    'eatChecker' => '',
                    'warningHint' => '',
                    'moveAfterCheckerBeenEaten' => ''
                );
            }

            if (!($this->allTokenHome($inPlayerId) && !$this->existsTokenBeyond($inPlayerId, $inColId))) {
                // echo "Tous les tokens sont home, on ne peut pas aller au dela du repo, car il existe des token plus loin que toi";
                return array(
                    'success' => false,
                    'message' =>  self::DEST_COL_CANNOT_GO_OVER_REPO_EXISTS_TOKEN_BEYOND,
                    'action' => 'FROM-'.$this->tabColumn[$inColId]->column,
                    'startCol' => $this->tabColumn[$inColId]->column,
                    'endCol' => '',
                    'startColNumAfterMove' => '',
                    'startColOwnerIdAfterMove' => '',
                    'endColNumAfterMove' => '',
                    'endColOwnerIdAfterMove' => '',
                    'diceValue' => $inDiceValue,
                    'eatChecker' => '',
                    'warningHint' => '',
                    'moveAfterCheckerBeenEaten' => ''
                );
            }
        } else {
            // check dest colomn state
            if ($this->tabColumn[$destCol]->playerId != $inPlayerId && $this->tabColumn[$destCol]->number > 1) {
                return array(
                    'success' => false,
                    'message' =>  self::DEST_COL_TOO_MUCH_OTHER_PLAYER_TOKEN_IN_DEST_COL,
                    'action' => 'FROM-'.$this->tabColumn[$inColId]->column,
                    'startCol' => $this->tabColumn[$inColId]->column,
                    'endCol' => '',
                    'startColNumAfterMove' => '',
                    'startColOwnerIdAfterMove' => '',
                    'endColNumAfterMove' => '',
                    'endColOwnerIdAfterMove' => '',
                    'diceValue' => $inDiceValue,
                    'eatChecker' => '',
                    'warningHint' => '',
                    'moveAfterCheckerBeenEaten' => ''
                );
            }
        }

        $otherPlayerId = $this->bgObject->getOtherPlayerId($inPlayerId);
        $pitNum = $this->bgObject->getPlayerPitNum($otherPlayerId);
        $repoNum = $this->bgObject->getPlayerRepoNum($inPlayerId);
        $eatChecker = '';

        // move is valide, cool
        if ($destCol == self::REPO) {
            $this->tabColumn[$inColId]->number--;
            $this->tabColumn[$repoNum]->number++;
            $moveMessage = self::ADD_TOKEN_IN_REPO;
            $destColMsg = $this->tabColumn[$repoNum]->column;
            $startColNumAfterMove = $this->tabColumn[$inColId]->number;
            $endColNumAfterMove = $this->tabColumn[$repoNum]->number;
            $endColOwnerIdAfterMove = $inPlayerId;
            $eventMessage = "ADDREPO-".$this->tabColumn[$inColId]->column."-".$this->tabColumn[$repoNum]->column;
        } else if ($destCol == self::OVER_REPO) {
            $this->tabColumn[$inColId]->number--;
            $this->tabColumn[$repoNum]->number++;
            $moveMessage = self::ADD_TOKEN_BEYOND_REPO;
            $destColMsg = $this->tabColumn[$repoNum]->column;
            $startColNumAfterMove = $this->tabColumn[$inColId]->number;
            $endColNumAfterMove = $this->tabColumn[$repoNum]->number;
            $endColOwnerIdAfterMove = $inPlayerId;
            $eventMessage = "ADDREPO-".$this->tabColumn[$inColId]->column."-".$this->tabColumn[$repoNum]->column;
        } else if ($this->tabColumn[$destCol]->playerId == $inPlayerId) {
            $this->tabColumn[$inColId]->number--;
            $this->tabColumn[$destCol]->number++;
            $moveMessage = self::ADD_TOKEN_IN_OWN_COLUMN;
            $destColMsg = $this->tabColumn[$destCol]->column;
            $startColNumAfterMove = $this->tabColumn[$inColId]->number;
            $endColNumAfterMove = $this->tabColumn[$destCol]->number;
            $endColOwnerIdAfterMove = $inPlayerId;
            $eventMessage = "ADDOWN-".$this->tabColumn[$inColId]->column."-".$this->tabColumn[$destCol]->column;
        } else if ($this->tabColumn[$destCol]->number == 0) {
            $this->tabColumn[$inColId]->number--;
            $this->tabColumn[$destCol]->number++;
            $this->tabColumn[$destCol]->playerId = $inPlayerId;
            $moveMessage = self::ADD_TOKEN_IN_EMPTY_COLUMN;
            $destColMsg = $this->tabColumn[$destCol]->column;
            $startColNumAfterMove = $this->tabColumn[$inColId]->number;
            $endColNumAfterMove = $this->tabColumn[$destCol]->number;
            $endColOwnerIdAfterMove = $inPlayerId;
            $eventMessage = "ADDEMPTY-".$this->tabColumn[$inColId]->column."-".$this->tabColumn[$destCol]->column;
        } else if ($this->tabColumn[$destCol]->playerId != $inPlayerId && $this->tabColumn[$destCol]->number == 1) {
            $this->tabColumn[$inColId]->number--;
            $this->tabColumn[$destCol]->number = 1;
            $this->tabColumn[$destCol]->playerId = $inPlayerId;
            $this->tabColumn[$pitNum]->number++;
            $destColMsg = $this->tabColumn[$destCol]->column;
            $eventMessage = "EAT-".$this->tabColumn[$inColId]->column."-".$this->tabColumn[$destCol]->column;
            $startColNumAfterMove = $this->tabColumn[$inColId]->number;
            $endColNumAfterMove = 1;
            $endColOwnerIdAfterMove = $inPlayerId;
            $moveMessage = self::ADD_TOKEN_EAT_TOKEN;
            $eatChecker = 1;
        } else {
            // echo "ERREUR DE CAS 58963253 kzzzt";
            return array(
                'success' => false,
                'message' => self::UNKNOWN_ERROR,
                'action' => '',
                'startCol' => $this->tabColumn[$inColId]->column,
                'endCol' => '',
                'startColNumAfterMove' => '',
                'startColOwnerIdAfterMove' => '',
                'endColNumAfterMove' => '',
                'endColOwnerIdAfterMove' => '',
                'diceValue' => $inDiceValue,
                'eatChecker' => '',
                'warningHint' => '',
                'moveAfterCheckerBeenEaten' => ''
            );
        }

        $startColOwnerIdAfterMove = $inPlayerId;
        if($this->tabColumn[$inColId]->number == 0 && $this->tabColumn[$inColId]->column < 25) {
            $this->tabColumn[$inColId]->playerId = 0;
            $eventMessage .= ";EMPTY_START_COL";
            $startColOwnerIdAfterMove = '';
        }

        return array(
            'success' => true,
            'message' => $moveMessage,
            'action' => $eventMessage,
            'startCol' => $this->tabColumn[$inColId]->column,
            'endCol' => $destColMsg,
            'startColNumAfterMove' => $startColNumAfterMove,
            'startColOwnerIdAfterMove' => $startColOwnerIdAfterMove,
            'endColNumAfterMove' => $endColNumAfterMove,
            'endColOwnerIdAfterMove' => $endColOwnerIdAfterMove,
            'diceValue' => $inDiceValue,
            'eatChecker' => $eatChecker,
            'warningHint' => '',
            // if we display hint for this move. Do not display hint for move after a checker has been eaten
            'moveAfterCheckerBeenEaten' => ''
        );
    }


    /**
     * return the number of the column reach from colId with the value of the dice for playerId
     * if out of the boeard, return REPO or OVER_REPO
     * @param $inColId
     * @param $inDiceValue
     * @param $inPlayerId
     * @return int
     */
    public function getDestinationCol($inColId, $inDiceValue, $inPlayerId)
    {
        $res = 0;
        $playerColor = $this->bgObject->getPlayerColor($inPlayerId);
        // yellow player
        if ($playerColor == "aa7903") {
            // col is pit
            if ($inColId == 25) {
                return $inDiceValue;
            }
            // standart col
            $res = $inColId + $inDiceValue;
            if($res <= 24) {
                return $res;
            } else if ($res == 25) {
                return self::REPO;
            } else {
                return self::OVER_REPO;
            }
        } else if ($playerColor == "ff0000") {
            //red player
            // col is pit
            if ($inColId == 26) {
                return 25 - $inDiceValue;
            }
            // standart col
            $res = $inColId - $inDiceValue;
            if($res > 0) {
                return $res;
            } else if ($res == 0) {
                return self::REPO;
            } else {
                return self::OVER_REPO;
            }
        }
        return $res;
    }


    /**
     * return true if playerId has token in pit
     * @param $inPlayerId
     * @return bool
     */
    public function hasTokenInPit($inPlayerId)
    {
        if ($this->tabColumn[25]->playerId == $inPlayerId) {
            return ($this->tabColumn[25]->number > 0);
        } elseif ($this->tabColumn[26]->playerId == $inPlayerId) {
            return ($this->tabColumn[26]->number > 0);
        } else {
            return false;
        }
    }

    /**
     * copy the current oneBoard object to $inDest oneBoard object
     * @param $inDest
     */
    public function copyTo($inDest)
    {
        foreach ($this->tabColumn as $i => $tabColumn) {
            $objCol = new oneColumn($tabColumn->column, $tabColumn->playerId, $tabColumn->number);
            $inDest->tabColumn[$tabColumn->column] = $objCol;
        }
        $inDest->bgObject = $this->bgObject;
        $inDest->canPlay = $this->canPlay;
        $inDest->canPlayBothDice = $this->canPlayBothDice;
        $inDest->tabDice = $this->tabDice;
    }


    /**
     * return true if every playerId token are in his home
     * @param $inPlayerId
     * @return bool
     */
    public function allTokenHome($inPlayerId)
    {
        $playerColor = $this->bgObject->getPlayerColor($inPlayerId);
        foreach($this->tabColumn as $i => $oneColumn) {
            if ($oneColumn->playerId == $inPlayerId && $oneColumn->number > 0) {
                // yellow player all tokens must be in col 19 20 21 22 23 24 28
                if ($playerColor == 'aa7903' && !in_array($oneColumn->column, array(19, 20, 21, 22, 23, 24, 28))) {
                    return false;
                } else if ($playerColor == 'ff0000' && !in_array($oneColumn->column, array(1, 2, 3, 4, 5, 6, 27))) {
                    // red player all tokens must be in col 1 2 3 4 5 6 27
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * return true if exist player $inPlayerId token beyond $inColNumber
     * dont check repo nor pit
     * @param $inPlayerId
     * @param $inColNumber
     * @return bool
     */
    public function existsTokenBeyond($inPlayerId, $inColNumber)
    {
        $playerColor = $this->bgObject->getPlayerColor($inPlayerId);
        foreach($this->tabColumn as $i => $oneColumn) {
            if ($oneColumn->playerId == $inPlayerId && $oneColumn->number > 0) {
                // yellow player, return true if exists token from col 1 to col $inColNumber - 1
                if ($playerColor == 'aa7903' && $oneColumn->column < $inColNumber) {
                    return true;
                } else if ($playerColor == 'ff0000' && $oneColumn->column > $inColNumber && $oneColumn->column <= 24) {
                    // red player, return true if exists token from $inColNumber + 1 to 24
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Return array of possible move per dice
     * move with dice 1
     * move with dice 2
     * move with dice 12
     * move with dice 21
     * move with dice 123
     * move with dice 1234
     *
     * @param $playerId
     * @param $colId
     * @param $dice1
     * @param $dice2
     * @param $dice3
     * @param $dice4
     * @return array
     */
    function getEveryMoveFromOneColumn($playerId, $colId, $dice1, $dice2, $dice3, $dice4)
    {
        $moveListPerDice = array();

        // dice 1
        if ($dice1 != 0) {
            $board1 = new oneBoard($this);
            $this->copyTo($board1);
            $moveList1 = $board1->getBoardWithMoveColDice($colId, $dice1, $playerId);
            $moveListPerDice['dice1'] = $moveList1;
            // dice 1 then dice 2
            if ($moveList1['success'] && $moveList1['endCol'] <= 24 && $dice2 != 0) {
                $board12 = new oneBoard($this);
                $board1->copyTo($board12);
                $moveList12 = $board12->getBoardWithMoveColDice($moveList1['endCol'], $dice2, $playerId);

                // merge both move, start with dice 1 and end with dice 1 + dice 2
                $moveList12Merged = self::mergeMoveList($moveList1, $moveList12);

                if ($moveList1['eatChecker']) {
                    $moveList12Merged['moveAfterCheckerBeenEaten'] = true;
                }

                $moveListPerDice['dice12'] = $moveList12Merged;            }
            else {
                $moveList12['success'] = '';
            }
        } else {
            $moveList1['success'] = '';
            $moveList12['success'] = '';
        }

        // dice 2
        if ($dice2 != 0) {
            $board2 = new oneBoard($this);
            $this->copyTo($board2);
            $moveList2 = $board2->getBoardWithMoveColDice($colId, $dice2, $playerId);
            $moveListPerDice['dice2'] = $moveList2;
            // dice 2 then dice 1
            if ($moveList2['success'] && $moveList2['endCol'] <= 24 && $dice1 != 0) {
                $board21 = new oneBoard($this);
                $board2->copyTo($board21);
                $moveList21 = $board21->getBoardWithMoveColDice($moveList2['endCol'], $dice1, $playerId);

                // merge both move, start with dice 1 and end with dice 1 + dice 2
                $moveList21Merged = self::mergeMoveList($moveList2, $moveList21);

                if ($moveList2['eatChecker']) {
                    $moveList21Merged['moveAfterCheckerBeenEaten'] = true;
                }

                $moveListPerDice['dice21'] = $moveList21Merged;            }
            else {
                $moveList21['success'] = '';
            }
        } else {
            $moveList2['success'] = '';
            $moveList21['success'] = '';
        }

        // dice 1 + 2 + 3
        // value of dice are the same
        if ($moveList12['success'] && $moveList12['endCol'] <= 24 && $dice3 != 0) {
            $board123 = new oneBoard($this);
            $board12->copyTo($board123);
            $moveList123 = $board123->getBoardWithMoveColDice($moveList12['endCol'], $dice3, $playerId);

            // merge both move, start with dice 1 and end with dice 1 + dice 2
            $moveList123Merged = self::mergeMoveList($moveList12Merged, $moveList123);

            if ($moveList12['eatChecker']) {
                $moveList123Merged['moveAfterCheckerBeenEaten'] = true;
            }

            $moveListPerDice['dice123'] = $moveList123Merged;
        } else {
            $moveList123['success'] = '';
        }

        // dice 1 + 2 + 3 + 4
        // value of dice are the same
        if ($moveList123['success'] && $moveList123['endCol'] <= 24 && $dice4 != 0) {
            $board1234 = new oneBoard($this);
            $board123->copyTo($board1234);
            $moveList1234 = $board1234->getBoardWithMoveColDice($moveList123['endCol'], $dice4, $playerId);

            // merge both move, start with dice 1 and end with dice 1 + dice 2
            $moveList1234Merged = self::mergeMoveList($moveList123Merged, $moveList1234);

            if ($moveList123['eatChecker']) {
                $moveList1234Merged['moveAfterCheckerBeenEaten'] = true;
            }

            $moveListPerDice['dice1234'] = $moveList1234Merged;        }

        return $moveListPerDice;
    }

    /**
     * Return array of possible move per dice
     * move with dice 1
     * move with dice 2
     * move with dice 12
     * move with dice 21
     * move with dice 123
     * move with dice 1234
     * for all column
     * @param $playerId
     * @param $dice1
     * @param $dice2
     * @param $dice3
     * @param $dice4
     * @return array
     */
    function getEveryMoveForColumns($playerId, $dice1, $dice2, $dice3, $dice4)
    {
        $moveList = array();
        for ($colId=1; $colId <= 26; $colId++) {
            $moveList[$colId] = $this->getEveryMoveFromOneColumn($playerId, $colId, $dice1, $dice2, $dice3, $dice4);
        }

        if (!self::hasTokenInPit($playerId) && $dice1 != 0 && $dice2 != 0 && $dice1 != $dice2) {
            // lets check if in this list we have some move that can play 1 dice and some that can play both dice
            // do not check this if player has token in pit
            $moveList = $this->mustPlayBothDiceCorrection($dice1, $dice2, $moveList);
            $moveList = $this->oneDiceOnlyMustPlayBiggerCorrection($dice1, $dice2, $moveList);
        }

        if ($dice1 == $dice2) {
            // double, use first dice 4 and 3
            $moveList = self::doubleDiceCorrection($moveList, $dice1, $dice2, $dice3, $dice4);
        }
        return $moveList;
    }

    /**
     * Check if we can play both dice from two different col
     * @param $moveList
     * @return bool
     */
//    static function checkPlayBothDice($moveList)
//    {
//        $canPlayDice1 = false;
//        $colPlayDice1 = 0;
//        $canPlayDice2 = false;
//        $colPlayDice2 = 0;
//        for ($i = 1; $i <= 24; $i++) {
//            // check for all column
//            if (isset($moveList[$i]['dice1']) && $moveList[$i]['dice1']['success'] && $colPlayDice2 != $i) {
//                $canPlayDice1 = true;
//                $colPlayDice1 = $i;
//            }
//            if (isset($moveList[$i]['dice2']) && $moveList[$i]['dice2']['success'] && $colPlayDice1 != $i) {
//                $canPlayDice2 = true;
//                $colPlayDice2 = $i;
//            }
//        }
//        return ($canPlayDice1 && $canPlayDice2);
//    }


//    static function canWePlayThisDice($moveList, $dice)
//    {
//        $found = false;
//        $i = 0;
//        while (!$found || $i < count($moveList) ) {
//            if (isset($moveList[$i][$dice]) && $moveList[$i][$dice]['success']) {
//                return true;
//            }
//            $i++;
//        }
//        return false;
//    }

    /**
     * Correct the moveList to remove oneDice move if we can play both dice elsewhere
     * return a movelist
     * @param $moveList
     * @return array
     */
    function mustPlayBothDiceCorrection($dice1, $dice2, $moveList)
    {
        $resultList = array();

        $objMoveOneBoard = new moveOneBoard($dice1, $dice2);
        $objBoard = new oneBoard($this->bgObject);
        $objBoard->read();
        $objBoard1 = new oneBoard($this->bgObject);
        $objBoard1->read();
        $objMoveOneBoard = $objBoard1->getPossibleMoveForTwoDice($dice1, $dice2, $this->bgObject->getActivePlayerId(), $objMoveOneBoard);
        $objBoard2 = new oneBoard($this->bgObject);
        $objBoard2->read();
        $objMoveOneBoard = $objBoard2->getPossibleMoveForTwoDice($dice2, $dice1, $this->bgObject->getActivePlayerId(), $objMoveOneBoard);

        if ($objBoard1->canPlayBothDice || $objBoard2->canPlayBothDice) {
            // we can play both dice
            // forbid move that forbid to play the second dice after it
            for ($i = 1; $i <= 24; $i++) {
                // check it for each dice and each column
                $removeDice1 = false;
                $removeDice2 = false;
                $moveInfo1 = $objMoveOneBoard->possibleDiceForColList[$i][$dice1];
                $moveInfo2 = $objMoveOneBoard->possibleDiceForColList[$i][$dice2];
                /*
                moveOneBoard Object
                (
                    [possibleDiceForColList] => Array
                (
                    [1] => Array                                    column 1
                    (
                        [2] => moveOneColumn Object                 with dice value 2
                            (
                                [startCol] => 1
                                [diceValue] => 2
                                [isNextmovePossible] =>
                            )

                        [3] => moveOneColumn Object                 with dice value 3
                            (
                                [startCol] => 1
                                [diceValue] => 3
                                [isNextmovePossible] =>1
                            )
                    }
                )
                */
                if (isset($moveList[$i]['dice1'])) {
                    $resultList[$i]['dice1'] = $moveList[$i]['dice1'];
                    if ($moveList[$i]['dice1']['success'] && !$moveInfo1->isNextmovePossible) {
                        // remove dice from playable dice
                        $resultList[$i]['dice1']['success'] = false;
                        // OTHER_MOVE_CAN_PLAY_BOTH_DICE
                        $resultList[$i]['dice1']['message'] = clientTranslate("You can only play one dice with this move, and another move allow you to play both dices");
                        $resultList[$i]['dice1']['action'] = "FROM-$i Prevent from playing both dices";
                        $resultList[$i]['dice1']['warningHint'] = 1;
                        $removeDice1 = true;
                    }
                }


                // check for dice 2
                if (isset($moveList[$i]['dice2'])) {
                    $resultList[$i]['dice2'] = $moveList[$i]['dice2'];
                    if ($moveList[$i]['dice2']['success'] && !$moveInfo2->isNextmovePossible) {
                        // remove dice from playable dice
                        $resultList[$i]['dice2']['success'] = false;
                        // OTHER_MOVE_CAN_PLAY_BOTH_DICE
                        $resultList[$i]['dice2']['message'] = clientTranslate("You can only play one dice with this move, and another move allow you to play both dices");
                        $resultList[$i]['dice2']['action'] = "FROM-$i Prevent from playing both dices";
                        $resultList[$i]['dice2']['warningHint'] = 1;
                        $removeDice2 = true;
                    }
                }

                // dice 12
                if ((!$removeDice1 && !$removeDice2) &&  isset($moveList[$i]['dice12'])) {
                    $resultList[$i]['dice12'] = $moveList[$i]['dice12'];
                }

                // dice 21
                if ((!$removeDice1 && !$removeDice2) &&  isset($moveList[$i]['dice21'])) {
                    $resultList[$i]['dice21'] = $moveList[$i]['dice21'];
                }
            }

            return $resultList;
        } else {
            return $moveList;
        }
    }


    /**
     * If you can only play one dice, either the 1 or the 2, but not both
     * you must play the bigger one
     * Not valid if checker is from pit
     * return a movelist
     * @param $moveList
     * @return mixed
     */
    function oneDiceOnlyMustPlayBiggerCorrection($dice1, $dice2, $moveList)
    {
        $resultList = array();

        $objMoveOneBoard = new moveOneBoard($dice1, $dice2);
        $objBoard = new oneBoard($this->bgObject);
        $objBoard->read();
        $objBoard1 = new oneBoard($this->bgObject);
        $objBoard1->read();
        $objMoveOneBoard = $objBoard1->getPossibleMoveForTwoDice($dice1, $dice2, $this->bgObject->getActivePlayerId(), $objMoveOneBoard);
        $objBoard2 = new oneBoard($this->bgObject);
        $objBoard2->read();
        $objMoveOneBoard = $objBoard2->getPossibleMoveForTwoDice($dice2, $dice1, $this->bgObject->getActivePlayerId(), $objMoveOneBoard);

        if ($objBoard1->canPlayBothDice || $objBoard2->canPlayBothDice) {
            return $moveList;
        }

        // here we cannot play both dices
        // seek which dice we can play, 1 or 2
        $dice1Found = false;
        $dice1Value = $dice1;
        $dice2Found = false;
        $dice2Value = $dice2;

        if ($objBoard1->canPlay) {
            $dice1Found = true;
        }
        if ($objBoard2->canPlay) {
            $dice2Found = true;
        }

        if (!$dice1Found || !$dice2Found) {
            // only one of the dice is possible, ok, lets go then
            return $moveList;
        } else {
            // $dice1Found && $dice2Found
            // you have to play the biggest dice
            if ($dice1Value >= $dice2Value) {
                $diceToRemove = 'dice2';
                $diceToRemove2 = 'dice21';
            } else {
                $diceToRemove = 'dice1';
                $diceToRemove2 = 'dice12';
            }

            for ($i = 1; $i <= count($moveList); $i++) {
                // remove moves when we have dice1 ok and dice12 false
                // remove moves when we have dice2 ok and dice21 false
                foreach ($moveList[$i] as $key => $val) {
                    if ($key == $diceToRemove && $moveList[$i][$diceToRemove]['success']) {
                        // modify this entry for $diceToRemove which is no more success
                        $resultList[$i][$diceToRemove] = $moveList[$i][$diceToRemove];
                        $resultList[$i][$diceToRemove]['success'] = false;
                        // PLAY_BIGGER_DICE_ONLY
                        $resultList[$i][$diceToRemove]['message'] = clientTranslate("If you can play only one dice, you must play the bigger one.");
                        $resultList[$i][$diceToRemove]['action'] = "FROM-$i Can only play one of both dices, the bigger only";
                        $resultList[$i][$diceToRemove]['warningHint'] = 1;
                    } else if ($key == $diceToRemove2 && $moveList[$i][$diceToRemove]['success'] && !$moveList[$i][$diceToRemove2]['success']) {
                        // dont keep this value, because dice1 is not success anymore) {
                    } else {
                        // keep the value
                        $resultList[$i][$key] = $moveList[$i][$key];
                    }
                }

            }
        }
        return $resultList;
    }

    /**
     * when double, start to use dice 4, then 3 then 2, then 1
     * return a movelist
     * @param $moveList
     * @return array
     */
    function doubleDiceCorrection($moveList, $dice1, $dice2, $dice3, $dice4)
    {
        $resultList = array();
        foreach ($moveList as $key => $val) {
            if (array_key_exists('dice1234', $val)) {
                $resultList[$key]['dice1234'] = $val['dice1234'];
            }
            if (array_key_exists('dice123', $val)) {
                if ($dice4 != 0) {
                    $resultList[$key]['dice234'] = $val['dice123'];
                } else {
                    $resultList[$key]['dice123'] = $val['dice123'];
                }
            }
            if (array_key_exists('dice12', $val)) {
                // for 12 and 21, which is the same
                if ($dice4 != 0 && $dice3 != 0) {
                    $resultList[$key]['dice34'] = $val['dice12'];
                } elseif ($dice3 != 0) {
                    $resultList[$key]['dice23'] = $val['dice12'];
                } else {
                    $resultList[$key]['dice12'] = $val['dice12'];
                }
            }
            if (array_key_exists('dice1', $val)) {
                // dice1 and dice2 are the same
                if ($dice4 != 0) {
                    $resultList[$key]['dice4'] = $val['dice1'];
                } elseif($dice3 != 0) {
                    $resultList[$key]['dice3'] = $val['dice1'];
                } elseif ($dice2 != 0) {
                    $resultList[$key]['dice2'] = $val['dice1'];
                } else {
                    $resultList[$key]['dice1'] = $val['dice1'];
                }
            }
        }
        return $resultList;
    }

    /**
     * list 1 is from (startCol) to (startCol + dice 1)
     * list 12 is from (startCol + dice 1) to (startCol + dice 1 + dice 2)
     * list result is from (startCol) to (startCol + dice 1 + dice 2)
     * Both move ARE SUCCESS
     * @param $moveList1
     * @param $moveList12
     * @return array
     */
    function mergeMoveList($moveList1, $moveList12)
    {
        $resultList = array();
        $resultList['success'] = $moveList12['success'];
        $resultList['message'] = $moveList12['message'];
        $resultList['action'] = preg_replace('/'.$moveList12['startCol'].'/', $moveList1['startCol'], $moveList12['action']);
        $resultList['action'] = preg_replace('/;EMPTY_START_COL/', '', $resultList['action']);
        if ($moveList1['startColNumAfterMove'] == 0 || $moveList1['startColNumAfterMove'] == '') {
            $resultList['action'] .= ';EMPTY_START_COL';
        }
        $resultList['startCol'] = $moveList1['startCol'];
        $resultList['endCol'] = $moveList12['endCol'];
        $resultList['startColNumAfterMove'] = $moveList1['startColNumAfterMove'];
        $resultList['startColOwnerIdAfterMove'] = $moveList1['startColOwnerIdAfterMove'];
        $resultList['endColNumAfterMove'] = $moveList12['endColNumAfterMove'];
        $resultList['endColOwnerIdAfterMove'] = $moveList12['endColOwnerIdAfterMove'];
        $resultList['diceValue'] = $moveList1['diceValue'] + $moveList12['diceValue'];
        $resultList['eatChecker'] = $moveList12['eatChecker'] || $moveList1['eatChecker'];
        $resultList['warningHint'] = $moveList1['warningHint'];
        $resultList['moveAfterCheckerBeenEaten'] = $moveList12['moveAfterCheckerBeenEaten'];
        return $resultList;
    }

}


class oneColumn
{
    public $column;
    public $playerId;
    public $number;

    public function __construct($inCol, $playerId, $inNum)
    {
        $this->column = $inCol;
        $this->playerId = $playerId;
        $this->number = $inNum;
    }

}

class moveOneBoard
{
    public $possibleDiceForColList;

    function __construct($dice1Value, $dice2Value)
    {
        for ($i = 1; $i <= 26; $i++) {
            $objMove1 = new moveOneColumn($i, $dice1Value);
            $this->possibleDiceForColList[$i][$dice1Value] = $objMove1;
            $objMove2 = new moveOneColumn($i, $dice2Value);
            $this->possibleDiceForColList[$i][$dice2Value] = $objMove2;
        }
    }

    function setPossibleDice($startCol, $diceValue, $isNextmovePossible)
    {
        $objMove = $this->possibleDiceForColList[$startCol][$diceValue];
        if (!$objMove->isNextmovePossible && $isNextmovePossible) {
            $objMove->isNextmovePossible = $isNextmovePossible;
        }
    }

}

class moveOneColumn
{
    public $startCol = 0;
    public $diceValue = 0;
    public $isNextmovePossible = false;

    function __construct($startCol, $diceValue, $isNextmovePossible = false)
    {
        $this->startCol = $startCol;
        $this->diceValue = $diceValue;
        $this->isNextmovePossible = $isNextmovePossible;
    }


}
