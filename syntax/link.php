<?php
/**
 * DokuWiki Plugin lawlink (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Michael Große <mic.grosse@posteo.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class syntax_plugin_lawlink_link extends DokuWiki_Syntax_Plugin {
    /**
     * @return string Syntax mode type
     */
    public function getType() {
        return 'substition';
    }
    /**
     * @return string Paragraph type
     */
    public function getPType() {
        return 'normal';
    }
    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort() {
        return 300;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~§.*?~~',$mode,'plugin_lawlink_link');
        $this->Lexer->addSpecialPattern('~~Para.*?~~',$mode,'plugin_lawlink_link');
        $this->Lexer->addSpecialPattern('~~Art\..*?~~',$mode,'plugin_lawlink_link');
    //    $this->Lexer->addEntryPattern('~~§',$mode,'plugin_lawlink_link');
    }

    //public function postConnect() {
    //    $this->Lexer->addExitPattern('~~','plugin_lawlink_link');
    //}

    /**
     * Handle matches of the lawlink syntax
     *
     * @param string $match The match of the syntax
     * @param int    $state The state of the handler
     * @param int    $pos The position in the document
     * @param Doku_Handler    $handler The handler
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler &$handler){
        $data = array();
        $matches = explode(' ',$match);
        $link = 'http://www.gesetze-im-internet.de/';
        if (end($matches) == '~~') {
            array_pop($matches);
        } else {
            $matches[count($matches)-1] = trim($matches[count($matches)-1],'~');
        }
        $law = end($matches);
        $link .= strtolower($law) . '/';
        if ($matches[0] == '~~Art.') {
            $link .= 'art_';
            $matches[0] = 'Art.';
        } else {
            $link .= '__';
            $matches[0] = '§';
        }
        $link .= $matches[1];
        $link .= '.html';
        $data[0] = $link;
        $data[1] = implode(' ',$matches);
        return $data;
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string         $mode      Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer  $renderer  The renderer
     * @param array          $data      The data from the handler() function
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer &$renderer, $data) {
        if($mode != 'xhtml') return false;
            $renderer->externallink($data[0],$data[1]);
        return true;
    }
}

// vim:ts=4:sw=4:et:
