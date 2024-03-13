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
 * backgammon.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in backgammon_backgammon.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */

require_once( APP_BASE_PATH."view/common/game.view.php" );

class view_backgammon_backgammon extends game_view
{
    function getGameName() {
        return "backgammon";
    }

    function build_page( $viewArgs )
    {
        // Get players
        $this->game->loadPlayersBasicInfos();

        $boardUpperRight = 487;
        $boardUpperMiddle = 266;
        $boardBottomLeft = 74;
        $boardBottomMiddle = 294;
        $boardXPit = 264;
        $boardYTop = 73;
        $boardYBottom = 324;
        $colWidth = 32;
        $hintTopDelta = 20;
        $columnHeight = 160; // in CSS file

        $margin_top = 40;
        $margin_left = 40;

        // Manage squares blocks
        $this->page->begin_block( "backgammon_backgammon", "block_square" );
        // for hints
        $this->page->begin_block( "backgammon_backgammon", "block_hints" );
        // for anim
        $this->page->begin_block( "backgammon_backgammon", "block_checker_anim" );

        // top col 1 to 6
        $count = 1;
        for ($i=1; $i <= 6; $i++) {
            $this->page->insert_block(
                "block_square",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardUpperRight - $colWidth * $count + $margin_left,
                    'TOP' => $boardYTop + $margin_top
                )
            );
            $this->page->insert_block(
                "block_hints",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardUpperRight - $colWidth * $count + $margin_left,
                    'TOP' => $boardYTop - $hintTopDelta + $margin_top
                )
            );
            $this->page->insert_block(
                "block_checker_anim",
                array(
                    'anim_block_area_id' => $i,
                    'LEFT' => $boardUpperRight - $colWidth * $count + $margin_left,
                    'TOP' => $boardYTop + $margin_top
                )
            );
            $count++;
        }
        // top col 7 to 12
        $count = 1;
        for ($i=7; $i <= 12; $i++) {
            $this->page->insert_block(
                "block_square",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardUpperMiddle - $colWidth * $count + $margin_left,
                    'TOP' => $boardYTop + $margin_top
                )
            );
            $this->page->insert_block(
                "block_hints",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardUpperMiddle - $colWidth * $count + $margin_left,
                    'TOP' => $boardYTop - $hintTopDelta + $margin_top
                )
            );
            $this->page->insert_block(
                "block_checker_anim",
                array(
                    'anim_block_area_id' => $i,
                    'LEFT' => $boardUpperMiddle - $colWidth * $count + $margin_left,
                    'TOP' => $boardYTop + $margin_top
                )
            );
            $count++;
        }
        // bottom 13 to 18
        $count = 0;
        for ($i=13; $i <= 18; $i++) {
            $this->page->insert_block(
                "block_square",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardBottomLeft + $colWidth * $count + $margin_left,
                    'TOP' => $boardYBottom + $margin_top
                )
            );
            $this->page->insert_block(
                "block_hints",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardBottomLeft + $colWidth * $count + $margin_left,
                    'TOP' => $boardYBottom + $columnHeight + $margin_top
                )
            );
            $this->page->insert_block(
                "block_checker_anim",
                array(
                    'anim_block_area_id' => $i,
                    'LEFT' => $boardBottomLeft + $colWidth * $count + $margin_left,
                    'TOP' => $boardYBottom + $margin_top + $columnHeight - 32
                )
            );
            $count++;
        }
        // bottom 19 to 24
        $count = 0;
        for ($i=19; $i <= 24; $i++) {
            $this->page->insert_block(
                "block_square",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardBottomMiddle + $colWidth * $count + $margin_left,
                    'TOP' => $boardYBottom + $margin_top
                )
            );
            $this->page->insert_block(
                "block_hints",
                array(
                    'col_id' =>  $i,
                    'LEFT' => $boardBottomMiddle + $colWidth * $count + $margin_left,
                    'TOP' => $boardYBottom + $columnHeight + $margin_top
                )
            );
            $this->page->insert_block(
                "block_checker_anim",
                array(
                    'anim_block_area_id' => $i,
                    'LEFT' => $boardBottomMiddle + $colWidth * $count + $margin_left,
                    'TOP' => $boardYBottom + $margin_top + $columnHeight - 32
                )
            );
            $count++;
        }

        // bloc for the pit
        $this->page->begin_block( "backgammon_backgammon", "block_square_pit" );

        // top pit col 25
        $this->page->insert_block(
            "block_square_pit",
            array(
                'col_id' =>  25,
                'LEFT' => $boardXPit + $margin_left,
                'TOP' => $boardYTop + $margin_top
            )
        );
        $this->page->insert_block(
            "block_checker_anim",
            array(
                'anim_block_area_id' => 25,
                'LEFT' => $boardXPit + $margin_left,
                'TOP' => $boardYTop + $margin_top
            )
        );

        // bottom pit col 26
        $this->page->insert_block(
            "block_square_pit",
            array(
                'col_id' =>  26,
                'LEFT' => $boardXPit + $margin_left,
                'TOP' => $boardYBottom + $margin_top
            )
        );
        $this->page->insert_block(
            "block_checker_anim",
            array(
                'anim_block_area_id' => 26,
                'LEFT' => $boardXPit + $margin_left,
                'TOP' => $boardYBottom + $margin_top + $columnHeight - 32
            )
        );

        // dice area
        $this->page->begin_block( "backgammon_backgammon", "block_dice" );
        $this->page->insert_block(
            "block_dice",
            array(
                'dicearea_id' =>  1,
                'LEFT' => 343 + $margin_left,
                'TOP' => 260 + $margin_top
            )
        );
        $this->page->insert_block(
            "block_dice",
            array(
                'dicearea_id' =>  2,
                'LEFT' => 395 + $margin_left,
                'TOP' => 268 + $margin_top
            )
        );
        $this->page->insert_block(
            "block_dice",
            array(
                'dicearea_id' =>  3,
                'LEFT' => 137 + $margin_left,
                'TOP' => 260 + $margin_top
            )
        );
        $this->page->insert_block(
            "block_dice",
            array(
                'dicearea_id' =>  4,
                'LEFT' => 192 + $margin_left,
                'TOP' => 268 + $margin_top
            )
        );

        // free checker hint
        $this->page->begin_block( "backgammon_backgammon", "block_free_hint" );
        $this->page->insert_block(
            "block_free_hint",
            array(
                'free_hint_id' =>  'top',
                'LEFT' => 495 + $margin_left,
                'TOP' => 74 + $margin_top
            )
        );
        $this->page->insert_block(
            "block_free_hint",
            array(
                'free_hint_id' =>  'bottom',
                'LEFT' => 495 + $margin_left,
                'TOP' => 322 + $margin_top
            )
        );


        // free checker repository
        $this->page->begin_block( "backgammon_backgammon", "block_free_repository" );
        $this->page->insert_block(
            "block_free_repository",
            array(
                'freerepository_area_id' =>  'top',
                'LEFT' => 600 + $margin_left,
                'TOP' => 94 + $margin_top
            )
        );
        $this->page->insert_block(
            "block_free_repository",
            array(
                'freerepository_area_id' =>  'bottom',
                'LEFT' => 600 + $margin_left,
                'TOP' => 342 + $margin_top
            )
        );

        for ($i = 0; $i <= 2; $i++) {
            for ($j = 0; $j <= 4; $j++) {
                $baseLeft = 652;     // 652  // 684 => 32
                $baseTopHigh = 146;   // 146  // 182 => 36

                $idHigh = '28-'.($i * 5 + $j + 1);
                $idLow = '27-'.($i * 5 + $j + 1);

                $this->page->insert_block(
                    "block_checker_anim",
                    array(
                        'anim_block_area_id' => $idHigh,
                        'LEFT' => 652 + $j * 32,
                        'TOP' => 146 + $i * 36
                    )
                );

                $this->page->insert_block(
                    "block_checker_anim",
                    array(
                        'anim_block_area_id' => $idLow,
                        'LEFT' => 652 + $j * 32,
                        'TOP' => 394 + $i * 36
                    )
                );
            }
        }

        // hauteur 396 => 250

        /*********** Do not change anything below this line  ************/
    }
}
  

