{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- backgammon implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    backgammon_backgammon.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->


<div id="bg_board">

    <!-- Initialization of block in file backgammon.view.php -->

    <!-- BEGIN block_square -->
    <div id="col_{col_id}" class="column" style="left:{LEFT}px; top:{TOP}px;">
    </div>
    <!-- END block_square -->

    <!-- BEGIN block_square_pit -->
    <div id="col_{col_id}" class="column_pit" style="left:{LEFT}px; top:{TOP}px;">
    </div>
    <!-- END block_square_pit -->

    <!-- BEGIN block_hints -->
    <div id="hints_col_{col_id}" class="hint_box" style="left:{LEFT}px; top:{TOP}px;">
    </div>
    <!-- END block_hints -->


    <!-- BEGIN block_dice -->
    <div id="dicearea_{dicearea_id}" class="dice_area" style="left:{LEFT}px; top :{TOP}px;">
    </div>
    <!-- END block_dice -->

    <!-- BEGIN block_free_hint -->
    <div id="free_hintarea_{free_hint_id}" class="free_hint_area" style="left:{LEFT}px; top :{TOP}px;">
    </div>
    <!-- END block_free_hint -->

    <!-- BEGIN block_free_repository -->
    <div id="free_repositoryarea_{freerepository_area_id}" class="free_repository_area" style="left:{LEFT}px; top :{TOP}px;">
    </div>
    <!-- END block_free_repository -->

    <!-- BEGIN block_checker_anim -->
    <div id="col_block_checker_anim_area_{anim_block_area_id}" class="block_checker_anim" style="left:{LEFT}px; top :{TOP}px;">
    </div>
    <!-- END block_checker_anim -->

    <div id="tokens">
    </div>
    <div id="dices">
    </div>
    <div id="hints">
    </div>
    <div id="freehints">
    </div>
    <div id="freerepositories">
    </div>
    <div id="checkeranim">
    </div>

</div>


<script type="text/javascript">
    // Javascript HTML templates
    var jstpl_token='<div class="token tokencolor_${color} tokennb_${token_nb}_${vertpos}" id="token_${tokencol_id}"></div>';

    var jstpl_hint='<div class="hint hint-empty" id="hint_${hint_col_id}"></div>';

    var jstpl_dice='<div class="dice dice_${dice_id} dice_${dice_id}_${dice_value} dice_usable_${dice_usable}" id="dice_${dice_id}"></div>';

    var jstpl_free_hint='<div class="free-hint" id="free-hint_${freehint_id}"></div>';

    var jstpl_free_repository='<div class="free-repository_${red_or_yellow} free_repository_${top_or_bottom}_token_nb_${token_nb}" id="free-repository_${top_or_bottom}"></div>';

    var jstpl_checker_anim='<div id="checker_anim_${anim_block_id}" class="block_checker_anim_empty"></div>';

    var jstpl_player_board = '<div style="font-size:4px;">&nbsp;</div><div class="cp_board"><div class="icon_path_to_go" id="icon-path-to-go_${code_img}"></div><span id="path-to-go_p${player_id}">208</span></div>';

</script>


{OVERALL_GAME_FOOTER}
