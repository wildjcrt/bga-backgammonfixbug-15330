/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * backgammon implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * backgammon.js
 *
 * backgammon user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */
/*

 this.isCurrentPlayerActive()
 this.getActivePlayerId()
 dojo.removeClass(id, class);

 constructor: function(){
 setup: function( gamedatas )
 setup: function( gamedatas )
 onEnteringState: function( stateName, args )
 onUpdateActionButtons: function( stateName, args )
 onSelectColumn: function (evt)
 moveChecker: function(startCol, endCol, diceIds)
 onFreeChecker: function(evt)
 onColumnMouseOver: function(evt)
 onFreeHintMouseOver: function(evt)
 onColumnMouseOut: function(evt)
 setupNotifications: function()
 notif_rollDiceDone: function( notif )
 notif_checkerMoved: function( notif )
 addTokenOnBoard: function( col_id, token_nb, player_id )
 getPlayerColor: function (in_player_id)
 addDiceOnBoard: function (dice_id, dice_value, player_id, dice_usable)
 addHintsOnBoard: function(col_id, player_id)
 addRepositoryOnBoard: function (topOrBottom, redOrYellow, tokenNb, playerId)
 displayHintForColumn: function(in_colnum, in_tab_dice_used)
 removeHintForColumn: function()
 getListOfDiceFromDicecode: function(diceCode)
 addBorderSelectedColumn: function(colId)
 getClickedColumnId: function()
 classDiceFromColId: function(colId)
 classDiceUsedToInt: function(classCol)
 diceIdsToArray: function(txt)
 colHasHint: function(colId)
 lighton: function(diceId)
 displayWarningHintForColumn: function(colId, message)
 displayColumn: function(colNum, tokenNb, playerId, playerColor)
 emptyColumn: function(colNum)
 makeDiceUnusable: function(diceIds)
 addCheckerToRepo: function(playerId, tokenNb)
 */

define([
        "dojo","dojo/_base/declare",
        "ebg/core/gamegui",
        "ebg/counter"
    ],
    function (dojo, declare) {
        return declare("bgagame.backgammon", ebg.core.gamegui, {
            constructor: function(){
                //console.log('backgammon constructor');

                // Here, you can init the global variables of your user interface
                // Example:
                // this.myGlobalValue = 0;
                this.moveList = new Array();
                this.dice1Usable = 0;
                this.dice2Usable = 0;
                this.dice3Usable = 0;
                this.dice4Usable = 0;
                this.mustPlayPit = 0;
                this.myColor = 0;
                this.pitColId = 0;
                this.myId = 0;
                this.nbCheckerInRepo = 0;
            },

            /*
             setup:

             This method must set up the game user interface according to current game situation specified
             in parameters.

             The method is called each time the game interface is displayed to a player, ie:
             _ when the game starts
             _ when a player refreshes the game page (F5)

             "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
             */

            setup: function( gamedatas )
            {
                // Setting up player boards
                var myId = gamedatas.playerorder[0];
                var myColor = this.getPlayerColor(myId);
                var activePlayerId = this.getActivePlayerId();
                this.myColor = myColor;
                this.myId = myId;

                for ( var player_id in gamedatas.players )
                {
                    // Setting up players boards if needed - i.e. player_panel
                    var player_board_div = $('player_board_'+player_id);
                    //alert(player_board_div.id);

                    if (player_id == myId) {
                        postfix = '_my';
                    } else {
                        postfix = '_other';
                    }

                    //console.log(gamedatas.players[player_id]['color'] + postfix);

                    dojo.place(
                        this.format_block(
                            'jstpl_player_board',
                            {
                                player_id : player_id,
                                code_img : gamedatas.players[player_id]['color'] + postfix
                            }
                        )
                        , player_board_div
                    );
                }

                // current player is always the top player
                //console.log('Starting game setup');

                console.log("*** gamedatas ***");
                console.log(gamedatas);

                // display tokens for yellow player
                if (myColor == 'aa7903') {

                    for (i = 1; i <= 26 ; i++) {
                        this.addAnimBoxOnBoard(i, i, myId);
                    }

                    for (var i in gamedatas.board) {
                        colId = gamedatas.board[i].col_id;
                        if (colId <= 26) { // do not display repo
                            tokenNb = gamedatas.board[i].token_nb;
                            playerId = gamedatas.board[i].player_id;
                            if (playerId != 0) {
                                this.addTokenOnBoard(colId, colId, tokenNb, playerId);
                            } else {
                                this.addTokenOnBoard(colId, colId, 0, myId);
                            }
                        }

                    }

                    // write repository
                    // top is red
                    //this.addAnimBoxOnBoard(27, 28, myId);
                    for (i = 1; i <= 15; i++) {
                        this.addAnimBoxOnBoard('27-'+i, '28-'+i, myId);
                    }
                    tokenNb = gamedatas.board[27].token_nb;
                    this.addRepositoryOnBoard('bottom', 'yellow', tokenNb, myId);

                    // bottom is yellow
                    //this.addAnimBoxOnBoard(28, 27, myId);
                    for (i = 1; i <= 15; i++) {
                        this.addAnimBoxOnBoard('28-'+i, '27-'+i, myId);
                    }
                    tokenNb = gamedatas.board[26].token_nb;
                    this.addRepositoryOnBoard('top', 'red', tokenNb, myId);
                }

                // display token for red player
                // <div class="token tokencolor_${color} tokennb_${token_nb}_${vertpos}" id="token_${tokencol_id}"></div>
                if (myColor == 'ff0000') {
                    for (i = 0; i < 24 ; i++) {
                        tokenId = gamedatas.board[i].col_id;
                        tokenNb = gamedatas.board[i].token_nb;
                        playerId = gamedatas.board[i].player_id;
                        colId = 25 - tokenId;
                        //console.log(colId+' '+tokenId+' '+tokenNb+' '+playerId);

                        this.addAnimBoxOnBoard(colId, tokenId, myId);

                        if (playerId != 0) {
                            this.addTokenOnBoard(colId, tokenId, tokenNb, playerId);
                        } else {
                            this.addTokenOnBoard(colId, tokenId, 0, myId);
                        }

                    }

                    // yellow pit = tokenId = 25 - colId = 26
                    this.addAnimBoxOnBoard(26, 25, myId);
                    tokenNb = gamedatas.board[24].token_nb;
                    playerId = gamedatas.board[24].player_id;
                    this.addTokenOnBoard(26, 25, tokenNb, playerId);

                    // red pit = tokenId = 26 - colId = 25
                    this.addAnimBoxOnBoard(25, 26, myId);
                    tokenNb = gamedatas.board[25].token_nb;
                    playerId = gamedatas.board[25].player_id;
                    this.addTokenOnBoard(25, 26, tokenNb, playerId);

                    // write repository
                    // top is yellow
                    //this.addAnimBoxOnBoard(27, 27, myId);
                    for (i = 1; i <= 15; i++) {
                        this.addAnimBoxOnBoard('27-'+i, '27-'+i, myId);
                    }

                    if( gamedatas.board[27] )
                    {
                        tokenNb = gamedatas.board[27].token_nb; // board index start at 0
                        this.addRepositoryOnBoard('top', 'yellow', tokenNb, myId);
                    }

                    // bottom is red
                    //this.addAnimBoxOnBoard(28, 28, myId);
                    for (i = 1; i <= 15; i++) {
                        this.addAnimBoxOnBoard('28-'+i, '28-'+i, myId);
                    }

                    if( gamedatas.board[26] )
                    {
                        tokenNb = gamedatas.board[26].token_nb; // board index start at 0
                        this.addRepositoryOnBoard('bottom', 'red', tokenNb, myId);
                    }
                }

                // display player panel
                for (var keyPlayerId in gamedatas.pathToWin) {
                    dojo.byId("path-to-go_p"+keyPlayerId).innerHTML = gamedatas.pathToWin[keyPlayerId];
                }

                // write dice
                for (var i in gamedatas.dice_result) {
                    var dices = gamedatas.dice_result[i];
                    this.addDiceOnBoard(1, dices.dice1, myId, dices.dice1_usable);
                    this.addDiceOnBoard(2, dices.dice2, myId, dices.dice2_usable);
                    this.addDiceOnBoard(3, dices.dice3, myId, dices.dice3_usable);
                    this.addDiceOnBoard(4, dices.dice4, myId, dices.dice4_usable);
                }

                // write hints
                for (var i=1; i <= 24; i++) {
                    if (myColor == 'aa7903') {
                        this.addHintsOnBoard(i, i, myId);
                    } else {
                        this.addHintsOnBoard(i, 25 - i, myId);
                    }
                }

                // connect clickable area
                this.connectAreas();

                // Setup game notifications to handle(see "setupNotifications" method below)
                this.setupNotifications();

                // check if player can play
                if (this.isCurrentPlayerActive() && !gamedatas.isMovePossible) {
                    this.ajaxcall( "/backgammon/backgammon/cantPlayFromJs.html", {}, this, function( result ) {}, function( is_error) {});
                }

                // clean board from all hints, etc...
                this.removeHintForColumn();

                //console.log('Ending setup');
            },


            connectAreas: function() {
                dojo.query('.token').connect('onclick', this, 'onSelectColumn');
                dojo.query('.token').connect('onmouseover', this, 'onColumnMouseOver');
                dojo.query('.token').connect('onmouseout', this, 'onColumnMouseOut');
                dojo.query('.hint').connect('onclick', this, 'onSelectColumn');
                dojo.query('.hint').connect('onmouseover', this, 'onColumnMouseOver');
                dojo.query('.hint').connect('onmouseout', this, 'onColumnMouseOut');
                dojo.query('.free_hint_area').connect('onclick', this, 'onFreeChecker');
                dojo.query('.free_hint_area').connect('onmouseover', this, 'onFreeHintMouseOver');
                dojo.query('.free_hint_area').connect('onmouseout', this, 'onColumnMouseOut');
            },

            unconnectAreas: function()
            {
                dojo.query('.token').connect('onclick', this, '');
                dojo.query('.token').connect('onmouseover', this, '');
                dojo.query('.token').connect('onmouseout', this, '');
                dojo.query('.hint').connect('onclick', this, '');
                dojo.query('.hint').connect('onmouseover', this, '');
                dojo.query('.hint').connect('onmouseout', this, '');
                dojo.query('.free_hint_area').connect('onclick', this, '');
                dojo.query('.free_hint_area').connect('onmouseover', this, '');
                dojo.query('.free_hint_area').connect('onmouseout', this, '');
            },


            ///////////////////////////////////////////////////
            //// Game & client states

            // onEnteringState: this method is called each time we are entering into a new game state.
            //                  You can use this method to perform some user interface changes at this moment.
            //
            onEnteringState: function( stateName, args )
            {
                //console.log( 'Entering state: '+stateName );

                switch( stateName )
                {

                    case 'selectColumn':

                        this.removeHintForColumn();

                        // get moveList returned by servers, and save it to global variable
                        this.myColor = args.args.myColor;
                        this.pitColId = args.args.pitColId;
                        this.moveList = args.args.moveList;
                        this.mustPlayPit = args.args.mustPlayPit;
                        this.dice1Usable = args.args.dice1Usable;
                        this.dice2Usable = args.args.dice2Usable;
                        this.dice3Usable = args.args.dice3Usable;
                        this.dice4Usable = args.args.dice4Usable;
                        this.nbCheckerInRepo = args.args.nbCheckerInRepo;

                        // if token in pit, autoselect pit, and display the blue border and hints
                        if (this.isCurrentPlayerActive() && this.mustPlayPit) {
                            this.addBorderSelectedColumn(this.pitColId);
                            moveListAllDiceForCol = this.moveList[this.pitColId];
                            this.displayAvaliableHintsForColumn(moveListAllDiceForCol);
                        }

                        break;
                    case 'nextPlayer':
                        //console.log('Entering nextPlayer ');
                        break;
                    case 'dummmy':
                        break;
                }
            },

            /**
             * onLeavingState
             *
             * This method is called each time we are leaving a game state.
             * You can use this method to perform some user interface changes at this moment.
             *
             * @param string stateName : The name of the entering state.
             */
            onLeavingState: function(stateName)
            {
                console.log('Starting onLeavingState(' + stateName + ')...');

                switch(stateName)
                {
                    case 'selectColumn' :
                        this.removeHintForColumn();
                        break;

                    case 'dummmy':
                        break;

                    case 'playerTurn' :
                        break;
                }
            },


            // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
            //                        action status bar (ie: the HTML links in the status bar).
            //
            onUpdateActionButtons: function( stateName, args )
            {
                //console.log( 'onUpdateActionButtons: '+stateName );

                if( this.isCurrentPlayerActive() )
                {
                    switch( stateName )
                    {
                        /*
                         Example:

                         case 'myGameState':

                         // Add 3 action buttons in the action status bar:

                         this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' );
                         this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' );
                         this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' );
                         break;
                         */
                    }
                }
            },

            /**
             *
             * @param evt
             */
            onSelectColumn: function (evt)
            {
                dojo.stopEvent( evt );
                /*if(!this.isCurrentPlayerActive() )  {
                    return;
                }*/
                if( !this.checkAction( "selectColumn" ) ) {
                    return;
                }

                //console.log(this.moveList);

                var colid = evt.currentTarget.id;
                var tabcolnum = evt.currentTarget.id.split('_');
                var colnum = tabcolnum[1];
                var playerId = this.getActivePlayerId();
                var playerColor = this.getPlayerColor(playerId);

                //console.log("MOVE list column " + colnum);
                //console.log(this.moveList[colnum]);

                // if we click on a previously selected column, unselect it
                if (dojo.hasClass('token_'+colnum, 'selected_column')) {
                    this.removeHintForColumn();
                    return;
                }

                // if we click on a column with a hint on it, move checker from start to this column
                // get column clicked (with border class)
                //console.log("On a clické sur un truc qui a un hint ou pas ?");
                if (colnum < 25 && this.colHasHint(colnum)) {
                    // this is destination column, move has been selected
                    this.moveChecker(this.getClickedColumnId(), colnum, this.classDiceUsedToInt(document.getElementById('hint_'+colnum).className));
                    //console.log("MOVE TON CHECKER "+colnum+" classdiceusedtohint");
                    return;
                }

                // remove previous hints
                this.removeHintForColumn();
                this.addBorderSelectedColumn(colnum);

                // place hints on board for this colNum
                moveListAllDiceForCol = this.moveList[colnum];
                this.displayAvaliableHintsForColumn(moveListAllDiceForCol);

            },

            /**
             * Display all hints for this column, for every diceCodes found
             * if dice1 or dice2 eats checker, dont display more hints than dice1 and dice2
             * if dice12 eats dont display dice123
             * if dice123 eats dont display dice1234
             * @param moveListAllDiceForCol
             */
            displayAvaliableHintsForColumn: function(moveListAllDiceForCol) {

                if( typeof moveListAllDiceForCol == 'undefined')
                {
                    return ;
                }

                var eatChecker = false;

                // dice 1 and dice 2
                diceList = ['dice1', 'dice2', 'dice3', 'dice4'];
                for (i = 0; i < diceList.length; i++) {
                    dice = diceList[i];
                    if (moveListAllDiceForCol[dice] != undefined) {
                        doEat = this.displayHintTool01(moveListAllDiceForCol, dice);
                        eatChecker = (eatChecker || doEat);
                    }
                }

                // dice 12 et 21
                if (eatChecker) {
                    return;
                }
                diceList = ['dice12', 'dice21', 'dice23', 'dice32', 'dice34', 'dice43'];
                for (i = 0; i < diceList.length; i++) {
                    dice = diceList[i];
                    if (moveListAllDiceForCol[dice] != undefined) {
                        doEat = this.displayHintTool01(moveListAllDiceForCol, dice);
                        eatChecker = (eatChecker || doEat);
                    }
                }

                // dice 123
                if (eatChecker) {
                    return;
                }
                diceList = ['dice123', 'dice234'];
                for (i = 0; i < diceList.length; i++) {
                    dice = diceList[i];
                    if (moveListAllDiceForCol[dice] != undefined) {
                        doEat = this.displayHintTool01(moveListAllDiceForCol, dice);
                        eatChecker = (eatChecker || doEat);
                    }
                }

                // dice 1234
                if (eatChecker) {
                    return;
                }
                diceList = ['dice1234'];
                for (i = 0; i < diceList.length; i++) {
                    dice = diceList[i];
                    if (moveListAllDiceForCol[dice] != undefined) {
                        doEat = this.displayHintTool01(moveListAllDiceForCol, dice);
                        eatChecker = (eatChecker || doEat);
                    }
                }
            },


            /**
             * Display hint and return if a checker has been eaten
             * @param moveListAllDiceForCol
             * @param diceCode
             * @returns {boolean}
             */
            displayHintTool01: function(moveListAllDiceForCol, diceCode)
            {
                var eatChecker = false;
                moveListForDice = moveListAllDiceForCol[diceCode];
                destCol = moveListForDice['endCol'];
                if (moveListForDice['success']) {
                    this.displayHintForColumn(destCol, this.getListOfDiceFromDicecode(diceCode));
                    if (moveListForDice['eatChecker']) {
                        eatChecker = true;
                    }
                } else if (moveListForDice['warningHint']) {
                    // case that we cannot play a column because we will play only one dice,
                    // and there is another column where we can play 2 dices
                    this.displayWarningHintForColumn(destCol, moveListForDice['message']);
                }
                return eatChecker;
            },

            // we move a checker
            // send info to server
            moveChecker: function(startCol, endCol, diceIds)
            {
                // dices_used: this.diceIdsToArray(diceIds)
                //alert("Move from "+startCol+" to "+endCol+" avec les dés "+diceIds);

                this.removeHintForColumn();

                if (startCol > 0 && endCol > 0 && diceIds > 0) {
                    this.ajaxcall("/backgammon/backgammon/moveChecker.html",
                        {
                            lock: true,
                            start_col_num: startCol,
                            destination_col_num: endCol,
                            player_id: this.getActivePlayerId(),
                            dices_used: diceIds
                        },
                        this,
                        function (result) {
                        },
                        function (is_error) {
                        }
                    );
                }
            },

            // we click to free a checker
            onFreeChecker: function(evt)
            {
                var playerId = this.myId;
                var color = this.getPlayerColor(playerId);

                startCol = this.getClickedColumnId();
                diceIds = this.classDiceUsedToInt(document.getElementById('free_hintarea_bottom').className)
                //alert("Colonne "+startCol+" liberéedélivrée avec le dés "+diceIds);

                if (color == 'ff0000') {
                    destCol = '27';
                } else {
                    destCol = '28';
                }
                this.moveChecker(startCol, destCol, diceIds);
            },

            /**
             * mouse over a column or a hint area
             * @param evt
             */
            onColumnMouseOver: function(evt)
            {
                var colid = evt.currentTarget.id;
                var tabcolnum = evt.currentTarget.id.split('_');
                var colnum = tabcolnum[1];

                if (colnum < 25 && this.colHasHint(colnum)) {
                    // si la colonne a un hint, hightlight dice used for this hint
                    dices = this.classDiceFromColId(colnum);
                    for (var i=0; i < dices.length; i++) {
                        // hightlight dice dice_{dices[i]}
                        this.lighton(dices[i]);
                    }
                }
            },

            onFreeHintMouseOver: function(evt)
            {
                if (document.getElementById('free_hintarea_bottom')) {
                    classLabel = document.getElementById('free_hintarea_bottom').className;
                    dices = this.classDiceUsedToInt(classLabel);
                    for (var i = 0; i < dices.length; i++) {
                        // hightlight dice dice_{dices[i]}
                        this.lighton(dices[i]);
                    }
                }
            },

            onColumnMouseOut: function(evt)
            {
                dojo.removeClass('dice_1', 'lighton_dice');
                dojo.removeClass('dice_2', 'lighton_dice');
                dojo.removeClass('dice_3', 'lighton_dice');
                dojo.removeClass('dice_4', 'lighton_dice');
            },
            ///////////////////////////////////////////////////
            //// Utility methods

            /*

             Here, you can defines some utility methods that you can use everywhere in your javascript
             script.

             */


            ///////////////////////////////////////////////////
            //// Player's action

            ///////////////////////////////////////////////////
            //// Reaction to cometD notifications

            /*
             setupNotifications:

             In this method, you associate each of your game notifications with your local method to handle it.

             Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
             your backgammon.game.php file.

             */
            setupNotifications: function()
            {
                //console.log( 'notifications subscriptions setup' );
                dojo.subscribe('rollDiceDone', this, "notif_rollDiceDone");
                dojo.subscribe('checkerMoved', this, "notif_checkerMoved");
                dojo.subscribe('cantPlay', this, "notif_cantPlay");
                dojo.subscribe('selectPlayer', this, "notif_selectPlayer");
                dojo.subscribe( 'playerPanel', this, 'notif_playerPanel');
                dojo.subscribe( 'newScores', this, 'notif_newScores');
                dojo.subscribe( 'resetHints', this, 'notif_resetHints');

                this.notifqueue.setSynchronous( 'checkerMoved', 1000 ); // to let the anim play
                this.notifqueue.setSynchronous( 'cantPlay', 3000 );     // message displayed before go to next player
                //this.notifqueue.setSynchronous( 'selectPlayer', 1000 ); // because of anim
                //this.notifqueue.setSynchronous( 'resetHints', 1000 ); // because of anim
            },

            notif_resetHints: function( notif )
            {
                var message = notif.args.message;
                this.showMessage( message , "info" );
                this.removeHintForColumn();
            },

            notif_newScores: function( notif )
            {
                for( var player_id in notif.args.scores )
                {
                    var newScore = notif.args.scores[ player_id ];
                    this.scoreCtrl[ player_id ].toValue( newScore );
                }
            },

            notif_playerPanel: function ( notif ) {
                var length = notif.args.path_length;
                var player_id = notif.args.player_id;
                dojo.byId("path-to-go_p"+player_id).innerHTML = length;
            },


            notif_rollDiceDone: function( notif )
            {
                // Remove current possible moves (makes the board more clear)
                var dice1_result = notif.args.dice1_value;
                var dice2_result = notif.args.dice2_value;
                var dice3_result = notif.args.dice3_value;
                var dice4_result = notif.args.dice4_value;

                // the game value has change
                // for dice 1
                dojo.removeClass( 'dice_1', ['dice_1_0', 'dice_1_1', 'dice_1_2', 'dice_1_3', 'dice_1_4', 'dice_1_5', 'dice_1_6', 'dice_usable_0']);
                dojo.addClass ('dice_1', 'dice_1_' + dice1_result);
                // for dice 2
                dojo.removeClass( 'dice_2', ['dice_2_0', 'dice_2_1', 'dice_2_2', 'dice_2_3', 'dice_2_4', 'dice_2_5', 'dice_2_6', 'dice_usable_0']);
                dojo.addClass ('dice_2', 'dice_2_' + dice2_result);
                // for dice 3
                dojo.removeClass( 'dice_3', ['dice_3_0', 'dice_3_1', 'dice_3_2', 'dice_3_3', 'dice_3_4', 'dice_3_5', 'dice_3_6', 'dice_usable_0']);
                dojo.addClass ('dice_3', 'dice_3_' + dice3_result);
                // for dice 4
                dojo.removeClass( 'dice_4', ['dice_4_0', 'dice_4_1', 'dice_4_2', 'dice_4_3', 'dice_4_4', 'dice_4_5', 'dice_4_6', 'dice_usable_0']);
                dojo.addClass ('dice_4', 'dice_4_' + dice4_result);
            },

            /**
             *
             * @param notif
             */
            notif_checkerMoved: function( notif )
            {
                // update board

                this.removeHintForColumn();

                var iAmActivePlayer = this.isCurrentPlayerActive();
                var activePlayerId = notif.args.activePlayerId;
                var passivePlayerId =  notif.args.passivePlayerId;
                var activePlayerColor = notif.args.activePlayerColor;
                var passivePlayerColor =  notif.args.passivePlayerColor;
                var activePlayerPitId =  notif.args.activePlayerPitId;
                var passivePlayerPitId =  notif.args.passivePlayerPitId;
                var startColumn = notif.args.startColumn;
                var startColumnTokenNb = notif.args.startColumnTokenNb;
                var startColumnPlayerId = notif.args.startColumnPlayerId;
                var endColumn = notif.args.endColumn;
                var endColumnTokenNb = notif.args.endColumnTokenNb;
                var endColumnPlayerId = notif.args.endColumnPlayerId;
                var diceUsedIds = notif.args.diceUsedIds;
                // if a checker has been eaten
                var eatChecker = notif.args.eatChecker;
                var tokenNbInPitAfterEat = notif.args.tokenNbInPitAfterEat;
                var nbTokenInRepo = notif.args.nbTokenInRepo;

                //console.log("MOVE TOKEN");
                //console.log(notif.args);
                // depend if you are active player or passive player, you see the board topDown

                if (endColumn <= 26) {
                    // not a move to repo
                    this.removeHintForColumn(startColumn);
                    this.emptyColumn(startColumn);
                    this.displayColumn(startColumn, startColumnTokenNb, activePlayerId, activePlayerColor, iAmActivePlayer, activePlayerColor, 'start');

                    // anim
                    this.animToken(
                        iAmActivePlayer,
                        activePlayerId,
                        passivePlayerId,
                        activePlayerColor,
                        passivePlayerColor,
                        activePlayerPitId,
                        passivePlayerPitId,
                        startColumn,
                        endColumn,
                        endColumnTokenNb,
                        endColumnPlayerId,
                        diceUsedIds,
                        eatChecker,
                        tokenNbInPitAfterEat,
                        0
                    );

                } else {
                    // move to repo, free checker
                    this.removeHintForColumn(startColumn);
                    this.emptyColumn(startColumn);
                    this.displayColumn(startColumn, startColumnTokenNb, activePlayerId, activePlayerColor, iAmActivePlayer, activePlayerColor, 'start');


                    // anim
                    this.animToken(
                        iAmActivePlayer,
                        activePlayerId,
                        passivePlayerId,
                        activePlayerColor,
                        passivePlayerColor,
                        activePlayerPitId,
                        passivePlayerPitId,
                        startColumn,
                        endColumn,
                        endColumnTokenNb,
                        endColumnPlayerId,
                        diceUsedIds,
                        eatChecker,
                        tokenNbInPitAfterEat,
                        nbTokenInRepo
                    );

                    //this.addCheckerToRepo(movingPlayerId, endColumnTokenNb);
                    //this.makeDiceUnusable(diceUsedIds);
                }
            },

            /**
             *
             * @param notif
             */
            notif_cantPlay: function(notif)
            {
                var cantPlayPlayerName = notif.args.player_name;
                var cantPlayPlayerId = notif.args.playerId;

                if (this.myId == cantPlayPlayerId) {
                    var youcannotplay = _("You cant play.");
                    this.showMessage( youcannotplay , "info" );
                } else {
                    var hecannotplay = _(cantPlayPlayerName + " can't play.");
                    this.showMessage( hecannotplay , "info" );
                }
            },

            notif_selectPlayer: function(notif)
            {
                // just wait
                this.removeHintForColumn();
            },

            notif_personalizationSaved: function(notif)
            {
                log(notif.args);

                this.personalization.ltr = notif.args.personalization.ltr;
                this.personalization.displayNumbers = notif.args.personalization.displayNumbers;
                this.personalization.forcedMoves = notif.args.personalization.forcedMoves;
                this.personalization.askForCube = notif.args.personalization.askForCube;

            },

            /**
             *
             * @param iAmActivePlayer
             * @param activePlayerId
             * @param passivePlayerId
             * @param activePlayerColor
             * @param passivePlayerColor
             * @param activePlayerPitId
             * @param passivePlayerPitId
             * @param startColumn
             * @param endColumn
             * @param endColumnTokenNb
             * @param endColumnPlayerId
             * @param diceUsedIds
             * @param eatChecker
             * @param tokenNbInPitAfterEat
             * @param nbTokenInRepo
             */
            animToken: function (
                iAmActivePlayer,
                activePlayerId,
                passivePlayerId,
                activePlayerColor,
                passivePlayerColor,
                activePlayerPitId,
                passivePlayerPitId,
                startColumn,
                endColumn,
                endColumnTokenNb,
                endColumnPlayerId,
                diceUsedIds,
                eatChecker,
                tokenNbInPitAfterEat,
                nbTokenInRepo)
            {
                //console.log("DEBUT ANIM");

                if (activePlayerColor == "ff0000") {
                    classanim = "anim_token_red";
                } else {
                    classanim = "anim_token_yellow";
                }

                //console.log("animToken playerId="+activePlayerId+" startCol="+startColumn+" endCol="+endColumn+" endColumnTokenNb="+endColumnTokenNb+" endColumnPlayerId="+endColumnPlayerId+" diceUsedIds="+diceUsedIds);

                startColAnim = startColumn;
                endColAnim = endColumn;

                if (endColAnim == 25) {
                    classanim = "anim_token_yellow";
                }

                if (endColAnim == 26) {
                    classanim = "anim_token_red";
                }

                //if (startCol <= 24 && endCol <= 24) {
                //    if (playerColor == 'ff0000' || currentPlayerId != movingPlayerId) {
                //        startColAnim = 25 - startCol;
                //        endColAnim = 25 - endCol;
                //    }
                //} else if (startCol == 25 || startCol == 26) {
                //    // start from pit, free a checker of mine
                //    if (playerColor == 'ff0000' || currentPlayerId != movingPlayerId) {
                //        startColAnim = 25 + (startCol - 24) % 2 ; // 25 -> 26 and 26 -> 25
                //        endColAnim = 25 - endCol;
                //    }
                //} else if (endCol  == 25 || endCol == 26) {
                //    if (playerColor == 'ff0000' || currentPlayerId != movingPlayerId) {
                //        startColAnim = 25 - startCol;
                //        endColAnim = 25 + (endCol - 24) % 2 ; // 25 -> 26 and 26 -> 25
                //    }
                //}

                //console.log("anim repaire 0 startnimCol="+startColAnim+" endanimcol="+endColAnim);

                if (endColumn <= 26) {
                    var anim = this.slideTemporaryObject('<div class="'+classanim+' "></div>',
                        'checkeranim',
                        'checker_anim_'+startColAnim,
                        'checker_anim_'+endColAnim
                    );
                } else {
                    // got o repo
                    var anim = this.slideTemporaryObject('<div class="'+classanim+' "></div>',
                        'checkeranim',
                        'checker_anim_'+startColAnim,
                        'checker_anim_'+endColAnim+'-'+nbTokenInRepo
                    );
                }


                this.param1 = endColumn;
                this.param2 = endColumnTokenNb;
                this.param3 = endColumnPlayerId;
                this.param4 = activePlayerId;
                this.param5 = diceUsedIds;
                this.param6 = eatChecker;
                this.param7 = activePlayerColor;
                // if eat checker
                this.param8 = tokenNbInPitAfterEat;
                this.param9 = iAmActivePlayer;
                this.param10 = passivePlayerId;
                this.param11 = startColumn;
                this.param12 = passivePlayerColor;
                this.param13 = tokenNbInPitAfterEat;
                this.param14 = activePlayerPitId;
                this.param15 = passivePlayerPitId;

                //console.log("anim repaire 1");

                moveInRepo = false;

                if (endColumn <= 26) {
                    dojo.connect(anim, 'onEnd', this, 'moveNotInRepo');
                } else {
                    dojo.connect(anim, 'onEnd', this, 'moveInRepo');
                }
                anim.play();
                //console.log("FIN ANIM");
            },

            /**
             *
             * @param evt
             */
            moveNotInRepo: function(evt) {
                endColumn = this.param1;
                endColumnTokenNb = this.param2;
                endColumnPlayerId = this.param3;
                activePlayerId = this.param4;
                diceUsedIds = this.param5;
                eatChecker = this.param6;
                activePlayerColor = this.param7;
                tokenInPit = this.param8;
                iAmActivePlayer = this.param9;
                passivePlayerId = this.param10;
                startColumn = this.param11;
                passivePlayerColor = this.param12;
                tokenNbInPitAfterEat = this.param13;
                activePlayerPitId = this.param14;
                passivePlayerPitId = this.param15;

                this.emptyColumn(endColumn);
                this.displayColumn(endColumn, endColumnTokenNb, endColumnPlayerId, activePlayerColor, iAmActivePlayer, activePlayerColor, 'end');
                this.makeDiceUnusable(diceUsedIds);

                if (eatChecker) {
                    // show move eating a checker
                    //console.log("moveNotInRepo EAT A CHECKER");
                    this.animToken(
                        iAmActivePlayer,
                        activePlayerId,
                        passivePlayerId,
                        activePlayerColor,
                        passivePlayerColor,
                        activePlayerPitId,
                        passivePlayerPitId,
                        endColumn,
                        passivePlayerPitId,
                        tokenNbInPitAfterEat,
                        passivePlayerId,
                        0,
                        0,
                        tokenNbInPitAfterEat,
                        0
                    );
                }
            },

            /**
             *
             * @param evt
             */
            moveInRepo: function(evt)
            {
                endColumn = this.param1;
                endColumnTokenNb = this.param2;
                endColumnPlayerId = this.param3;
                activePlayerId = this.param4;
                diceUsedIds = this.param5;
                eatChecker = this.param6;
                activePlayerColor = this.param7;
                tokenInPit = this.param8;
                iAmActivePlayer = this.param9;
                passivePlayerId = this.param10;
                startColumn = this.param11;
                passivePlayerColor = this.param12;
                tokenNbInPitAfterEat = this.param13;
                activePlayerPitId = this.param14;
                passivePlayerPitId = this.param15;

                this.addCheckerToRepo(activePlayerId, endColumnTokenNb);
                this.makeDiceUnusable(diceUsedIds);
            },

            addTokenOnBoard: function( colId, tokenId, tokenNb, playerId )
            {
                var vertpos = "top";
                if ( (colId > 12 && colId < 25)|| colId == 26) {
                    vertpos = "bottom";
                }

                if (tokenNb == 0) {
                    dojo.place(
                        this.format_block (
                            'jstpl_token',
                            {
                                vertpos: vertpos,
                                tokencol_id: tokenId,
                                token_nb: 0,
                                color: 0
                            }
                        ) ,
                        'tokens'
                    );

                } else {
                    dojo.place(
                        this.format_block (
                            'jstpl_token',
                            {
                                vertpos: vertpos,
                                tokencol_id: tokenId,
                                token_nb: tokenNb,
                                color: this.gamedatas.players[playerId].color
                            }
                        ) ,
                        'tokens'
                    );
                }

                if( $('token_'+tokenId) && $('overall_player_board_' + playerId ) && $('col_'+colId) )
                {
                    this.placeOnObject( 'token_'+tokenId, 'overall_player_board_' + playerId );
                    this.slideToObject( 'token_'+tokenId, 'col_'+colId ).play();
                }
            },

            addAnimBoxOnBoard: function(colId, animBoxId, playerId)
            {
                dojo.place(
                    this.format_block (
                        'jstpl_checker_anim',
                        {
                            anim_block_id: animBoxId
                        }
                    ) ,
                    'tokens'
                );
                this.placeOnObject( 'checker_anim_' + animBoxId, 'overall_player_board_' + playerId );
                this.slideToObject( 'checker_anim_' + animBoxId, 'col_block_checker_anim_area_' + colId ).play();
            },

            /**
             * @param in_player_id
             * @returns {number|string|CSSStyleDeclaration.color}
             */
            getPlayerColor: function (in_player_id)
            {
                if (in_player_id != 0) {
                    return this.gamedatas.players[in_player_id].color;
                } else {
                    return 0;
                }

            },

            /**
             *
             * @param dice_id
             * @param dice_value
             * @param player_id
             * @param dice_usable
             */
            addDiceOnBoard: function (dice_id, dice_value, player_id, dice_usable)
            {

                dojo.place(
                    this.format_block (
                        'jstpl_dice',
                        {
                            dice_id: dice_id,
                            dice_value: dice_value,
                            dice_usable: dice_usable
                        }
                    ),
                    'dices'
                );

                this.placeOnObject( 'dice_'+dice_id, 'overall_player_board_' + player_id);
                this.slideToObject('dice_'+dice_id, 'dicearea_'+dice_id).play();
            },

            /**
             * display hint on board for column col_id
             * @param col_id
             * @param player_id
             */
            addHintsOnBoard: function(colId, hintId, playerId)
            {
                // <div class="hint hint-empty" id="hint_${hint_col_id}"></div>
                dojo.place(
                    this.format_block (
                        'jstpl_hint',
                        {
                            hint_col_id: hintId
                        }
                    ) ,
                    'hints'
                );

                this.placeOnObjectPos( 'hint_'+hintId, 'overall_player_board_'+playerId );
                this.slideToObject( 'hint_'+hintId, 'hints_col_'+colId ).play();
            },

            /**
             *
             * @param topOrBottom
             * @param redOrYellow
             * @param tokenNb
             * @param playerId
             */
            addRepositoryOnBoard: function (topOrBottom, redOrYellow, tokenNb, playerId)
            {
                dojo.place(
                    this.format_block (
                        'jstpl_free_repository',
                        {
                            red_or_yellow: redOrYellow,
                            top_or_bottom: topOrBottom,
                            token_nb: tokenNb
                        }
                    ) ,
                    'freerepositories'
                );
                this.placeOnObjectPos( 'free-repository_' + topOrBottom, 'overall_player_board_' + playerId );
                this.slideToObject( 'free-repository_' + topOrBottom, 'free_repositoryarea_' + topOrBottom ).play();
            },

            /**
             * Display a visual hint at column num in_colnum
             * @param in_colnum
             * @param in_tab_dice_used
             */
            displayHintForColumn: function(in_colnum, in_tab_dice_used)
            {
                var class_dice_used = 'class_dice_used'; // indicate which dice are used to reach this col

                // {d2:1, d1:1, d3:0, d4:0}
                for (var key in in_tab_dice_used) {
                    if (in_tab_dice_used[key] == 1) {
                        diceNum = key[1]; // d2 => 2
                        class_dice_used += diceNum;
                    }
                }

                if (in_colnum <= 24) {
                    // add to board
                    dojo.addClass('hint_'+in_colnum, 'hint-image');
                    dojo.addClass('hint_'+in_colnum, class_dice_used);
                    tip = _('Click on the column to move the checker.');
                    this.addTooltipHtml( 'hint_'+in_colnum, tip, 1 );
                } else if (in_colnum >=26 ) {
                    // add to go to repository
                    // 27 for red, 28 for yellow
                    activePlayerId = this.getActivePlayerId();
                    if (this.myId == activePlayerId) {
                        dojo.addClass('free_hintarea_bottom', 'free_hint_image');
                        dojo.addClass('free_hintarea_bottom', class_dice_used);
                        tip = _('Click to free the checker');
                        this.addTooltipHtml( 'hint_'+in_colnum, tip, 1 );
                    } else {
                        dojo.addClass('free_hintarea_top', 'free_hint_image');
                        dojo.addClass('free_hintarea_top', class_dice_used);
                        tip = _('Click to free the checker');
                        this.addTooltipHtml( 'hint_'+in_colnum, tip, 1 );
                    }
                }
            },

            /**
             * Remove all graphic hint for column
             */
            removeHintForColumn: function()
            {
                var tabhints = dojo.query('.hint-image');
                for (var i =0; i < tabhints.length ; i++) {
                    var tab_class_hint = $(tabhints[i].id).classList.toString().split(' ');
                    for (var j=0; j < tab_class_hint.length; j++) {
                        dojo.removeClass(tabhints[i].id, tab_class_hint[j]);
                    }
                    this.addTooltipHtml(tabhints[i].id, '', 1 );
                }

                var hintClosed = dojo.query('.hint-closed');
                for (var i =0; i < hintClosed.length ; i++) {
                    dojo.removeClass(hintClosed[i].id, 'hint-closed');
                    this.addTooltipHtml( hintClosed[i].id, '', 1 );
                }

                var selectedColumn = dojo.query('.selected_column');
                for (var i=0; i < selectedColumn.length; i++) {
                    dojo.removeClass(selectedColumn[i].id, 'selected_column_top');
                    dojo.removeClass(selectedColumn[i].id, 'selected_column_bottom');
                    dojo.removeClass(selectedColumn[i].id, 'selected_column');
                }

                // remove class for go to repo hint for top
                repoTopClassHintList = $('free_hintarea_top').classList.toString().split(' ');
                for (i = 0; i < repoTopClassHintList.length; i++) {
                    if (repoTopClassHintList[i] != 'free_hint_area') {
                        dojo.removeClass("free_hintarea_top", repoTopClassHintList[i]);
                    }
                }

                // remove class for go to repo hint for bottom
                repoBottomClassHintList = $('free_hintarea_bottom').classList.toString().split(' ');
                for (i = 0; i < repoBottomClassHintList.length; i++) {
                    if (repoBottomClassHintList[i] != 'free_hint_area') {
                        dojo.removeClass("free_hintarea_bottom", repoBottomClassHintList[i]);
                    }
                }
            },

            /**
             * from dice21 return associative array {d2:1, d1:1, d3:0, d4:0}
             * @param diceCode
             */
            getListOfDiceFromDicecode: function(diceCode)
            {
                var resultList = {};
                // fill the array with values, ordre is important
                for (var i = 4; i < diceCode.length; i++) {
                    //console.log("i="+i+" et dicecode[i]="+diceCode[i]);
                    resultList['d'+diceCode[i]] = 1;
                }
                // fill non find dice with 0
                for (var i = 1; i <= 4 ; i++) {
                    if (resultList["d"+i] === undefined) {
                        resultList["d"+i] = 0;
                    }
                }

                //console.log("le table des des dans ordre pour"+diceCode);
                //console.log(resultList);

                return resultList;
            },

            /**
             * Display the blue top or bottom border for selected column
             * @param colId
             */
            addBorderSelectedColumn: function(colId)
            {
                // display the blue sign indicate selected column
                // are we top or bottom ?
                var playerId = this.myId;
                var color = this.getPlayerColor(playerId);

                if ((colId > 0 && colId <= 12) || colId == 25) {
                    topOrBottom = 'top';
                } else {
                    topOrBottom = 'bottom';
                }
                if (color == 'ff0000') {
                    if (topOrBottom == 'top') {
                        topOrBottom = 'bottom';
                    } else {
                        topOrBottom = 'top';
                    }
                }
                //console.log("met la bordure "+colId+" "+topOrBottom);
                dojo.addClass('token_'+colId, ['selected_column', 'selected_column_'+topOrBottom]);
            },

            /**
             * return the id of the column clicked, the column with the nice blue border-with-radius on top or bottom
             * @returns {number|*}
             */
            getClickedColumnId: function()
            {
                id = 0;
                // look for column with class selected_column
                selectedColumn = dojo.query('.selected_column');
                if (selectedColumn.length > 1) {
                    alert('hubc erreur nb de col selected, javascript a été modifié');
                }
                if (selectedColumn.length == 1){
                    colId = selectedColumn[0].id; // eg, token_3
                    termList = colId.split('_');
                    //console.log(termList);
                    id = termList[1];
                }

                return id;
            },

            /**
             * return class for hint for col colId
             * @param colId
             * @returns {*}
             */
            classDiceFromColId: function(colId)
            {
                classLabel = document.getElementById('hint_'+colId).className;
                return this.classDiceUsedToInt(classLabel);
            },

            /**
             * From classe class_dice_used123 return 123
             * @param classCol
             * @returns {*}
             */
            classDiceUsedToInt: function(classCol)
            {
                var tabdice = classCol.match(/class_dice_used(\d+)/);   // e.g. hint-empty hint-image class_dice_used24 (means dices 2 and 4 have been used to reach this column
                if (tabdice != null && tabdice.length > 1) {
                    dices = tabdice[1]; // e.g. 24
                    return  dices;
                }
                return '';
            },

            diceIdsToArray: function(txt)
            {
                var resultList = new Array(0, 0, 0, 0);
                for (var i=0; i < txt.length; i++) {
                    resultList[i] = txt[i];
                }
                return resultList;
            },

            /**
             * return true if col has hint displayed
             * @param colId
             * @returns {*}
             */
            colHasHint: function(colId)
            {
                //console.log("col has hint "+colId+" = "+dojo.hasClass('hint_'+colId, 'hint-image'));
                return dojo.hasClass('hint_'+colId, 'hint-image');
            },

            /**
             * light dice diceId (blue border with radius around)
             * @param diceId
             */
            lighton: function(diceId)
            {
                dojo.addClass('dice_'+diceId, 'lighton_dice');
            },

            /**
             * Display the cannot play here, bacause you'll play only 1 dice with this move, and other move can play 2
             * @param colId
             */
            displayWarningHintForColumn: function(colId, message)
            {
                if (colId <= 24) {
                    dojo.addClass('hint_'+colId, 'hint-closed');
                    this.addTooltipHtml( 'hint_'+colId, message, 1 );
                }
            },


            /**
             *
             * @param colNum                num of column to display token in
             * @param tokenNb               nb token for col
             * @param playerId              id of the player which token is moved
             * @param tokenColor            color of the token being move
             * @param iAmActivePlayer       true if I am the active player
             */
            displayColumn: function(colNum, tokenNb, playerId, tokenColor, iAmActivePlayer, activePlayerColor, startOrEnd)
            {
                var colId = 'token_'+colNum;

                if (tokenNb == 0) {
                    return;
                }

                //console.log("Display tokencolor=" + tokenColor + ' colNum=' + colNum + ' tokenNb='+tokenNb+" iamActivePlayer="+iAmActivePlayer+" activePlayerColor="+activePlayerColor);

                if (iAmActivePlayer) {
                    // normal case for yellow active player and board
                    if (colNum <= 12 || colNum == 25) {
                        topOrBottom = 'top';
                    } else {
                        topOrBottom = 'bottom';
                    }
                } else {
                    // normal case for yellow non active player and board
                    if (colNum <= 12 || colNum == 25) {
                        topOrBottom = 'bottom';
                    } else {
                        topOrBottom = 'top';
                    }
                }

                // red player board is top on bottom
                if (tokenColor == 'ff0000') {
                    topOrBottom = this.invertTopAndBottom(topOrBottom);
                }

                // yellow put red checker in pit
                if (tokenColor == 'ff0000' && colNum == 26) {
                    topOrBottom = this.invertTopAndBottom(topOrBottom);
                }

                // red free one of its checker
                if (colNum == 26 && startOrEnd == 'start') {
                    topOrBottom = this.invertTopAndBottom(topOrBottom);
                }

                if (colNum == 25) {
                    tokenColor = 'aa7903';
                }

                if (colNum == 26 ) {
                    tokenColor = 'ff0000';
                }


                dojo.addClass(colId, 'tokencolor_'+tokenColor);
                dojo.addClass(colId, 'tokennb_'+tokenNb+'_'+topOrBottom);
                //console.log("DISPLAY COLUMN FIN");
            },

            /**
             *
             * @param topOrBottom
             * @returns {*}
             */
            invertTopAndBottom: function(topOrBottom)
            {
                if (topOrBottom == 'top') {
                    return 'bottom';
                } else {
                    return 'top';
                }
            },

            /**
             * empty column of all token
             * @param colNum
             */
            emptyColumn: function(colNum)
            {
                var colId = 'token_'+colNum;

                //console.log("EMPTY COLUMN "+colNum);

                // remove color
                dojo.removeClass(colId, ['tokencolor_ff0000', 'tokencolor_aa7903']);

                // remove any number of tokens
                for (var i = 1; i <= 15; i++) {
                    dojo.removeClass(colId, ['tokennb_'+i+'_top', 'tokennb_'+i+'_bottom']);
                }
            },

            /**
             *
             */
            makeDiceUnusable: function(diceIds)
            {
                if (diceIds == '' | diceIds == 0 || diceIds == false) {
                    return;
                }

                var diceIdList = this.diceIdsToArray(diceIds);
                for (var i = 0; i < diceIdList.length; i++) {
                    if (diceIdList[i] != 0) {
                        dojo.removeClass('dice_' + diceIdList[i], 'dice_usable_1');
                        dojo.addClass('dice_' + diceIdList[i], 'dice_usable_0');
                    }
                }
            },

            /**
             * after notification from server, move a checker in reop
             * playerId is the moving player
             * @param playerId
             * @param tokenNb
             */
            addCheckerToRepo: function(playerId, tokenNb)
            {
                // remove previous class nb token for repo
                if (this.myId == playerId) {
                    var classToken = "free_repository_bottom_token_nb_";
                    var classRepo = $('free-repository_bottom').classList.toString();
                    var repoId = 'free-repository_bottom';
                } else {
                    var classToken = "free_repository_top_token_nb_";
                    var classRepo = $('free-repository_top').classList.toString();
                    var repoId = 'free-repository_top';
                }

                var regexp = '/('+classToken+'[0-9]+)/'
                regexp = eval(regexp);
                var classToRemoveList = classRepo.match(regexp);
                //console.log(classToRemoveList[0]);
                if (classToRemoveList.length > 0) {
                    dojo.removeClass(repoId, classToRemoveList[0]);
                }

                // add class for new number of token
                dojo.addClass(repoId, classToken + tokenNb);

                //console.log("FIN ADD CHECKER TO REPO Ajout class pour token in repo repoId="+repoId+" classtoken="+classToken+" tokenNb="+tokenNb);
            }



        });
    });
